<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Item;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestItem;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Integration Service User (Section 7.1)
        User::updateOrCreate(
            ['username' => 'integration-service'],
            [
                'name' => 'Integration Service Account',
                'email' => 'integration@external-system.local',
                'password' => Hash::make('password'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@external-system.local'],
            [
                'name' => 'Administrator',
                'username' => 'admin',
                'password' => Hash::make('password'),
            ]
        );

        // 2. Companies (Section 4.1 & 12)
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

        $companies = [];
        foreach ($companiesData as $c) {
            $companies[$c['code']] = Company::updateOrCreate(['code' => $c['code']], $c);
        }

        // 3. Vendors (Section 4.3 & 12)
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

        $vendors = [];
        foreach ($vendorsData as $v) {
            $vendors[$v['code']] = Vendor::updateOrCreate(['code' => $v['code']], $v);
        }

        // 4. Items (Section 4.2 & 12)
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

        $items = [];
        foreach ($itemsData as $it) {
            $items[$it['code']] = Item::updateOrCreate(['code' => $it['code']], $it);
        }

        // 5. Purchase Requests (Section 4.4 & 12)
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

        $prs = [];
        foreach ($prsData as $prRow) {
            $company = $companies[$prRow['company']];
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
                PurchaseRequestItem::create([
                    'purchase_request_id' => $pr->id,
                    'item_id' => $items[$it['item']]->id,
                    'quantity' => $it['qty'],
                    'estimated_price' => $it['est_price'],
                    'description' => $it['desc'],
                ]);
            }
            $prs[$prRow['pr_number']] = $pr;
        }

        // 6. Purchase Orders (Section 5 & 12)
        $posData = [
            [
                'po_number' => 'PO-2026-0001',
                'pr_number' => 'PR-2026-0001',
                'company' => 'TS',
                'vendor' => 'VND-001',
                'order_date' => '2026-09-14',
                'status' => 'issued',
                'description' => 'PO Pengadaan Perangkat Komputer Workstation Development',
                'tax_rate' => 0.0,
                'items' => [
                    ['item' => 'BRG-001', 'qty' => 2, 'unit_price' => 12500000, 'desc' => 'Laptop Lenovo ThinkPad L14 Gen 4'],
                    ['item' => 'BRG-002', 'qty' => 2, 'unit_price' => 1850000, 'desc' => 'Monitor LED 24 Inch IPS Full HD'],
                ],
            ],
            [
                'po_number' => 'PO-2026-0002',
                'pr_number' => 'PR-2026-0002',
                'company' => 'WJL',
                'vendor' => 'VND-004',
                'order_date' => '2026-09-12',
                'status' => 'issued',
                'description' => 'PO Bahan Kemasan & Kertas Faktur Surat Jalan Bulanan',
                'tax_rate' => 0.11,
                'items' => [
                    ['item' => 'BRG-004', 'qty' => 50, 'unit_price' => 50000, 'desc' => 'Kertas HVS SiDU A4 70 GSM'],
                    ['item' => 'BRG-007', 'qty' => 5, 'unit_price' => 540000, 'desc' => 'Lakban Bening Daimaru 2 Inch x 100 Yard'],
                ],
            ],
            [
                'po_number' => 'PO-2026-0003',
                'pr_number' => 'PR-2026-0005',
                'company' => 'TS',
                'vendor' => 'VND-002',
                'order_date' => '2026-09-13',
                'status' => 'issued',
                'description' => 'PO Perangkat Jaringan Access Point & Aksesoris Kantor Pusat',
                'tax_rate' => 0.11,
                'items' => [
                    ['item' => 'BRG-009', 'qty' => 4, 'unit_price' => 850000, 'desc' => 'Access Point Ruijie Reyee Dual Band Gigabit'],
                    ['item' => 'BRG-003', 'qty' => 6, 'unit_price' => 150000, 'desc' => 'Logitech Wireless Mouse M185 Silent'],
                ],
            ],
            [
                'po_number' => 'PO-2026-0004',
                'pr_number' => 'PR-2026-0003',
                'company' => 'IMK',
                'vendor' => 'VND-003',
                'order_date' => '2026-09-09',
                'status' => 'draft',
                'description' => 'PO Percetakan Formulir Dokumen Transaksi Continuous Form',
                'tax_rate' => 0.0,
                'items' => [
                    ['item' => 'BRG-006', 'qty' => 20, 'unit_price' => 245000, 'desc' => 'Continuous Form 3-Ply W/NCR 9.5 x 11 Logo Tisera'],
                ],
            ],
            [
                'po_number' => 'PO-2026-0005',
                'pr_number' => null,
                'company' => 'TS',
                'vendor' => 'VND-005',
                'order_date' => '2026-08-28',
                'status' => 'completed',
                'description' => 'PO Sistem Racking Heavy Duty & Armada Forklift/Hand Pallet',
                'tax_rate' => 0.11,
                'items' => [
                    ['item' => 'BRG-008', 'qty' => 6, 'unit_price' => 1450000, 'desc' => 'Heavy Duty Upright Frame 4500 x 1000 mm'],
                    ['item' => 'BRG-010', 'qty' => 2, 'unit_price' => 4200000, 'desc' => 'Hand Pallet Truck Hydraulic Krisbow 3.0 Ton'],
                ],
            ],
        ];

        foreach ($posData as $poRow) {
            $company = $companies[$poRow['company']];
            $vendor = $vendors[$poRow['vendor']];
            $prId = !empty($poRow['pr_number']) && isset($prs[$poRow['pr_number']]) ? $prs[$poRow['pr_number']]->id : null;

            $subtotal = 0;
            foreach ($poRow['items'] as $itemLine) {
                $subtotal += ($itemLine['qty'] * $itemLine['unit_price']);
            }
            $tax = round($subtotal * $poRow['tax_rate'], 2);
            $total = $subtotal + $tax;

            $po = PurchaseOrder::updateOrCreate(
                ['po_number' => $poRow['po_number']],
                [
                    'purchase_request_id' => $prId,
                    'company_id' => $company->id,
                    'vendor_id' => $vendor->id,
                    'order_date' => $poRow['order_date'],
                    'status' => $poRow['status'],
                    'description' => $poRow['description'],
                    'subtotal' => $subtotal,
                    'tax' => $tax,
                    'total' => $total,
                ]
            );

            $po->items()->delete();
            foreach ($poRow['items'] as $it) {
                $itemObj = $items[$it['item']];
                $itemSubtotal = $it['qty'] * $it['unit_price'];
                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'item_id' => $itemObj->id,
                    'quantity' => $it['qty'],
                    'unit_price' => $it['unit_price'],
                    'subtotal' => $itemSubtotal,
                    'description' => $it['desc'],
                ]);
            }
        }
    }
}
