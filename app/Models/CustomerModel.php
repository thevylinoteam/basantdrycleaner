<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
    protected $table            = 'customers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'phone',
        'address',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'name'    => 'required|min_length[2]|max_length[100]',
        'phone'   => 'required|min_length[8]|max_length[20]|is_unique[customers.phone,id,{id}]',
        'address' => 'permit_empty',
    ];

    protected $validationMessages = [
        'phone' => [
            'is_unique' => 'This mobile number is already registered.',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Search customers by name or phone number
     */
    public function searchCustomers(?string $keyword = null, int $perPage = 0)
    {
        $builder = $this;

        if (!empty($keyword)) {
            $builder = $builder->groupStart()
                               ->like('name', $keyword)
                               ->orLike('phone', $keyword)
                               ->groupEnd();
        }

        $builder->orderBy('id', 'DESC');

        if ($perPage > 0) {
            return $this->paginate($perPage);
        }

        return $builder->findAll();
    }
}
