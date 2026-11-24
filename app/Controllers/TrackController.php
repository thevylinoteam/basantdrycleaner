<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use CodeIgniter\HTTP\ResponseInterface;

class TrackController extends BaseController
{
    protected TransactionModel $transactionModel;

    public function __construct()
    {
        parent::__construct();
        $this->transactionModel = new TransactionModel();
    }

    /**
     * Public tracking view / instructions
     */
    public function index()
    {
        if (!$this->wantsJson()) {
            return view('track/index');
        }

        return $this->response->setJSON([
            'status'  => 'info',
            'message' => 'Masukkan kode transaksi (contoh: LDY-YYYYMMDD-0001) atau nomor HP pelanggan untuk melacak status cucian.',
        ]);
    }

    /**
     * Track status by transaction code or phone number
     */
    public function check()
    {
        $keyword = trim((string) ($this->request->getVar('keyword') ?? $this->request->getVar('code') ?? $this->request->getVar('phone') ?? ''));

        if (empty($keyword)) {
            if (!$this->wantsJson()) {
                return view('track/index', ['error' => 'Enter the order code or customer mobile number.', 'keyword' => '']);
            }
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Parameter pencarian (kode transaksi atau nomor HP) wajib diisi.',
            ])->setStatusCode(400);
        }

        $results = $this->transactionModel->trackByCodeOrPhone($keyword);

        if (empty($results)) {
            if (!$this->wantsJson()) {
                return view('track/index', ['error' => "Pesanan tidak ditemukan dengan kata kunci '{$keyword}'.", 'keyword' => $keyword]);
            }
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'No matching laundry orders found.',
            ])->setStatusCode(404);
        }

        if (!$this->wantsJson()) {
            return view('track/index', ['results' => $results, 'keyword' => $keyword]);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'count'   => count($results),
            'keyword' => $keyword,
            'data'    => $results,
        ]);
    }
}
