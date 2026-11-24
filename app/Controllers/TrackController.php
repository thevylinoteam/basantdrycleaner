<?php

namespace App\Controllers;

use App\Models\TransactionModel;

class TrackController extends BaseController
{
    protected TransactionModel $transactionModel;

    public function __construct()
    {
        parent::__construct();
        $this->transactionModel = new TransactionModel();
    }

    public function index()
    {
        $this->response->setHeader('Cache-Control', 'no-store, private');
        if (!$this->wantsJson()) {
            return view('track/index');
        }
        return $this->response->setJSON(['status' => 'info', 'message' => 'Enter your order code and registered mobile number.']);
    }

    public function check()
    {
        $this->response->setHeader('Cache-Control', 'no-store, private');
        $code = strtoupper(trim((string) ($this->request->getPost('keyword') ?? '')));
        $phone = trim((string) ($this->request->getPost('phone') ?? ''));
        if (!service('throttler')->check('basant-track-' . hash('sha256', $this->request->getIPAddress()), 10, 60)) {
            return $this->response->setStatusCode(429)->setBody('Too many attempts. Please try again in a minute.');
        }
        $results = [];
        if (preg_match('/^[A-Z0-9-]{5,40}$/D', $code) && preg_match('/^[+0-9 ()-]{10,20}$/D', $phone)) {
            foreach ($this->transactionModel->trackByCodeOrPhone($code) as $tx) {
                if (strcasecmp($code, (string) $tx['transaction_code']) === 0 &&
                    hash_equals(basant_whatsapp((string) $tx['customer_phone']), basant_whatsapp($phone))) {
                    $results[] = [
                        'transaction_code' => $tx['transaction_code'],
                        'status' => $tx['status'],
                        'payment_status' => $tx['payment_status'],
                        'estimated_done_at' => $tx['estimated_done_at'],
                        'balance_due' => $tx['balance_due'],
                        'items' => array_map(static fn($item) => array_intersect_key($item, array_flip(['service_name', 'qty', 'service_unit', 'subtotal'])), $tx['items']),
                    ];
                }
            }
        }
        if (!$results) {
            if (!$this->wantsJson()) {
                return view('track/index', ['error' => 'No matching order found. Check your order code and registered mobile number.', 'keyword' => $code]);
            }
            return $this->response->setStatusCode(404)->setJSON(['status' => 'error', 'message' => 'No matching order found.']);
        }
        if (!$this->wantsJson()) {
            return view('track/index', ['results' => $results, 'keyword' => $code]);
        }
        return $this->response->setJSON(['status' => 'success', 'data' => $results]);
    }
}
