<?php

use CodeIgniter\Boot;
use Config\Paths;
use Config\Services;

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

// Inject IncomingRequest for web/CSRF compatibility in CLI tests
$incomingRequest = new \CodeIgniter\HTTP\IncomingRequest(
    config('App'),
    new \CodeIgniter\HTTP\SiteURI(config('App'), 'http://localhost:8080/'),
    null,
    new \CodeIgniter\HTTP\UserAgent()
);
Services::injectMock('request', $incomingRequest);

// Initialize session service before console output
Services::session();

use App\Controllers\Auth;
use App\Controllers\CustomerController;
use App\Controllers\Dashboard;
use App\Controllers\ReportController;
use App\Controllers\ServiceController;
use App\Controllers\TrackController;
use App\Controllers\TransactionController;
use App\Models\CustomerModel;
use App\Models\TransactionModel;

$passed = 0;
$failed = 0;

function testControllerAssert(bool $condition, string $testName, string $extra = ''): void {
    global $passed, $failed;
    if ($condition) {
        echo " [PASS] $testName\n";
        $passed++;
    } else {
        echo " [FAIL] $testName | $extra\n";
        $failed++;
    }
}

echo "========================================================\n";
echo " RUNNING CONTROLLER & TAILWIND VIEW TESTS\n";
echo "========================================================\n\n";

try {
    $custModel = new CustomerModel();
    $txModel   = new TransactionModel();

    $sampleCustomer = $custModel->first();
    $sampleTx       = $txModel->first();

    // 1. Dashboard View
    echo "[1] Testing Dashboard Controller & View...\n";
    $dashboard = new Dashboard();
    $dashView = (string) $dashboard->index();
    testControllerAssert(str_contains($dashView, 'Operations Overview'), "Dashboard::index renders modern Tailwind UI");

    // 2. Customer Controller View
    echo "\n[2] Testing Customer Controller & Views...\n";
    $customerCtrl = new CustomerController();
    $custView = (string) $customerCtrl->index();
    testControllerAssert(str_contains($custView, 'Customers'), "CustomerController::index renders customer list");

    $custCreateView = (string) $customerCtrl->create();
    testControllerAssert(str_contains($custCreateView, 'Add Customer'), "CustomerController::create renders create customer form");

    if ($sampleCustomer) {
        $custEditView = (string) $customerCtrl->edit((int) $sampleCustomer['id']);
        testControllerAssert(str_contains($custEditView, 'Edit Customers'), "CustomerController::edit renders edit customer form");
    } else {
        echo " [SKIP] Customer edit test (no sample customer)\n";
    }

    // 3. Service Controller View
    echo "\n[3] Testing Service Controller & View...\n";
    $serviceCtrl = new ServiceController();
    $svcView = (string) $serviceCtrl->index();
    testControllerAssert(str_contains($svcView, 'Services & Pricing'), "ServiceController::index renders services & pricing catalog");

    // 4. Transaction Controller Views
    echo "\n[4] Testing Transaction Controller Views...\n";
    $txCtrl = new TransactionController();
    $txIndexView = (string) $txCtrl->index();
    testControllerAssert(str_contains($txIndexView, 'Laundry Orders'), "TransactionController::index renders transactions list");

    $txCreateView = (string) $txCtrl->create();
    testControllerAssert(str_contains($txCreateView, 'Create New Order'), "TransactionController::create renders dynamic order form");

    if ($sampleTx) {
        $txDetailView = (string) $txCtrl->detail((int) $sampleTx['id']);
        testControllerAssert(str_contains($txDetailView, 'Order Progress'), "TransactionController::detail renders stepper");

        $txPrintView = (string) $txCtrl->print((int) $sampleTx['id']);
        testControllerAssert(str_contains($txPrintView, 'Print Receipt'), "TransactionController::print renders receipt");
    } else {
        echo " [SKIP] Transaction detail & print (no sample transaction)\n";
    }

    // 5. Report Controller Views
    echo "\n[5] Testing Report Controller Views...\n";
    $reportCtrl = new ReportController();
    $revView = (string) $reportCtrl->revenue();
    testControllerAssert(str_contains($revView, 'Revenue Report'), "ReportController::revenue renders revenue report");

    $svcRepView = (string) $reportCtrl->byService();
    testControllerAssert(str_contains($svcRepView, 'Service Performance'), "ReportController::byService renders services report");

    // 6. Track Controller View
    echo "\n[6] Testing Track Controller View...\n";
    $trackCtrl = new TrackController();
    $trackView = (string) $trackCtrl->index();
    testControllerAssert(str_contains($trackView, 'Track Your Order'), "TrackController::index renders public tracking portal");

    // 7. Auth Controller View
    echo "\n[7] Testing Auth Controller View...\n";
    $authCtrl = new Auth();
    $loginView = (string) $authCtrl->login();
    testControllerAssert(str_contains($loginView, 'Sign In'), "Auth::login renders login card");

} catch (\Throwable $e) {
    echo "\n[ERROR EXCEPTION]: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine() . "\n";
    $failed++;
}

echo "\n========================================================\n";
echo " CONTROLLER & VIEW TEST SUMMARY: $passed PASSED, $failed FAILED\n";
echo "========================================================\n";

if ($failed > 0) {
    exit(1);
}
exit(0);
