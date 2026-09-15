<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $itemsData = [
            [
                'code' => 'BRG-001',
                'name' => 'Laptop Lenovo ThinkPad L14 Gen 4',
                'description' => 'Laptop untuk kebutuhan operasional staff IT & Finance',
                'unit' => 'unit',
                'price' => 12500000,
            ],
            [
                'code' => 'BRG-002',
                'name' => 'Monitor LED 24 Inch IPS Full HD',
                'description' => 'Monitor bezel-less untuk workstation kantor',
                'unit' => 'unit',
                'price' => 1850000,
            ],
            [
                'code' => 'BRG-003',
                'name' => 'Logitech Wireless Mouse M185 Silent',
                'description' => 'Mouse nirkabel 2.4GHz baterai tahan lama',
                'unit' => 'unit',
                'price' => 150000,
            ],
            [
                'code' => 'BRG-004',
                'name' => 'Kertas HVS SiDU A4 70 GSM (500 Lembar)',
                'description' => 'Kertas cetak surat jalan & faktur penjualan',
                'unit' => 'rim',
                'price' => 50000,
            ],
            [
                'code' => 'BRG-005',
                'name' => 'Toner Cartridge Konica Minolta TN-619K Black',
                'description' => 'Consumable mesin digital printing proofing',
                'unit' => 'pcs',
                'price' => 1200000,
            ],
            [
                'code' => 'BRG-006',
                'name' => 'Continuous Form 3-Ply W/NCR 9.5 x 11 Logo Tisera',
                'description' => 'Formulir cetak faktur rangkap 3',
                'unit' => 'box',
                'price' => 245000,
            ],
            [
                'code' => 'BRG-007',
                'name' => 'Lakban Bening Daimaru 2 Inch x 100 Yard',
                'description' => 'Lakban packing karton buku ekspedisi (Dus isi 72 roll)',
                'unit' => 'dus',
                'price' => 540000,
            ],
            [
                'code' => 'BRG-008',
                'name' => 'Heavy Duty Upright Frame 4500 x 1000 mm',
                'description' => 'Racking gudang heavy duty kapasitas 2.000 kg/level',
                'unit' => 'set',
                'price' => 1450000,
            ],
            [
                'code' => 'BRG-009',
                'name' => 'Access Point Ruijie Reyee Dual Band Gigabit',
                'description' => 'Perangkat pemancar Wi-Fi kantor cabang & gudang',
                'unit' => 'unit',
                'price' => 850000,
            ],
            [
                'code' => 'BRG-010',
                'name' => 'Hand Pallet Truck Hydraulic Krisbow 3.0 Ton',
                'description' => 'Unit hand pallet hidrolik armada bongkar muat',
                'unit' => 'unit',
                'price' => 4200000,
            ],
        ];

        foreach ($itemsData as $item) {
            Item::updateOrCreate(
                ['code' => $item['code']],
                $item
            );
        }
    }
}
