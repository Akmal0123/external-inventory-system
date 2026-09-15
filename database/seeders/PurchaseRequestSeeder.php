<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Item;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestItem;
use Illuminate\Database\Seeder;

class PurchaseRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prsData = [
            [
                'pr_number' => 'PR-2026-0001',
                'company' => 'TS',
                'requester_name' => 'Budi Santoso',
                'department' => 'IT Department',
                'request_date' => '2026-09-01',
                'status' => 'approved',
                'description' => 'Pengadaan perangkat komputer baru untuk tim development & QA',
                'items' => [
                    ['item' => 'BRG-001', 'qty' => 2, 'est_price' => 12500000, 'desc' => 'Laptop development'],
                    ['item' => 'BRG-002', 'qty' => 2, 'est_price' => 1850000, 'desc' => 'Dual monitor setup'],
                ],
            ],
            [
                'pr_number' => 'PR-2026-0002',
                'company' => 'WJL',
                'requester_name' => 'Siti Rahma',
                'department' => 'Logistik & Warehouse',
                'request_date' => '2026-09-02',
                'status' => 'approved',
                'description' => 'Pengadaan perlengkapan packing dan dokumen ekspedisi bulanan',
                'items' => [
                    ['item' => 'BRG-004', 'qty' => 50, 'est_price' => 50000, 'desc' => 'Kertas cetak surat jalan'],
                    ['item' => 'BRG-007', 'qty' => 5, 'est_price' => 540000, 'desc' => 'Stok lakban packing ekspedisi'],
                ],
            ],
            [
                'pr_number' => 'PR-2026-0003',
                'company' => 'IMK',
                'requester_name' => 'Joko Susilo',
                'department' => 'Produksi Grafika',
                'request_date' => '2026-09-05',
                'status' => 'submitted',
                'description' => 'Refill toner mesin digital printing Konica Minolta',
                'items' => [
                    ['item' => 'BRG-005', 'qty' => 4, 'est_price' => 1200000, 'desc' => 'Toner hitam cetak naskah'],
                ],
            ],
            [
                'pr_number' => 'PR-2026-0004',
                'company' => 'TS',
                'requester_name' => 'Denny Prabowo',
                'department' => 'Gudang Pusat',
                'request_date' => '2026-09-08',
                'status' => 'draft',
                'description' => 'Peremajaan rak gudang blok C & penambahan hand pallet',
                'items' => [
                    ['item' => 'BRG-008', 'qty' => 4, 'est_price' => 1450000, 'desc' => 'Rak pallet tingkat'],
                    ['item' => 'BRG-010', 'qty' => 1, 'est_price' => 4200000, 'desc' => 'Hand pallet hidrolik'],
                ],
            ],
            [
                'pr_number' => 'PR-2026-0005',
                'company' => 'TS',
                'requester_name' => 'Hendra Setiawan',
                'department' => 'IT Infrastructure',
                'request_date' => '2026-09-10',
                'status' => 'approved',
                'description' => 'Upgrade Wi-Fi kantor pusat dan kelengkapan aksesoris user',
                'items' => [
                    ['item' => 'BRG-009', 'qty' => 4, 'est_price' => 850000, 'desc' => 'AP Ruijie lantai 2 & 3'],
                    ['item' => 'BRG-003', 'qty' => 6, 'est_price' => 150000, 'desc' => 'Mouse wireless cadangan'],
                ],
            ],
        ];

        foreach ($prsData as $prRow) {
            $company = Company::firstWhere('code', $prRow['company']);
            if (! $company) {
                continue;
            }

            $pr = PurchaseRequest::updateOrCreate(
                ['pr_number' => $prRow['pr_number']],
                [
                    'company_id' => $company->id,
                    'requester_name' => $prRow['requester_name'],
                    'department' => $prRow['department'],
                    'request_date' => $prRow['request_date'],
                    'status' => $prRow['status'],
                    'description' => $prRow['description'],
                ]
            );

            $pr->items()->delete();

            foreach ($prRow['items'] as $it) {
                $item = Item::firstWhere('code', $it['item']);
                if ($item) {
                    PurchaseRequestItem::create([
                        'purchase_request_id' => $pr->id,
                        'item_id' => $item->id,
                        'quantity' => $it['qty'],
                        'estimated_price' => $it['est_price'],
                        'description' => $it['desc'],
                    ]);
                }
            }
        }
    }
}
