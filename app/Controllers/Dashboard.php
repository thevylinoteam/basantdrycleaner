<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use CodeIgniter\HTTP\ResponseInterface;

class Dashboard extends BaseController
{
    protected TransactionModel $transactionModel;

    public function __construct()
    {
        parent::__construct();
        $this->transactionModel = new TransactionModel();
    }

    /**
     * Dashboard summary endpoint
     */
    public function index()
    {
        $stats = $this->transactionModel->getDashboardStats();

        if (!$this->wantsJson()) {
            return view('dashboard/index', ['stats' => $stats]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $stats,
        ]);
    }
}
