<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name'                    => 'Cuci Kiloan Reguler (2 Hari)',
                'type'                    => 'kiloan',
                'unit'                    => 'kg',
                'price'                   => 7000.00,
                'duration_estimate_hours' => 48,
                'is_active'               => 1,
                'created_at'              => date('Y-m-d H:i:s'),
                'updated_at'              => date('Y-m-d H:i:s'),
            ],
            [
                'name'                    => 'Cuci Kiloan Kilat (1 Hari)',
                'type'                    => 'kiloan',
                'unit'                    => 'kg',
                'price'                   => 10000.00,
                'duration_estimate_hours' => 24,
                'is_active'               => 1,
                'created_at'              => date('Y-m-d H:i:s'),
                'updated_at'              => date('Y-m-d H:i:s'),
            ],
            [
                'name'                    => 'Cuci Kiloan Ekspres (6 Jam)',
                'type'                    => 'ekspres',
                'unit'                    => 'kg',
                'price'                   => 15000.00,
                'duration_estimate_hours' => 6,
                'is_active'               => 1,
                'created_at'              => date('Y-m-d H:i:s'),
                'updated_at'              => date('Y-m-d H:i:s'),
            ],
            [
                'name'                    => 'Cuci Satuan Bedcover Besar',
                'type'                    => 'satuan',
                'unit'                    => 'pcs',
                'price'                   => 25000.00,
                'duration_estimate_hours' => 48,
                'is_active'               => 1,
                'created_at'              => date('Y-m-d H:i:s'),
                'updated_at'              => date('Y-m-d H:i:s'),
            ],
            [
                'name'                    => 'Cuci Satuan Jas / Blazer',
                'type'                    => 'satuan',
                'unit'                    => 'pcs',
                'price'                   => 20000.00,
                'duration_estimate_hours' => 48,
                'is_active'               => 1,
                'created_at'              => date('Y-m-d H:i:s'),
                'updated_at'              => date('Y-m-d H:i:s'),
            ],
            [
                'name'                    => 'Setrika Saja (Kiloan)',
                'type'                    => 'kiloan',
                'unit'                    => 'kg',
                'price'                   => 5000.00,
                'duration_estimate_hours' => 24,
                'is_active'               => 1,
                'created_at'              => date('Y-m-d H:i:s'),
                'updated_at'              => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('services')->insertBatch($data);
    }
}
