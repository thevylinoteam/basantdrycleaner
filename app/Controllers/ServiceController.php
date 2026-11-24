<?php

namespace App\Controllers;

use App\Models\ServiceModel;
use CodeIgniter\HTTP\ResponseInterface;

class ServiceController extends BaseController
{
    protected ServiceModel $serviceModel;

    public function __construct()
    {
        parent::__construct();
        $this->serviceModel = new ServiceModel();
    }

    /**
     * List all services
     */
    public function index()
    {
        $onlyActive = $this->request->getGet('active');
        $perPage    = 10;
        
        $builder = $this->serviceModel;
        if ($onlyActive !== null) {
            $builder = $builder->where('is_active', 1);
        }
        
        $services = $builder->orderBy('id', 'ASC')->paginate($perPage);
        $pager    = $this->serviceModel->pager;

        if (!$this->wantsJson()) {
            return view('services/index', [
                'services' => $services,
                'pager'    => $pager,
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'count'  => count($services),
            'data'   => $services,
        ]);
    }

    /** Import the approved Basant catalogue on an existing installation. */
    public function importBasant()
    {
        if (session()->get('userRole') !== 'admin') {
            return $this->response->setStatusCode(403)->setBody('Administrator access required.');
        }
        try {
            $count = \App\Libraries\BasantCatalogue::import(\Config\Database::connect());
            return redirect()->to('/services')->with('success', $count . ' Basant services added. Existing services and prices were retained.');
        } catch (\Throwable $e) {
            log_message('error', 'Basant catalogue import failed: {message}', ['message' => $e->getMessage()]);
            return redirect()->to('/services')->with('error', 'Could not import the catalogue. No partial import was retained.');
        }
    }

    public function edit(int $id)
    {
        $service = $this->serviceModel->find($id);
        if (!$service) {
            return redirect()->to('/services')->with('error', 'Service not found.');
        }
        return view('services/edit', ['service' => $service]);
    }

    /**
     * Store new service
     */
    public function store()
    {
        $rules = [
            'name'                    => 'required|min_length[3]|max_length[100]',
            'type'                    => 'required|in_list[kiloan,satuan,ekspres]',
            'unit'                    => 'required|in_list[kg,pcs]',
            'price'                   => 'required|numeric|greater_than_equal_to[0]',
            'duration_estimate_hours' => 'required|is_natural_no_zero',
            'is_active'               => 'permit_empty|in_list[0,1]',
        ];

        if (!$this->validate($rules)) {
            if (!$this->wantsJson()) {
                return redirect()->back()->withInput()->with('error', 'Enter a valid service name, price and duration.');
            }
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors(),
            ])->setStatusCode(400);
        }

        $data = [
            'name'                    => $this->request->getVar('name'),
            'type'                    => $this->request->getVar('type'),
            'unit'                    => $this->request->getVar('unit'),
            'price'                   => $this->request->getVar('price'),
            'duration_estimate_hours' => $this->request->getVar('duration_estimate_hours'),
            'is_active'               => $this->request->getVar('is_active') ?? 1,
        ];

        $serviceId = $this->serviceModel->insert($data);
        $newService = $this->serviceModel->find($serviceId);

        if (!$this->wantsJson()) {
            return redirect()->to('/services')->with('success', 'Service added successfully.');
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Service added successfully.',
            'data'    => $newService,
        ])->setStatusCode(201);
    }

    /**
     * Update service
     */
    public function update(int $id)
    {
        $service = $this->serviceModel->find($id);
        if (!$service) {
            if (!$this->wantsJson()) {
                return redirect()->to('/services')->with('error', 'Service not found.');
            }
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Service not found.',
            ])->setStatusCode(404);
        }

        $rules = [
            'name'                    => 'permit_empty|min_length[3]|max_length[100]',
            'type'                    => 'permit_empty|in_list[kiloan,satuan,ekspres]',
            'unit'                    => 'permit_empty|in_list[kg,pcs]',
            'price'                   => 'permit_empty|numeric|greater_than_equal_to[0]',
            'duration_estimate_hours' => 'permit_empty|is_natural_no_zero',
            'is_active'               => 'permit_empty|in_list[0,1]',
        ];

        if (!$this->validate($rules)) {
            if (!$this->wantsJson()) {
                return redirect()->back()->withInput()->with('error', 'Invalid input.');
            }
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors(),
            ])->setStatusCode(400);
        }

        $data = [];
        foreach (['name', 'type', 'unit', 'price', 'duration_estimate_hours', 'is_active'] as $field) {
            $val = $this->request->getVar($field);
            if ($val !== null) {
                $data[$field] = $val;
            }
        }

        if (!empty($data)) {
            $this->serviceModel->update($id, $data);
        }

        $updated = $this->serviceModel->find($id);

        if (!$this->wantsJson()) {
            return redirect()->to('/services')->with('success', 'Service updated successfully.');
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Service updated successfully.',
            'data'    => $updated,
        ]);
    }

    /**
     * Delete service
     */
    public function delete(int $id)
    {
        $service = $this->serviceModel->find($id);
        if (!$service) {
            if (!$this->wantsJson()) {
                return redirect()->to('/services')->with('error', 'Service not found.');
            }
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Service not found.',
            ])->setStatusCode(404);
        }

        $this->serviceModel->delete($id);

        if (!$this->wantsJson()) {
            return redirect()->to('/services')->with('success', 'Service archived successfully.');
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Service archived successfully.',
        ]);
    }
}
