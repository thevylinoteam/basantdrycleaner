<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionItemModel extends Model
{
    protected $table            = 'transaction_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'transaction_id',
        'service_id',
        'qty',
        'price_per_unit',
        'subtotal',
    ];

    // Dates
    protected $useTimestamps = false;

    // Validation
    protected $validationRules = [
        'transaction_id' => 'required|is_natural_no_zero',
        'service_id'     => 'required|is_natural_no_zero',
        'qty'            => 'required|numeric|greater_than[0]',
        'price_per_unit' => 'required|numeric|greater_than_equal_to[0]',
        'subtotal'       => 'required|numeric|greater_than_equal_to[0]',
    ];

    /**
     * Get transaction items with service name and unit
     */
    public function getItemsByTransactionId(int $transactionId)
    {
        return $this->select('transaction_items.*, services.name as service_name, services.type as service_type, services.unit as service_unit')
                    ->join('services', 'services.id = transaction_items.service_id')
                    ->where('transaction_items.transaction_id', $transactionId)
                    ->findAll();
    }
}
