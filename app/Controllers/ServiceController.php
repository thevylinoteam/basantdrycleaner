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
                return redirect()->back()->withInput()->with('error', 'Validasi gagal: lengkapi nama, tarif, dan durasi.');
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
            return redirect()->to('/services')->with('success', 'Paket layanan berhasil ditambahkan.');
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Layanan berhasil ditambahkan.',
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
                return redirect()->to('/services')->with('error', 'Layanan tidak ditemukan.');
            }
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Layanan tidak ditemukan.',
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
                return redirect()->back()->withInput()->with('error', 'Validasi gagal: format input tidak valid.');
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
            return redirect()->to('/services')->with('success', 'Paket layanan berhasil diperbarui.');
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Layanan berhasil diperbarui.',
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
                return redirect()->to('/services')->with('error', 'Layanan tidak ditemukan.');
            }
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Layanan tidak ditemukan.',
            ])->setStatusCode(404);
        }

        $this->serviceModel->delete($id);

        if (!$this->wantsJson()) {
            return redirect()->to('/services')->with('success', 'Layanan berhasil dihapus.');
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Layanan berhasil dihapus.',
        ]);
    }
}
