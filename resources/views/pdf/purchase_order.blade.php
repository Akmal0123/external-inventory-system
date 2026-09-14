<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Purchase Order - {{ $po->po_number }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            font-size: 11pt;
            line-height: 1.5;
            padding: 30px;
        }
        .header {
            border-bottom: 3px double #0284c7;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .company-title {
            font-size: 18pt;
            font-weight: bold;
            color: #0369a1;
            text-transform: uppercase;
        }
        .company-address {
            font-size: 9pt;
            color: #64748b;
            margin-top: 3px;
        }
        .doc-title {
            text-align: right;
            float: right;
        }
        .doc-title h1 {
            font-size: 20pt;
            color: #0f172a;
            margin-bottom: 4px;
        }
        .doc-badge {
            display: inline-block;
            padding: 3px 10px;
            font-size: 9pt;
            font-weight: bold;
            border-radius: 4px;
            text-transform: uppercase;
            background-color: #e0f2fe;
            color: #0369a1;
        }
        .meta-container {
            width: 100%;
            margin-bottom: 25px;
        }
        .meta-col {
            width: 48%;
            float: left;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px;
        }
        .meta-col-right {
            float: right;
        }
        .meta-col h3 {
            font-size: 10pt;
            text-transform: uppercase;
            color: #64748b;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 4px;
            margin-bottom: 8px;
        }
        .meta-table {
            width: 100%;
            font-size: 9.5pt;
        }
        .meta-table td {
            padding: 3px 0;
            vertical-align: top;
        }
        .meta-label {
            color: #64748b;
            width: 35%;
        }
        .meta-value {
            font-weight: 600;
            color: #1e293b;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 9.5pt;
        }
        .items-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-weight: 600;
            text-align: left;
            padding: 8px 10px;
            border: 1px solid #0f172a;
        }
        .items-table td {
            padding: 8px 10px;
            border: 1px solid #e2e8f0;
        }
        .items-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .totals-section {
            width: 100%;
            margin-top: 15px;
        }
        .notes-box {
            width: 55%;
            float: left;
            border: 1px dashed #cbd5e1;
            background: #fafafa;
            border-radius: 6px;
            padding: 10px;
            font-size: 9pt;
        }
        .notes-box strong {
            display: block;
            margin-bottom: 4px;
            color: #475569;
        }
        .totals-table-wrapper {
            width: 40%;
            float: right;
        }
        .totals-table {
            width: 100%;
            font-size: 10pt;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 6px 8px;
        }
        .totals-table .total-row {
            border-top: 2px solid #0f172a;
            border-bottom: 2px solid #0f172a;
            font-weight: bold;
            font-size: 11pt;
            background-color: #f1f5f9;
        }
        .signatures {
            margin-top: 50px;
            width: 100%;
        }
        .sig-box {
            width: 45%;
            float: left;
            text-align: center;
            font-size: 9.5pt;
        }
        .sig-box-right {
            float: right;
        }
        .sig-line {
            margin-top: 60px;
            border-top: 1px solid #1e293b;
            padding-top: 5px;
            font-weight: 600;
        }
        .footer {
            position: fixed;
            bottom: 10px;
            left: 30px;
            right: 30px;
            font-size: 8pt;
            color: #94a3b8;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            padding-top: 5px;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>

    <div class="header clearfix">
        <div class="doc-title">
            <h1>PURCHASE ORDER</h1>
            <span class="doc-badge">{{ strtoupper($po->status) }}</span>
        </div>
        <div>
            <div class="company-title">{{ $po->company?->name ?? 'PERUSAHAAN' }}</div>
            <div class="company-address">
                {{ $po->company?->address ?? 'Alamat Operasional Perusahaan' }}
            </div>
        </div>
    </div>

    <div class="meta-container clearfix">
        <div class="meta-col">
            <h3>Vendor / Pemasok</h3>
            <table class="meta-table">
                <tr>
                    <td class="meta-label">Nama</td>
                    <td class="meta-value">{{ $po->vendor?->name }}</td>
                </tr>
                <tr>
                    <td class="meta-label">Kode</td>
                    <td class="meta-value">{{ $po->vendor?->code }}</td>
                </tr>
                <tr>
                    <td class="meta-label">Alamat</td>
                    <td class="meta-value">{{ $po->vendor?->address ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="meta-label">Telepon</td>
                    <td class="meta-value">{{ $po->vendor?->phone ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="meta-label">Email</td>
                    <td class="meta-value">{{ $po->vendor?->email ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <div class="meta-col meta-col-right">
            <h3>Informasi Dokumen</h3>
            <table class="meta-table">
                <tr>
                    <td class="meta-label">No. PO</td>
                    <td class="meta-value" style="color:#0284c7;">{{ $po->po_number }}</td>
                </tr>
                <tr>
                    <td class="meta-label">Tanggal Order</td>
                    <td class="meta-value">{{ $po->order_date?->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td class="meta-label">No. Ref PR</td>
                    <td class="meta-value">{{ $po->purchaseRequest?->pr_number ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="meta-label">Company Code</td>
                    <td class="meta-value">{{ $po->company?->code }}</td>
                </tr>
                <tr>
                    <td class="meta-label">Tipe Transaksi</td>
                    <td class="meta-value">Purchase Order (PO)</td>
                </tr>
            </table>
        </div>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 5%;">No</th>
                <th style="width: 15%;">Kode Barang</th>
                <th style="width: 40%;">Deskripsi Barang</th>
                <th class="text-center" style="width: 8%;">Qty</th>
                <th class="text-center" style="width: 8%;">Satuan</th>
                <th class="text-right" style="width: 12%;">Harga Satuan</th>
                <th class="text-right" style="width: 12%;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($po->items as $index => $itemLine)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td><strong>{{ $itemLine->item?->code ?? '-' }}</strong></td>
                <td>
                    <div>{{ $itemLine->item?->name ?? 'Barang' }}</div>
                    @if($itemLine->description)
                        <div style="font-size: 8pt; color: #64748b;">{{ $itemLine->description }}</div>
                    @endif
                </td>
                <td class="text-center">{{ $itemLine->quantity }}</td>
                <td class="text-center">{{ $itemLine->item?->unit ?? 'unit' }}</td>
                <td class="text-right">Rp {{ number_format($itemLine->unit_price, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($itemLine->subtotal, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center" style="padding: 20px; color: #94a3b8;">Tidak ada daftar item.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="totals-section clearfix">
        <div class="notes-box">
            <strong>Catatan & Deskripsi:</strong>
            <p>{{ $po->description ?: 'Purchase Order ini diterbitkan melalui External Inventory System sebagai dokumen pengadaan resmi.' }}</p>
        </div>

        <div class="totals-table-wrapper">
            <table class="totals-table">
                <tr>
                    <td>Subtotal</td>
                    <td class="text-right">Rp {{ number_format($po->subtotal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Pajak (PPN)</td>
                    <td class="text-right">Rp {{ number_format($po->tax, 0, ',', '.') }}</td>
                </tr>
                <tr class="total-row">
                    <td>TOTAL AKHIR</td>
                    <td class="text-right">Rp {{ number_format($po->total, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="signatures clearfix">
        <div class="sig-box">
            <div>Diterima & Disetujui Vendor,</div>
            <div class="sig-line">{{ $po->vendor?->name ?? 'Pemasok' }}</div>
        </div>
        <div class="sig-box sig-box-right">
            <div>Diterbitkan oleh,</div>
            <div class="sig-line">{{ $po->company?->name ?? 'Authorized Signer' }}</div>
        </div>
    </div>

    <div class="footer">
        Dokumen ini dibuat otomatis oleh External Inventory System | Simulated Environment for Digital Approval (AMS)
    </div>

</body>
</html>
