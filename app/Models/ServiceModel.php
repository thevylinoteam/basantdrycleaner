<?php

namespace App\Models;

use CodeIgniter\Model;

class ServiceModel extends Model
{
    protected $table            = 'services';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'type',
        'unit',
        'price',
        'duration_estimate_hours',
        'is_active',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'name'                    => 'required|min_length[3]|max_length[100]',
        'type'                    => 'required|in_list[kiloan,satuan,ekspres]',
        'unit'                    => 'required|in_list[kg,pcs]',
        'price'                   => 'required|numeric|greater_than_equal_to[0]',
        'duration_estimate_hours' => 'required|is_natural_no_zero',
        'is_active'               => 'permit_empty|in_list[0,1]',
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Get only active services
     */
    public function getActiveServices()
    {
        return $this->where('is_active', 1)->findAll();
    }
}
