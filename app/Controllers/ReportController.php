<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use CodeIgniter\HTTP\ResponseInterface;

class ReportController extends BaseController
{
    protected TransactionModel $transactionModel;

    public function __construct()
    {
        parent::__construct();
        $this->transactionModel = new TransactionModel();
    }

    /**
     * Revenue report
     */
    public function revenue()
    {
        $startDate = $this->request->getGet('start_date');
        $endDate   = $this->request->getGet('end_date');

        $report = $this->transactionModel->getRevenueReport($startDate, $endDate);

        $totalRevenue = 0.00;
        $totalPayments = 0;
        foreach ($report as $row) {
            $totalRevenue  += (float) $row['total_revenue'];
            $totalPayments += (int) $row['payment_count'];
        }

        if (!$this->wantsJson()) {
            return view('reports/revenue', [
                'report'        => $report,
                'total_revenue' => $totalRevenue,
                'total_payments'=> $totalPayments,
                'start_date'    => $startDate,
                'end_date'      => $endDate,
            ]);
        }

        return $this->response->setJSON([
            'status'        => 'success',
            'period'        => [
                'start_date' => $startDate,
                'end_date'   => $endDate,
            ],
            'summary'       => [
                'total_revenue'  => $totalRevenue,
                'total_payments' => $totalPayments,
            ],
            'data'          => $report,
        ]);
    }

    /**
     * Service performance & breakdown report
     */
    public function byService()
    {
        $startDate = $this->request->getGet('start_date');
        $endDate   = $this->request->getGet('end_date');

        $report = $this->transactionModel->getServiceReport($startDate, $endDate);

        $totalRevenue = 0.00;
        foreach ($report as $row) {
            $totalRevenue += (float) $row['total_revenue'];
        }

        if (!$this->wantsJson()) {
            return view('reports/services', [
                'report'        => $report,
                'total_revenue' => $totalRevenue,
                'start_date'    => $startDate,
                'end_date'      => $endDate,
            ]);
        }

        return $this->response->setJSON([
            'status'        => 'success',
            'period'        => [
                'start_date' => $startDate,
                'end_date'   => $endDate,
            ],
            'summary'       => [
                'total_revenue' => $totalRevenue,
                'service_count' => count($report),
            ],
            'data'          => $report,
        ]);
    }
}
