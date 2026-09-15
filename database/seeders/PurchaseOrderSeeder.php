<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Item;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseRequest;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class PurchaseOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
            $company = Company::firstWhere('code', $poRow['company']);
            $vendor = Vendor::firstWhere('code', $poRow['vendor']);

            if (! $company || ! $vendor) {
                continue;
            }

            $prId = null;
            if (! empty($poRow['pr_number'])) {
                $pr = PurchaseRequest::firstWhere('pr_number', $poRow['pr_number']);
                $prId = $pr?->id;
            }

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
                $itemObj = Item::firstWhere('code', $it['item']);
                if ($itemObj) {
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
}
