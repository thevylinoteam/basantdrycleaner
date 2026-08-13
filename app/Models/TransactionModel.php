<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table            = 'transactions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'transaction_code',
        'customer_id',
        'user_id',
        'total_price',
        'status',
        'payment_status',
        'estimated_done_at',
        'picked_up_at',
        'notes',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Status Constants
    public const STATUS_DITERIMA     = 'diterima';
    public const STATUS_DICUCI       = 'dicuci';
    public const STATUS_DIKERINGKAN  = 'dikeringkan';
    public const STATUS_DISETRIKA    = 'disetrika';
    public const STATUS_SIAP_DIAMBIL = 'siap_diambil';
    public const STATUS_SELESAI      = 'selesai';

    public const STATUSES = [
        self::STATUS_DITERIMA,
        self::STATUS_DICUCI,
        self::STATUS_DIKERINGKAN,
        self::STATUS_DISETRIKA,
        self::STATUS_SIAP_DIAMBIL,
        self::STATUS_SELESAI,
    ];

    // Payment Status Constants
    public const PAYMENT_BELUM_BAYAR = 'belum_bayar';
    public const PAYMENT_DP          = 'dp';
    public const PAYMENT_LUNAS       = 'lunas';

    // Validation
    protected $validationRules = [
        'transaction_code'  => 'required|max_length[30]|is_unique[transactions.transaction_code,id,{id}]',
        'customer_id'       => 'required|is_natural_no_zero',
        'user_id'           => 'required|is_natural_no_zero',
        'total_price'       => 'required|numeric|greater_than_equal_to[0]',
        'status'            => 'required|in_list[diterima,dicuci,dikeringkan,disetrika,siap_diambil,selesai]',
        'payment_status'    => 'required|in_list[belum_bayar,dp,lunas]',
        'estimated_done_at' => 'permit_empty|valid_date',
        'picked_up_at'      => 'permit_empty|valid_date',
        'notes'             => 'permit_empty',
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Generate unique transaction code: LDY-YYYYMMDD-XXXX
     */
    public function generateTransactionCode(): string
    {
        $todayPrefix = 'LDY-' . date('Ymd') . '-';
        
        // Count today's transactions including soft-deleted ones to avoid collisions
        $countToday = $this->withDeleted()
                           ->like('transaction_code', $todayPrefix, 'after')
                           ->countAllResults();

        $sequence = str_pad($countToday + 1, 4, '0', STR_PAD_LEFT);
        $code = $todayPrefix . $sequence;

        // Double check uniqueness
        while ($this->withDeleted()->where('transaction_code', $code)->countAllResults() > 0) {
            $countToday++;
            $sequence = str_pad($countToday + 1, 4, '0', STR_PAD_LEFT);
            $code = $todayPrefix . $sequence;
        }

        return $code;
    }

    /**
     * Validate status transition sequence
     */
    public function isValidStatusTransition(string $currentStatus, string $nextStatus): bool
    {
        if ($currentStatus === $nextStatus) {
            return true;
        }

        $currentIndex = array_search($currentStatus, self::STATUSES, true);
        $nextIndex    = array_search($nextStatus, self::STATUSES, true);

        if ($currentIndex === false || $nextIndex === false) {
            return false;
        }

        // Allow moving forward step by step or admin overriding forward
        return $nextIndex > $currentIndex;
    }

    /**
     * Get filtered transactions with relations
     */
    public function getFilteredTransactions(array $filters = [], int $perPage = 0)
    {
        $builder = $this->select('transactions.*, customers.name as customer_name, customers.phone as customer_phone, users.name as user_name')
                        ->join('customers', 'customers.id = transactions.customer_id')
                        ->join('users', 'users.id = transactions.user_id');

        if (!empty($filters['status'])) {
            $builder->where('transactions.status', $filters['status']);
        }

        if (!empty($filters['payment_status'])) {
            $builder->where('transactions.payment_status', $filters['payment_status']);
        }

        if (!empty($filters['customer_id'])) {
            $builder->where('transactions.customer_id', $filters['customer_id']);
        }

        if (!empty($filters['start_date'])) {
            $builder->where('DATE(transactions.created_at) >=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $builder->where('DATE(transactions.created_at) <=', $filters['end_date']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $builder->groupStart()
                    ->like('transactions.transaction_code', $search)
                    ->orLike('customers.name', $search)
                    ->orLike('customers.phone', $search)
                    ->groupEnd();
        }

        $builder->orderBy('transactions.id', 'DESC');

        if ($perPage > 0) {
            return $this->paginate($perPage);
        }

        return $builder->findAll();
    }

    /**
     * Get transaction with full relations: customer, user, items, histories, payments
     */
    public function getTransactionWithDetails(int $id): ?array
    {
        $transaction = $this->select('transactions.*, customers.name as customer_name, customers.phone as customer_phone, customers.address as customer_address, users.name as user_name')
                            ->join('customers', 'customers.id = transactions.customer_id')
                            ->join('users', 'users.id = transactions.user_id')
                            ->where('transactions.id', $id)
                            ->first();

        if (!$transaction) {
            return null;
        }

        $itemModel    = new TransactionItemModel();
        $historyModel = new StatusHistoryModel();
        $paymentModel = new PaymentModel();

        $transaction['items']     = $itemModel->getItemsByTransactionId($id);
        $transaction['histories'] = $historyModel->getHistoryByTransactionId($id);
        $transaction['payments']  = $paymentModel->getPaymentsByTransactionId($id);

        $totalPaid = 0.00;
        foreach ($transaction['payments'] as $payment) {
            $totalPaid += (float) $payment['amount'];
        }

        $transaction['total_paid']   = $totalPaid;
        $transaction['balance_due']  = max(0.00, (float) $transaction['total_price'] - $totalPaid);

        return $transaction;
    }

    /**
     * Recalculate and update payment status for a transaction
     */
    public function recalculatePaymentStatus(int $transactionId): string
    {
        $transaction = $this->find($transactionId);
        if (!$transaction) {
            return self::PAYMENT_BELUM_BAYAR;
        }

        $paymentModel = new PaymentModel();
        $totalPaid    = $paymentModel->getTotalPaid($transactionId);
        $totalPrice   = (float) $transaction['total_price'];

        $newPaymentStatus = self::PAYMENT_BELUM_BAYAR;
        if ($totalPaid >= $totalPrice && $totalPrice > 0) {
            $newPaymentStatus = self::PAYMENT_LUNAS;
        } elseif ($totalPaid > 0) {
            $newPaymentStatus = self::PAYMENT_DP;
        }

        $this->update($transactionId, [
            'payment_status' => $newPaymentStatus,
        ]);

        return $newPaymentStatus;
    }

    /**
     * Get dashboard summary statistics
     */
    public function getDashboardStats(): array
    {
        $today = date('Y-m-d');

        // Today's total transactions
        $todayTransactionsCount = $this->where('DATE(created_at)', $today)->countAllResults();

        // Today's revenue (from payments made today)
        $paymentModel = new PaymentModel();
        $todayRevenueResult = $paymentModel->selectSum('amount')
                                           ->where('DATE(paid_at)', $today)
                                           ->first();
        $todayRevenue = (float) ($todayRevenueResult['amount'] ?? 0.00);

        // Orders ready for pickup
        $readyForPickupCount = $this->where('status', self::STATUS_SIAP_DIAMBIL)->countAllResults();

        // Recent 5 transactions
        $recentTransactions = $this->select('transactions.*, customers.name as customer_name, customers.phone as customer_phone')
                                   ->join('customers', 'customers.id = transactions.customer_id')
                                   ->orderBy('transactions.id', 'DESC')
                                   ->limit(5)
                                   ->findAll();

        return [
            'today_transactions_count' => $todayTransactionsCount,
            'today_revenue'            => $todayRevenue,
            'ready_for_pickup_count'   => $readyForPickupCount,
            'recent_transactions'      => $recentTransactions,
        ];
    }

    /**
     * Get revenue report grouped by period
     */
    public function getRevenueReport(?string $startDate = null, ?string $endDate = null)
    {
        $paymentModel = new PaymentModel();
        $builder = $paymentModel->select('DATE(paid_at) as date, SUM(amount) as total_revenue, COUNT(id) as payment_count')
                                ->groupBy('DATE(paid_at)')
                                ->orderBy('date', 'DESC');

        if ($startDate) {
            $builder->where('DATE(paid_at) >=', $startDate);
        }
        if ($endDate) {
            $builder->where('DATE(paid_at) <=', $endDate);
        }

        return $builder->findAll();
    }

    /**
     * Get service usage report
     */
    public function getServiceReport(?string $startDate = null, ?string $endDate = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('transaction_items')
                      ->select('services.id as service_id, services.name as service_name, services.type as service_type, services.unit, SUM(transaction_items.qty) as total_qty, SUM(transaction_items.subtotal) as total_revenue, COUNT(DISTINCT transaction_items.transaction_id) as transaction_count')
                      ->join('services', 'services.id = transaction_items.service_id')
                      ->join('transactions', 'transactions.id = transaction_items.transaction_id')
                      ->where('transactions.deleted_at', null)
                      ->groupBy('services.id')
                      ->orderBy('total_revenue', 'DESC');

        if ($startDate) {
            $builder->where('DATE(transactions.created_at) >=', $startDate);
        }
        if ($endDate) {
            $builder->where('DATE(transactions.created_at) <=', $endDate);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Public track status by transaction code or customer phone number
     */
    public function trackByCodeOrPhone(string $keyword): array
    {
        $transactions = $this->select('transactions.*, customers.name as customer_name, customers.phone as customer_phone')
                             ->join('customers', 'customers.id = transactions.customer_id')
                             ->groupStart()
                             ->where('transactions.transaction_code', $keyword)
                             ->orWhere('customers.phone', $keyword)
                             ->groupEnd()
                             ->orderBy('transactions.id', 'DESC')
                             ->findAll();

        $itemModel    = new TransactionItemModel();
        $historyModel = new StatusHistoryModel();
        $paymentModel = new PaymentModel();

        foreach ($transactions as &$tx) {
            $tx['items']      = $itemModel->getItemsByTransactionId($tx['id']);
            $tx['histories']  = $historyModel->getHistoryByTransactionId($tx['id']);
            $totalPaid        = $paymentModel->getTotalPaid($tx['id']);
            $tx['total_paid'] = $totalPaid;
            $tx['balance_due']= max(0.00, (float) $tx['total_price'] - $totalPaid);
        }

        return $transactions;
    }
}
