<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companiesData = [
            [
                'code' => 'TS',
                'name' => 'PT Tiga Serangkai',
                'address' => 'Jl. Dr. Supomo No. 23, Surakarta, Jawa Tengah',
            ],
            [
                'code' => 'WJL',
                'name' => 'PT Wangsa Jatra Lestari',
                'address' => 'Kawasan Industri Palur, Karanganyar, Jawa Tengah',
            ],
            [
                'code' => 'IMK',
                'name' => 'PT Inti Media Kreasi',
                'address' => 'Jl. Veteran No. 45, Surakarta, Jawa Tengah',
            ],
        ];

        foreach ($companiesData as $company) {
            Company::updateOrCreate(
                ['code' => $company['code']],
                $company
            );
        }
    }
}
