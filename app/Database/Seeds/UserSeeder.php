<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $adminPassword = getenv('BASANT_ADMIN_PASSWORD') ?: '';
        $cashierPassword = getenv('BASANT_CASHIER_PASSWORD') ?: '';
        if (ENVIRONMENT === 'testing') {
            $adminPassword = 'admin123';
            $cashierPassword = 'kasir123';
        } elseif (strlen($adminPassword) < 12 || strlen($cashierPassword) < 12 || $adminPassword === $cashierPassword) {
            throw new \RuntimeException('Set separate strong BASANT_ADMIN_PASSWORD and BASANT_CASHIER_PASSWORD environment variables before seeding.');
        }
        $data = [
            [
                'name'       => 'Basant Administrator',
                'email'      => ENVIRONMENT === 'testing' ? 'admin@laundry.com' : (getenv('BASANT_ADMIN_EMAIL') ?: 'thevylinoteam@gmail.com'),
                'password'   => password_hash($adminPassword, PASSWORD_BCRYPT),
                'role'       => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'Basant Cashier',
                'email'      => 'kasir@laundry.com',
                'password'   => password_hash($cashierPassword, PASSWORD_BCRYPT),
                'role'       => 'kasir',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('users')->insertBatch($data);
    }
}
