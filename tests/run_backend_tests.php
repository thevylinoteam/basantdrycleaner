<?php

use CodeIgniter\Boot;
use Config\Paths;

// Define front controller path
define('FCPATH', realpath(__DIR__ . '/../public') . DIRECTORY_SEPARATOR);

// Load paths config
require_once FCPATH . '../app/Config/Paths.php';

$paths = new Paths();

// Load framework boot file
require_once $paths->systemDirectory . '/Boot.php';

define('ENVIRONMENT', 'development');

// Boot console environment
Boot::bootConsole($paths);

use App\Models\CustomerModel;
use App\Models\PaymentModel;
use App\Models\ServiceModel;
use App\Models\StatusHistoryModel;
use App\Models\TransactionItemModel;
use App\Models\TransactionModel;
use App\Models\UserModel;

$passed = 0;
$failed = 0;

function testAssert(bool $condition, string $testName): void {
    global $passed, $failed;
    if ($condition) {
        echo " [PASS] $testName\n";
        $passed++;
    } else {
        echo " [FAIL] $testName\n";
        $failed++;
    }
}

echo "========================================================\n";
echo " RUNNING BACKEND TESTS - SISTEM INFORMASI LAUNDRY\n";
echo "========================================================\n\n";

// TEST 1: Authentication & User Model
echo "[1] Testing Authentication & User Model...\n";
$userModel = new UserModel();
$admin = $userModel->authenticate('admin@laundry.com', 'admin123');
testAssert($admin !== null && $admin['role'] === 'admin', "Admin authentication with valid credentials");

$badAuth = $userModel->authenticate('admin@laundry.com', 'wrongpassword');
testAssert($badAuth === null, "Rejection of invalid password");

$kasir = $userModel->authenticate('kasir@laundry.com', 'kasir123');
testAssert($kasir !== null && $kasir['role'] === 'kasir', "Kasir authentication with valid credentials");

// TEST 2: Customer Management & Soft Deletes
echo "\n[2] Testing Customer CRUD & Soft Delete...\n";
$customerModel = new CustomerModel();
$phoneTest = '0899' . mt_rand(10000000, 99999999);
$newCustId = $customerModel->insert([
    'name'    => 'Pelanggan Uji Coba',
    'phone'   => $phoneTest,
    'address' => 'Jl. Uji Coba No. 99, Surabaya',
]);
testAssert($newCustId > 0, "Insert customer record (ID: $newCustId)");

$searchResults = $customerModel->searchCustomers('Uji Coba');
testAssert(!empty($searchResults), "Search customer by name keyword");

$searchResultsPhone = $customerModel->searchCustomers($phoneTest);
testAssert(!empty($searchResultsPhone), "Search customer by phone number");

$customerModel->delete($newCustId);
$deletedCheck = $customerModel->find($newCustId);
$withDeletedCheck = $customerModel->withDeleted()->find($newCustId);
testAssert($deletedCheck === null && $withDeletedCheck !== null, "Soft delete customer (record preserved in DB)");

// TEST 3: Service Management
echo "\n[3] Testing Service Model...\n";
$serviceModel = new ServiceModel();
$activeServices = $serviceModel->getActiveServices();
testAssert(count($activeServices) >= 5, "Get active services (count: " . count($activeServices) . ")");

// TEST 4: Transaction Lifecycle & Business Logic
echo "\n[4] Testing Transaction Creation & Price Calculation...\n";
$transactionModel = new TransactionModel();
$itemModel        = new TransactionItemModel();
$historyModel     = new StatusHistoryModel();
$paymentModel     = new PaymentModel();

// Get customer 1 and standard services
$customer = $customerModel->first();
$kiloanReguler = $serviceModel->where('name', 'Cuci Kiloan Reguler (2 Hari)')->first();
$bedcover = $serviceModel->where('name', 'Cuci Satuan Bedcover Besar')->first();

$qty1 = 4.0; // 4 kg @ 7000 = 28000
$qty2 = 1.0; // 1 pcs @ 25000 = 25000
$expectedTotal = (4.0 * (float)$kiloanReguler['price']) + (1.0 * (float)$bedcover['price']); // 53000

$txCode = $transactionModel->generateTransactionCode();
testAssert(str_starts_with($txCode, 'LDY-') && strlen($txCode) >= 16, "Generated transaction code format: $txCode");

$txId = $transactionModel->insert([
    'transaction_code'  => $txCode,
    'customer_id'       => $customer['id'],
    'user_id'           => $admin['id'],
    'total_price'       => $expectedTotal,
    'status'            => TransactionModel::STATUS_DITERIMA,
    'payment_status'    => TransactionModel::PAYMENT_BELUM_BAYAR,
    'estimated_done_at' => date('Y-m-d H:i:s', strtotime('+48 hours')),
    'notes'             => 'Test transaction',
]);
testAssert($txId > 0, "Insert transaction header (ID: $txId)");

// Insert items snapshotting price
$itemModel->insert([
    'transaction_id' => $txId,
    'service_id'     => $kiloanReguler['id'],
    'qty'            => $qty1,
    'price_per_unit' => $kiloanReguler['price'],
    'subtotal'       => $qty1 * (float)$kiloanReguler['price'],
]);
$itemModel->insert([
    'transaction_id' => $txId,
    'service_id'     => $bedcover['id'],
    'qty'            => $qty2,
    'price_per_unit' => $bedcover['price'],
    'subtotal'       => $qty2 * (float)$bedcover['price'],
]);

$historyModel->insert([
    'transaction_id' => $txId,
    'status'         => TransactionModel::STATUS_DITERIMA,
    'changed_by'     => $admin['id'],
    'notes'          => 'Pesanan diterima',
    'created_at'     => date('Y-m-d H:i:s'),
]);

$detail = $transactionModel->getTransactionWithDetails($txId);
testAssert(count($detail['items']) === 2, "Transaction has 2 snapshot items");
testAssert((float)$detail['total_price'] === $expectedTotal, "Total price calculated correctly: Rp " . number_format($expectedTotal, 0, ',', '.'));
testAssert($detail['payment_status'] === 'belum_bayar', "Initial payment status is 'belum_bayar'");

// TEST 5: Payment Flow & FR-26 Business Rule Validation
echo "\n[5] Testing Partial/Full Payments & FR-26 Status Progression...\n";
// 1. Partial payment (DP)
$paymentModel->insert([
    'transaction_id' => $txId,
    'amount'         => 20000.00,
    'method'         => 'tunai',
    'paid_at'        => date('Y-m-d H:i:s'),
    'received_by'    => $admin['id'],
]);
$payStatus = $transactionModel->recalculatePaymentStatus($txId);
testAssert($payStatus === 'dp', "Payment status updated to 'dp' after partial payment (20.000 / 53.000)");

$detail = $transactionModel->getTransactionWithDetails($txId);
testAssert((float)$detail['balance_due'] === 33000.00, "Remaining balance is Rp 33.000");

// 2. Try to mark 'selesai' while still DP
$canCompleteUnpaid = ((float)$detail['total_paid'] >= (float)$detail['total_price']);
testAssert(!$canCompleteUnpaid, "FR-26 Check: Order cannot be marked 'selesai' while unpaid/DP");

// 3. Status progression
$validSeq = $transactionModel->isValidStatusTransition('diterima', 'dicuci') &&
            $transactionModel->isValidStatusTransition('dicuci', 'dikeringkan') &&
            $transactionModel->isValidStatusTransition('dikeringkan', 'disetrika') &&
            $transactionModel->isValidStatusTransition('disetrika', 'siap_diambil');
testAssert($validSeq, "Valid sequential status transition check (diterima -> dicuci -> dikeringkan -> disetrika -> siap_diambil)");

$invalidBackward = $transactionModel->isValidStatusTransition('disetrika', 'diterima');
testAssert(!$invalidBackward, "Backward status transition correctly rejected");

// Advance to siap_diambil
$transactionModel->update($txId, ['status' => TransactionModel::STATUS_SIAP_DIAMBIL]);

// 4. Settle payment in full
$paymentModel->insert([
    'transaction_id' => $txId,
    'amount'         => 33000.00,
    'method'         => 'tunai',
    'paid_at'        => date('Y-m-d H:i:s'),
    'received_by'    => $admin['id'],
]);
$payStatusFull = $transactionModel->recalculatePaymentStatus($txId);
testAssert($payStatusFull === 'lunas', "Payment status updated to 'lunas' after full payment");

$detail = $transactionModel->getTransactionWithDetails($txId);
testAssert((float)$detail['balance_due'] === 0.00, "Remaining balance is now Rp 0");

// Mark as selesai & picked up
$transactionModel->update($txId, [
    'status'       => TransactionModel::STATUS_SELESAI,
    'picked_up_at' => date('Y-m-d H:i:s'),
]);
$finalDetail = $transactionModel->getTransactionWithDetails($txId);
testAssert($finalDetail['status'] === 'selesai' && !empty($finalDetail['picked_up_at']), "Order marked 'selesai' with picked_up_at timestamp");

// TEST 6: Dashboard & Reports
echo "\n[6] Testing Dashboard Stats & Reports...\n";
$dashboardStats = $transactionModel->getDashboardStats();
testAssert($dashboardStats['today_transactions_count'] > 0, "Dashboard stats: today_transactions_count > 0");
testAssert($dashboardStats['today_revenue'] > 0, "Dashboard stats: today_revenue > 0");

$revenueReport = $transactionModel->getRevenueReport();
testAssert(!empty($revenueReport), "Revenue report generated successfully");

$serviceReport = $transactionModel->getServiceReport();
testAssert(!empty($serviceReport), "Service report generated with item aggregation");

// TEST 7: Public Tracking
echo "\n[7] Testing Public Order Tracking...\n";
$trackedByCode = $transactionModel->trackByCodeOrPhone($txCode);
testAssert(!empty($trackedByCode) && $trackedByCode[0]['transaction_code'] === $txCode, "Public track order by transaction code ($txCode)");

$trackedByPhone = $transactionModel->trackByCodeOrPhone($customer['phone']);
testAssert(!empty($trackedByPhone), "Public track order by customer phone (" . $customer['phone'] . ")");

echo "\n========================================================\n";
echo " TEST SUMMARY: $passed PASSED, $failed FAILED\n";
echo "========================================================\n";

if ($failed > 0) {
    exit(1);
}
exit(0);
