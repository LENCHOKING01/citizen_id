<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemConfiguration;

class SystemConfigSeeder extends Seeder
{
    public function run()
    {
        $configs = [
            [
                'key' => 'id_format',
                'value' => 'CIT-YYYY-XXXXX',
                'description' => 'Format for generating citizen ID numbers'
            ],
            [
                'key' => 'required_documents',
                'value' => 'birth_certificate,residency_proof',
                'description' => 'Comma-separated list of required documents'
            ],
            [
                'key' => 'id_expiry_years',
                'value' => '10',
                'description' => 'Number of years before ID card expires'
            ],
        ];

        foreach ($configs as $config) {
            SystemConfiguration::firstOrCreate(
                ['key' => $config['key']],
                $config
            );
        }
    }
}