<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendorsData = [
            [
                'code' => 'VND-001',
                'name' => 'PT Teknologi Nusantara',
                'address' => 'Jl. Slamet Riyadi No. 100, Surakarta',
                'phone' => '0271-123456',
                'email' => 'vendor@example.com',
            ],
            [
                'code' => 'VND-002',
                'name' => 'PT Metrodata Electronics Tbk',
                'address' => 'Wisma Metropolitan I Lantai 16, Jakarta Selatan',
                'phone' => '021-29345800',
                'email' => 'sales@metrodata.co.id',
            ],
            [
                'code' => 'VND-003',
                'name' => 'CV Surya Grafika Solo',
                'address' => 'Jl. Urip Sumoharjo No. 88, Jebres, Surakarta',
                'phone' => '0271-652311',
                'email' => 'suryagrafika@gmail.com',
            ],
            [
                'code' => 'VND-004',
                'name' => 'CV Bintang Plastik Surakarta',
                'address' => 'Kawasan Sentra Niaga Grogol, Sukoharjo',
                'phone' => '0271-741289',
                'email' => 'bintangplastik@gmail.com',
            ],
            [
                'code' => 'VND-005',
                'name' => 'PT Sentosa Racking Indonesia',
                'address' => 'Kawasan Industri Jababeka V, Cikarang',
                'phone' => '021-89842100',
                'email' => 'info@sentosaracking.co.id',
            ],
        ];

        foreach ($vendorsData as $vendor) {
            Vendor::updateOrCreate(
                ['code' => $vendor['code']],
                $vendor
            );
        }
    }
}
