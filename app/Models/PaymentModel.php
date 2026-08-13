<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table            = 'payments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'transaction_id',
        'amount',
        'method',
        'paid_at',
        'received_by',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'transaction_id' => 'required|is_natural_no_zero',
        'amount'         => 'required|numeric|greater_than[0]',
        'method'         => 'required|in_list[tunai,transfer]',
        'paid_at'        => 'required|valid_date',
        'received_by'    => 'required|is_natural_no_zero',
    ];

    /**
     * Get payments for a transaction with receiver's name
     */
    public function getPaymentsByTransactionId(int $transactionId)
    {
        return $this->select('payments.*, users.name as received_by_name')
                    ->join('users', 'users.id = payments.received_by')
                    ->where('payments.transaction_id', $transactionId)
                    ->orderBy('payments.paid_at', 'ASC')
                    ->findAll();
    }

    /**
     * Calculate total amount paid for a transaction
     */
    public function getTotalPaid(int $transactionId): float
    {
        $result = $this->selectSum('amount')
                       ->where('transaction_id', $transactionId)
                       ->first();

        return (float) ($result['amount'] ?? 0.00);
    }
}
