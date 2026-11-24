<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use CodeIgniter\HTTP\ResponseInterface;

class CustomerController extends BaseController
{
    protected CustomerModel $customerModel;

    public function __construct()
    {
        parent::__construct();
        $this->customerModel = new CustomerModel();
    }

    /**
     * List all customers with optional search
     */
    public function index()
    {
        $search    = $this->request->getGet('search') ?? $this->request->getGet('keyword');
        $perPage   = 10;
        $customers = $this->customerModel->searchCustomers($search, $perPage);
        $pager     = $this->customerModel->pager;

        if (!$this->wantsJson()) {
            return view('customers/index', [
                'customers' => $customers,
                'search'    => $search,
                'pager'     => $pager,
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'count'  => count($customers),
            'data'   => $customers,
        ]);
    }

    /**
     * Create endpoint metadata/schema or view
     */
    public function create()
    {
        if (!$this->wantsJson()) {
            return view('customers/create');
        }

        return $this->response->setJSON([
            'status' => 'info',
            'schema' => [
                'name'    => 'required|string|min:2|max:100',
                'phone'   => 'required|string|unique:customers.phone',
                'address' => 'optional|string',
            ],
        ]);
    }

    /**
     * Store new customer
     */
    public function store()
    {
        $rules = [
            'name'    => 'required|min_length[2]|max_length[100]',
            'phone'   => 'required|min_length[8]|max_length[20]|is_unique[customers.phone]',
            'address' => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            if (!$this->wantsJson()) {
                return redirect()->back()->withInput()->with('error', 'Mobile number already registered or name is invalid.');
            }
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors(),
            ])->setStatusCode(400);
        }

        $data = [
            'name'    => $this->request->getVar('name'),
            'phone'   => $this->request->getVar('phone'),
            'address' => $this->request->getVar('address'),
        ];

        $customerId = $this->customerModel->insert($data);
        $newCustomer = $this->customerModel->find($customerId);

        if (!$this->wantsJson()) {
            return redirect()->to('/customers')->with('success', 'Customer added successfully.');
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Customer added successfully.',
            'data'    => $newCustomer,
        ])->setStatusCode(201);
    }

    /**
     * Get single customer for edit
     */
    public function edit(int $id)
    {
        $customer = $this->customerModel->find($id);
        if (!$customer) {
            if (!$this->wantsJson()) {
                return redirect()->to('/customers')->with('error', 'Customer not found.');
            }
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Customer not found.',
            ])->setStatusCode(404);
        }

        if (!$this->wantsJson()) {
            return view('customers/edit', ['customer' => $customer]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $customer,
        ]);
    }

    /**
     * Update customer
     */
    public function update(int $id)
    {
        $customer = $this->customerModel->find($id);
        if (!$customer) {
            if (!$this->wantsJson()) {
                return redirect()->to('/customers')->with('error', 'Customer not found.');
            }
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Customer not found.',
            ])->setStatusCode(404);
        }

        $rules = [
            'name'    => 'required|min_length[2]|max_length[100]',
            'phone'   => "required|min_length[8]|max_length[20]|is_unique[customers.phone,id,{$id}]",
            'address' => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            if (!$this->wantsJson()) {
                return redirect()->back()->withInput()->with('error', 'Validation failed: enter a valid name and mobile number.');
            }
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors(),
            ])->setStatusCode(400);
        }

        $data = [
            'name'    => $this->request->getVar('name'),
            'phone'   => $this->request->getVar('phone'),
            'address' => $this->request->getVar('address'),
        ];

        $this->customerModel->update($id, $data);
        $updatedCustomer = $this->customerModel->find($id);

        if (!$this->wantsJson()) {
            return redirect()->to('/customers')->with('success', 'Customer updated successfully.');
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Customer updated successfully.',
            'data'    => $updatedCustomer,
        ]);
    }

    /**
     * Delete customer (soft delete)
     */
    public function delete(int $id)
    {
        $customer = $this->customerModel->find($id);
        if (!$customer) {
            if (!$this->wantsJson()) {
                return redirect()->to('/customers')->with('error', 'Customer not found.');
            }
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Customer not found.',
            ])->setStatusCode(404);
        }

        $this->customerModel->delete($id);

        if (!$this->wantsJson()) {
            return redirect()->to('/customers')->with('success', 'Data pelanggan berhasil dihapus (soft delete).');
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data pelanggan berhasil dihapus (soft delete).',
        ]);
    }
}
