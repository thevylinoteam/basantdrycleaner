<?php

namespace App\Database\Seeds;

use App\Libraries\BasantCatalogue;
use CodeIgniter\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        BasantCatalogue::import($this->db);
    }
}
