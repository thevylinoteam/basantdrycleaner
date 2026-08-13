<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name'       => 'Budi Santoso',
                'phone'      => '081234567890',
                'address'    => 'Jl. Dharmawangsa No. 12, Surabaya',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'Siti Rahmawati',
                'phone'      => '085712345678',
                'address'    => 'Jl. Mulyorejo Indah No. 45, Surabaya',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'Ahmad Fauzi',
                'phone'      => '087890123456',
                'address'    => 'Jl. Gubeng Kertajaya No. 8, Surabaya',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('customers')->insertBatch($data);
    }
}
