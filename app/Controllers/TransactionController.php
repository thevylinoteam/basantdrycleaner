<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use App\Models\PaymentModel;
use App\Models\ServiceModel;
use App\Models\StatusHistoryModel;
use App\Models\TransactionItemModel;
use App\Models\TransactionModel;
use CodeIgniter\HTTP\ResponseInterface;

class TransactionController extends BaseController
{
    protected TransactionModel $transactionModel;
    protected TransactionItemModel $itemModel;
    protected StatusHistoryModel $historyModel;
    protected PaymentModel $paymentModel;
    protected CustomerModel $customerModel;
    protected ServiceModel $serviceModel;

    public function __construct()
    {
        parent::__construct();
        $this->transactionModel = new TransactionModel();
        $this->itemModel        = new TransactionItemModel();
        $this->historyModel     = new StatusHistoryModel();
        $this->paymentModel     = new PaymentModel();
        $this->customerModel    = new CustomerModel();
        $this->serviceModel     = new ServiceModel();
    }

    /**
     * List transactions with optional filters
     */
    public function index()
    {
        $filters = [
            'status'         => $this->request->getGet('status'),
            'payment_status' => $this->request->getGet('payment_status'),
            'customer_id'    => $this->request->getGet('customer_id'),
            'start_date'     => $this->request->getGet('start_date'),
            'end_date'       => $this->request->getGet('end_date'),
            'search'         => $this->request->getGet('search') ?? $this->request->getGet('q'),
        ];

        $perPage = 10;
        $transactions = $this->transactionModel->getFilteredTransactions(array_filter($filters), $perPage);
        $pager        = $this->transactionModel->pager;

        if (!$this->wantsJson()) {
            return view('transactions/index', [
                'transactions' => $transactions,
                'filters'      => $filters,
                'pager'        => $pager,
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'count'   => count($transactions),
            'filters' => array_filter($filters),
            'data'    => $transactions,
        ]);
    }

    /**
     * Helper endpoint to supply data for order creation form
     */
    public function create()
    {
        $customers = $this->customerModel->findAll();
        $services  = $this->serviceModel->getActiveServices();

        if (!$this->wantsJson()) {
            return view('transactions/create', [
                'customers' => $customers,
                'services'  => $services,
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'customers' => $customers,
                'services'  => $services,
            ],
        ]);
    }

    /**
     * Store new transaction
     */
    public function store(): ResponseInterface
    {
        $userId = session()->get('userId') ?? 1; // Fallback to 1 for testing / admin

        $rules = [
            'customer_id' => 'required|is_natural_no_zero',
            'items'       => 'required',
            'notes'       => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors(),
            ])->setStatusCode(400);
        }

        $customerId = (int) $this->request->getVar('customer_id');
        $customer   = $this->customerModel->find($customerId);
        if (!$customer) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Customer not found.',
            ])->setStatusCode(404);
        }

        $rawItems = $this->request->getVar('items');
        if (is_string($rawItems)) {
            $rawItems = json_decode($rawItems, true);
        }

        if (empty($rawItems) || !is_array($rawItems)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Add at least one service item.',
            ])->setStatusCode(400);
        }

        // Validate and compute items
        $parsedItems = [];
        $totalPrice  = 0.00;
        $maxDurationHours = 24;

        foreach ($rawItems as $item) {
            $serviceId = (int) ($item['service_id'] ?? 0);
            $qty       = (float) ($item['qty'] ?? 0);

            if ($serviceId <= 0 || $qty <= 0) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Each item requires a service ID and a quantity greater than zero.',
                ])->setStatusCode(400);
            }

            $service = $this->serviceModel->find($serviceId);
            if (!$service || !$service['is_active']) {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => "Layanan dengan ID {$serviceId} tidak aktif atau tidak ditemukan.",
                ])->setStatusCode(400);
            }

            $pricePerUnit = (float) $service['price'];
            $subtotal     = round($qty * $pricePerUnit, 2);
            $totalPrice  += $subtotal;

            if ((int) $service['duration_estimate_hours'] > $maxDurationHours) {
                $maxDurationHours = (int) $service['duration_estimate_hours'];
            }

            $parsedItems[] = [
                'service_id'     => $serviceId,
                'qty'            => $qty,
                'price_per_unit' => $pricePerUnit,
                'subtotal'       => $subtotal,
            ];
        }

        $estimatedDoneAt = date('Y-m-d H:i:s', strtotime("+{$maxDurationHours} hours"));
        $transactionCode = $this->transactionModel->generateTransactionCode();

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            // 1. Insert Transaction Header
            $transactionData = [
                'transaction_code'  => $transactionCode,
                'customer_id'       => $customerId,
                'user_id'           => $userId,
                'total_price'       => $totalPrice,
                'status'            => TransactionModel::STATUS_DITERIMA,
                'payment_status'    => TransactionModel::PAYMENT_BELUM_BAYAR,
                'estimated_done_at' => $estimatedDoneAt,
                'notes'             => $this->request->getVar('notes'),
            ];

            $this->transactionModel->insert($transactionData);
            $transactionId = (int) $this->transactionModel->getInsertID();

            // 2. Insert Transaction Items
            foreach ($parsedItems as &$pItem) {
                $pItem['transaction_id'] = $transactionId;
            }
            $this->itemModel->insertBatch($parsedItems);

            // 3. Log initial status history
            $this->historyModel->insert([
                'transaction_id' => $transactionId,
                'status'         => TransactionModel::STATUS_DITERIMA,
                'changed_by'     => $userId,
                'notes'          => 'New laundry order received.',
                'created_at'     => date('Y-m-d H:i:s'),
            ]);

            // 4. Check for initial payment
            $initialPaymentAmount = (float) ($this->request->getVar('payment_amount') ?? 0);
            $paymentMethod        = (string) ($this->request->getVar('payment_method') ?? 'tunai');

            if ($initialPaymentAmount > 0) {
                $this->paymentModel->insert([
                    'transaction_id' => $transactionId,
                    'amount'         => $initialPaymentAmount,
                    'method'         => in_array($paymentMethod, ['tunai', 'transfer'], true) ? $paymentMethod : 'tunai',
                    'paid_at'        => date('Y-m-d H:i:s'),
                    'received_by'    => $userId,
                ]);

                // Recalculate payment status
                $this->transactionModel->recalculatePaymentStatus($transactionId);
            }

            $db->transCommit();

            $createdTransaction = $this->transactionModel->getTransactionWithDetails($transactionId);

            if (!$this->wantsJson()) {
                return redirect()->to('/transactions/detail/' . $transactionId)->with('success', 'Order created successfully.');
            }

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Order created successfully.',
                'data'    => $createdTransaction,
            ])->setStatusCode(201);
        } catch (\Throwable $e) {
            $db->transRollback();
            if (!$this->wantsJson()) {
                return redirect()->back()->withInput()->with('error', 'Unable to create order: ' . $e->getMessage());
            }
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Unable to create order: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Get single transaction detail
     */
    public function detail(int $id)
    {
        $transaction = $this->transactionModel->getTransactionWithDetails($id);
        if (!$transaction) {
            if (!$this->wantsJson()) {
                return redirect()->to('/transactions')->with('error', 'Order not found.');
            }
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Order not found.',
            ])->setStatusCode(404);
        }

        if (!$this->wantsJson()) {
            return view('transactions/detail', ['transaction' => $transaction]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $transaction,
        ]);
    }

    /**
     * Update transaction washing status
     */
    public function updateStatus(int $id)
    {
        $userId = session()->get('userId') ?? 1;

        $transaction = $this->transactionModel->find($id);
        if (!$transaction) {
            if (!$this->wantsJson()) {
                return redirect()->to('/transactions')->with('error', 'Order not found.');
            }
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Order not found.',
            ])->setStatusCode(404);
        }

        $rules = [
            'status' => 'required|in_list[diterima,dicuci,dikeringkan,disetrika,siap_diambil,selesai]',
            'notes'  => 'permit_empty|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            if (!$this->wantsJson()) {
                return redirect()->back()->with('error', 'Invalid status.');
            }
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors(),
            ])->setStatusCode(400);
        }

        $newStatus = (string) $this->request->getVar('status');
        $notes     = (string) ($this->request->getVar('notes') ?? "Status diubah menjadi {$newStatus}");

        // Validate status transition sequence
        if (!$this->transactionModel->isValidStatusTransition($transaction['status'], $newStatus)) {
            if (!$this->wantsJson()) {
                return redirect()->back()->with('error', "Transisi status tidak valid dari '{$transaction['status']}' ke '{$newStatus}'.");
            }
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => "Transisi status tidak valid dari '{$transaction['status']}' ke '{$newStatus}'.",
            ])->setStatusCode(400);
        }

        // FR-26 Business Rule: Selesai/Diambil requires payment to be LUNAS
        if ($newStatus === TransactionModel::STATUS_SELESAI) {
            $paymentTotal = $this->paymentModel->getTotalPaid($id);
            if ($paymentTotal < (float) $transaction['total_price']) {
                if (!$this->wantsJson()) {
                    return redirect()->back()->with('error', 'The order cannot be completed or collected until fully paid.');
                }
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'The order cannot be marked completed or collected until fully paid.',
                    'data'    => [
                        'total_price' => (float) $transaction['total_price'],
                        'total_paid'  => $paymentTotal,
                        'balance_due' => (float) $transaction['total_price'] - $paymentTotal,
                    ],
                ])->setStatusCode(422);
            }
        }

        $updateData = [
            'status' => $newStatus,
        ];

        if ($newStatus === TransactionModel::STATUS_SELESAI && empty($transaction['picked_up_at'])) {
            $updateData['picked_up_at'] = date('Y-m-d H:i:s');
        }

        $this->transactionModel->update($id, $updateData);

        // Record history
        $this->historyModel->insert([
            'transaction_id' => $id,
            'status'         => $newStatus,
            'changed_by'     => $userId,
            'notes'          => $notes,
            'created_at'     => date('Y-m-d H:i:s'),
        ]);

        $updatedTransaction = $this->transactionModel->getTransactionWithDetails($id);

        if (!$this->wantsJson()) {
            return redirect()->to('/transactions/detail/' . $id)->with('success', "Status cucian berhasil diubah menjadi '{$newStatus}'.");
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => "Status transaksi berhasil diperbarui menjadi '{$newStatus}'.",
            'data'    => $updatedTransaction,
        ]);
    }

    /**
     * Record payment for transaction
     */
    public function pay(int $id)
    {
        $userId = session()->get('userId') ?? 1;

        $transaction = $this->transactionModel->find($id);
        if (!$transaction) {
            if (!$this->wantsJson()) {
                return redirect()->to('/transactions')->with('error', 'Order not found.');
            }
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Order not found.',
            ])->setStatusCode(404);
        }

        $rules = [
            'amount' => 'required|numeric|greater_than[0]',
            'method' => 'permit_empty|in_list[tunai,transfer]',
        ];

        if (!$this->validate($rules)) {
            if (!$this->wantsJson()) {
                return redirect()->back()->with('error', 'Payment amount must be greater than zero.');
            }
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors(),
            ])->setStatusCode(400);
        }

        $amount = (float) $this->request->getVar('amount');
        $method = (string) ($this->request->getVar('method') ?? 'tunai');

        $this->paymentModel->insert([
            'transaction_id' => $id,
            'amount'         => $amount,
            'method'         => $method,
            'paid_at'        => date('Y-m-d H:i:s'),
            'received_by'    => $userId,
        ]);

        $newPaymentStatus = $this->transactionModel->recalculatePaymentStatus($id);
        $updatedTransaction = $this->transactionModel->getTransactionWithDetails($id);

        if (!$this->wantsJson()) {
            return redirect()->to('/transactions/detail/' . $id)->with('success', 'Payment of Rp ' . number_format($amount, 0, ',', '.') . ' berhasil dicatat.');
        }

        return $this->response->setJSON([
            'status'         => 'success',
            'message'        => 'Payment recorded successfully.',
            'payment_status' => $newPaymentStatus,
            'data'           => $updatedTransaction,
        ]);
    }

    /**
     * Soft delete transaction
     */
    public function delete(int $id)
    {
        $transaction = $this->transactionModel->find($id);
        if (!$transaction) {
            if (!$this->wantsJson()) {
                return redirect()->to('/transactions')->with('error', 'Order not found.');
            }
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Order not found.',
            ])->setStatusCode(404);
        }

        $this->transactionModel->delete($id);

        if (!$this->wantsJson()) {
            return redirect()->to('/transactions')->with('success', 'Transaksi berhasil dihapus (soft delete).');
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Transaksi berhasil dihapus (soft delete).',
        ]);
    }

    /**
     * Print receipt payload
     */
    public function print(int $id)
    {
        $transaction = $this->transactionModel->getTransactionWithDetails($id);
        if (!$transaction) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Order not found.',
            ])->setStatusCode(404);
        }

        $receiptPayload = [
            'store' => [
                'name'    => 'Dry Cleaning Management System',
                'address' => 'Khermai Road, Railway Colony, Satna, Madhya Pradesh',
                'phone'   => '+91 8871280521',
            ],
            'receipt_number'    => $transaction['transaction_code'],
            'printed_at'        => date('Y-m-d H:i:s'),
            'customer'          => [
                'name'    => $transaction['customer_name'],
                'phone'   => $transaction['customer_phone'],
                'address' => $transaction['customer_address'],
            ],
            'cashier'           => $transaction['user_name'],
            'status'            => $transaction['status'],
            'payment_status'    => $transaction['payment_status'],
            'estimated_done_at' => $transaction['estimated_done_at'],
            'items'             => $transaction['items'],
            'total_price'       => (float) $transaction['total_price'],
            'total_paid'        => (float) $transaction['total_paid'],
            'balance_due'       => (float) $transaction['balance_due'],
            'notes'             => $transaction['notes'],
        ];

        if (!$this->wantsJson()) {
            return view('transactions/print', ['receipt' => $receiptPayload]);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Receipt is ready to print.',
            'data'    => $receiptPayload,
        ]);
    }
}
