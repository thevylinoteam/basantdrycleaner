<?php

namespace App\Models;

use CodeIgniter\Model;

class StatusHistoryModel extends Model
{
    protected $table            = 'status_histories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'transaction_id',
        'status',
        'changed_by',
        'notes',
        'created_at',
    ];

    // Dates
    protected $useTimestamps = false;

    // Validation
    protected $validationRules = [
        'transaction_id' => 'required|is_natural_no_zero',
        'status'         => 'required',
        'changed_by'     => 'required|is_natural_no_zero',
        'notes'          => 'permit_empty|max_length[255]',
    ];

    /**
     * Get full status history with user details
     */
    public function getHistoryByTransactionId(int $transactionId)
    {
        return $this->select('status_histories.*, users.name as changed_by_name, users.role as changed_by_role')
                    ->join('users', 'users.id = status_histories.changed_by')
                    ->where('status_histories.transaction_id', $transactionId)
                    ->orderBy('status_histories.created_at', 'ASC')
                    ->findAll();
    }
}
