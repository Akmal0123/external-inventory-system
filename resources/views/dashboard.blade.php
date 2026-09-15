<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>External Inventory System — Integration Simulator</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --primary-light: #eef2ff;
            --success: #10b981;
            --success-light: #ecfdf5;
            --warning: #f59e0b;
            --warning-light: #fffbeb;
            --danger: #ef4444;
            --danger-light: #fef2f2;
            --info: #06b6d4;
            --info-light: #ecfeff;
            --bg-body: #f8fafc;
            --bg-card: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --border-focus: #818cf8;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --radius-md: 10px;
            --radius-lg: 16px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            line-height: 1.6;
            min-height: 100vh;
        }

        /* Navbar Header */
        .navbar {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 50;
            padding: 14px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--text-main);
        }

        .brand-logo {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #4f46e5, #06b6d4);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-weight: 800;
            font-size: 20px;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .brand-text h1 {
            font-size: 17px;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .brand-text p {
            font-size: 12px;
            color: var(--text-muted);
        }

        .nav-badges {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 600;
            background: var(--bg-body);
            border: 1px solid var(--border-color);
        }

        .badge-pill .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--success);
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
        }

        /* Container */
        .container {
            max-width: 1380px;
            margin: 0 auto;
            padding: 28px 32px;
        }

        /* Hero Banner */
        .hero {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 60%, #1e293b 100%);
            border-radius: var(--radius-lg);
            padding: 32px 36px;
            color: #ffffff;
            margin-bottom: 28px;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 450px;
            height: 450px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.3) 0%, rgba(0,0,0,0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .hero-tag {
            display: inline-block;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 12px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .hero h2 {
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 8px;
        }

        .hero p {
            font-size: 14px;
            color: #cbd5e1;
            max-width: 800px;
            margin-bottom: 20px;
        }

        .hero-meta-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
        }

        .hero-meta-item {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 13px;
        }

        .hero-meta-item strong {
            color: #a5b4fc;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 18px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 20px;
            box-shadow: var(--shadow-sm);
            transition: all 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            border-color: #cbd5e1;
        }

        .stat-card .stat-label {
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 600;
            margin-bottom: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stat-card .stat-value {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: var(--text-main);
        }

        .stat-card .stat-sub {
            font-size: 12px;
            margin-top: 4px;
            color: var(--text-muted);
        }

        /* Card Section */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            margin-bottom: 28px;
        }

        .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .card-title {
            font-size: 18px;
            font-weight: 700;
        }

        .card-subtitle {
            font-size: 13px;
            color: var(--text-muted);
        }

        /* Tabs */
        .tabs {
            display: flex;
            border-bottom: 1px solid var(--border-color);
            background: #f8fafc;
            padding: 0 16px;
            overflow-x: auto;
        }

        .tab-btn {
            background: transparent;
            border: none;
            padding: 16px 20px;
            font-family: inherit;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-muted);
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .tab-btn:hover {
            color: var(--primary);
        }

        .tab-btn.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
            background: #ffffff;
        }

        .tab-content {
            display: none;
            padding: 24px;
        }

        .tab-content.active {
            display: block;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 16px;
            font-family: inherit;
            font-size: 13px;
            font-weight: 600;
            border-radius: 8px;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.15s ease;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--primary);
            color: #ffffff;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
        }

        .btn-secondary {
            background: #f1f5f9;
            color: var(--text-main);
            border-color: #cbd5e1;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
        }

        .btn-success {
            background: var(--success);
            color: #ffffff;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
            border-radius: 6px;
        }

        /* Table */
        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
        }

        th {
            background: #f8fafc;
            color: var(--text-muted);
            font-weight: 700;
            text-align: left;
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-color);
            white-space: nowrap;
        }

        td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        tbody tr:hover {
            background: #f8fafc;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .status-issued {
            background: var(--primary-light);
            color: var(--primary);
        }

        .status-approved {
            background: var(--success-light);
            color: var(--success);
        }

        .status-submitted {
            background: var(--warning-light);
            color: var(--warning);
        }

        .status-draft {
            background: #f1f5f9;
            color: #64748b;
        }

        .status-completed {
            background: #ecfeff;
            color: #0891b2;
        }

        .status-cancelled, .status-rejected {
            background: var(--danger-light);
            color: var(--danger);
        }

        /* API Tester Console */
        .api-console {
            background: #0f172a;
            border-radius: var(--radius-md);
            padding: 20px;
            color: #f8fafc;
        }

        .api-controls {
            display: flex;
            gap: 10px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }

        .api-select {
            flex: 1;
            min-width: 250px;
            padding: 10px 14px;
            background: #1e293b;
            border: 1px solid #334155;
            color: #f8fafc;
            border-radius: 8px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
        }

        .api-preview {
            background: #020617;
            border: 1px solid #1e293b;
            border-radius: 8px;
            padding: 16px;
            max-height: 380px;
            overflow-y: auto;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12.5px;
            color: #38bdf8;
            white-space: pre-wrap;
            word-break: break-all;
        }

        /* Modal */
        .modal-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 100;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .modal-backdrop.open {
            display: flex;
        }

        .modal {
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            width: 100%;
            max-width: 780px;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            border: 1px solid var(--border-color);
            animation: modalFadeIn 0.2s ease-out;
        }

        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(10px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .modal-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            font-size: 16px;
            font-weight: 700;
        }

        .modal-close {
            background: transparent;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: var(--text-muted);
        }

        .modal-body {
            padding: 24px;
            max-height: 70vh;
            overflow-y: auto;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 12.5px;
            font-weight: 600;
            margin-bottom: 6px;
            color: var(--text-main);
        }

        .form-control {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-family: inherit;
            font-size: 13.5px;
            transition: border-color 0.15s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--border-focus);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }

        .modal-footer {
            padding: 14px 24px;
            background: #f8fafc;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        /* Item Rows in PO/PR modal */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .items-table th {
            background: #f1f5f9;
            padding: 8px 10px;
            font-weight: 700;
            color: var(--text-muted);
            text-align: left;
            border-bottom: 1px solid var(--border-color);
            font-size: 11.5px;
        }

        .items-table td {
            padding: 6px 6px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .items-table select,
        .items-table input {
            width: 100%;
            padding: 6px 8px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-family: inherit;
            font-size: 12.5px;
            transition: border-color 0.15s;
        }

        .items-table select:focus,
        .items-table input:focus {
            outline: none;
            border-color: var(--border-focus);
            box-shadow: 0 0 0 2px rgba(99,102,241,0.12);
        }

        .btn-danger {
            background: var(--danger-light);
            color: var(--danger);
            border-color: #fca5a5;
        }

        .btn-danger:hover {
            background: var(--danger);
            color: #fff;
        }

        .summary-box {
            background: #f8fafc;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 12px 16px;
            display: flex;
            justify-content: flex-end;
            gap: 24px;
            font-size: 13px;
        }

        .summary-box .summary-item {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .summary-box .summary-label {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .summary-box .summary-value {
            font-size: 16px;
            font-weight: 800;
            color: var(--primary);
        }

        /* Toast Notification */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .toast {
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 14px 18px;
            min-width: 300px;
            box-shadow: var(--shadow-lg);
            display: flex;
            align-items: center;
            gap: 12px;
            animation: toastIn 0.3s ease-out;
            font-size: 13.5px;
            font-weight: 600;
        }

        .toast.success { border-left: 4px solid var(--success); }
        .toast.error { border-left: 4px solid var(--danger); }

        @keyframes toastIn {
            from { opacity: 0; transform: translateX(40px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .toast-icon { font-size: 18px; }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }
    </style>
</head>
<body>

    <!-- Top Navbar -->
    <nav class="navbar">
        <a href="/" class="brand">
            <div class="brand-logo">EIS</div>
            <div class="brand-text">
                <h1>External Inventory System</h1>
                <p>Digital Approval & Fastify Integration Simulator</p>
            </div>
        </a>
        <div class="nav-badges">
            <div class="badge-pill">
                <span class="dot"></span>
                <span>MySQL Connected</span>
            </div>
            <div class="badge-pill">
                <strong>Port:</strong> 9000
            </div>
            <div class="badge-pill">
                <strong>API Prefix:</strong> /api
            </div>
        </div>
    </nav>

    <div class="container">

        <!-- Hero Card -->
        <div class="hero">
            <span class="hero-tag">System 2 — Mock Provider</span>
            <h2>External Inventory System Service</h2>
            <p>
                Sistem simulasi penyedia data Purchase Request (PR) dan Purchase Order (PO) untuk integrasi dengan <strong>Digital Approval System (AMS)</strong> via Fastify. Siap digunakan secara lokal dengan REST API CRUD lengkap dan generator PDF.
            </p>
            <div class="hero-meta-grid">
                <div class="hero-meta-item">Database: <strong>external_inventory_db</strong></div>
                <div class="hero-meta-item">JWT Toggle: <strong>{{ env('API_JWT_ENABLED') ? 'ENABLED' : 'DISABLED (Dev Mode)' }}</strong></div>
                <div class="hero-meta-item">Integration User: <strong>integration-service</strong></div>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">
                    <span>Purchase Orders (PO)</span>
                    <span class="status-badge status-issued">{{ $stats['issued_pos_count'] }} Issued</span>
                </div>
                <div class="stat-value">{{ $stats['pos_count'] }}</div>
                <div class="stat-sub">Daftar pesanan pembelian</div>
            </div>

            <div class="stat-card">
                <div class="stat-label">
                    <span>Purchase Requests (PR)</span>
                    <span class="status-badge status-approved">{{ $stats['approved_prs_count'] }} Approved</span>
                </div>
                <div class="stat-value">{{ $stats['prs_count'] }}</div>
                <div class="stat-sub">Daftar permintaan barang</div>
            </div>

            <div class="stat-card">
                <div class="stat-label">
                    <span>Master Barang (Items)</span>
                </div>
                <div class="stat-value">{{ $stats['items_count'] }}</div>
                <div class="stat-sub">Item inventaris aktif</div>
            </div>

            <div class="stat-card">
                <div class="stat-label">
                    <span>Master Vendor</span>
                </div>
                <div class="stat-value">{{ $stats['vendors_count'] }}</div>
                <div class="stat-sub">Mitra supplier resmi</div>
            </div>

            <div class="stat-card">
                <div class="stat-label">
                    <span>Companies</span>
                </div>
                <div class="stat-value">{{ $stats['companies_count'] }}</div>
                <div class="stat-sub">Unit entitas usaha</div>
            </div>
        </div>

        <!-- Main Card with Tabs -->
        <div class="card">
            <div class="tabs">
                <button class="tab-btn active" onclick="switchTab('tab-po', this)">Purchase Orders (PO)</button>
                <button class="tab-btn" onclick="switchTab('tab-pr', this)">Purchase Requests (PR)</button>
                <button class="tab-btn" onclick="switchTab('tab-items', this)">Master Barang (Items)</button>
                <button class="tab-btn" onclick="switchTab('tab-vendors', this)">Master Vendor</button>
                <button class="tab-btn" onclick="switchTab('tab-api-tester', this)">Live API Simulator</button>
            </div>

            <!-- Tab 1: Purchase Orders -->
            <div id="tab-po" class="tab-content active">
                <div class="card-header" style="padding: 0 0 16px 0;">
                    <div>
                        <div class="card-title">Daftar Purchase Orders</div>
                        <div class="card-subtitle">Data PO yang dapat di-lookup dan ditarik oleh Fastify & Digital Approval System</div>
                    </div>
                    <button class="btn btn-primary btn-sm" onclick="openModal('modal-po')">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Tambah PO
                    </button>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>No. PO</th>
                                <th>Ref. PR</th>
                                <th>Company</th>
                                <th>Vendor</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th style="text-align: right;">Total Nilai</th>
                                <th style="text-align: center;">Aksi Dokumen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPOs as $po)
                            <tr>
                                <td>
                                    <strong style="color: var(--primary);">{{ $po->po_number }}</strong>
                                </td>
                                <td>{{ $po->purchaseRequest?->pr_number ?? '-' }}</td>
                                <td>{{ $po->company?->code }} ({{ $po->company?->name }})</td>
                                <td>{{ $po->vendor?->name }}</td>
                                <td>{{ $po->order_date?->format('d/m/Y') }}</td>
                                <td>
                                    <span class="status-badge status-{{ strtolower($po->status) }}">{{ $po->status }}</span>
                                </td>
                                <td style="text-align: right; font-weight: 700;">
                                    Rp {{ number_format($po->total, 0, ',', '.') }}
                                </td>
                                <td style="text-align: center;">
                                    <div style="display: inline-flex; gap: 6px;">
                                        <a href="/api/purchase-orders/{{ $po->id }}" target="_blank" class="btn btn-secondary btn-sm" title="Lihat JSON API">
                                            JSON API
                                        </a>
                                        <a href="/api/purchase-orders/{{ $po->id }}/pdf" target="_blank" class="btn btn-primary btn-sm" title="Download atau Preview PDF">
                                            Download PDF
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 30px;">
                                    Belum ada data Purchase Order.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab 2: Purchase Requests -->
            <div id="tab-pr" class="tab-content">
                <div class="card-header" style="padding: 0 0 16px 0;">
                    <div>
                        <div class="card-title">Daftar Purchase Requests</div>
                        <div class="card-subtitle">Permintaan pengadaan sebelum diterbitkan menjadi Purchase Order</div>
                    </div>
                    <button class="btn btn-primary btn-sm" onclick="openModal('modal-pr')">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Tambah PR
                    </button>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>No. PR</th>
                                <th>Requester</th>
                                <th>Department</th>
                                <th>Company</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th style="text-align: center;">Jumlah Item</th>
                                <th style="text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPRs as $pr)
                            <tr>
                                <td><strong style="color: var(--primary);">{{ $pr->pr_number }}</strong></td>
                                <td>{{ $pr->requester_name }}</td>
                                <td>{{ $pr->department }}</td>
                                <td>{{ $pr->company?->code }}</td>
                                <td>{{ $pr->request_date?->format('d/m/Y') }}</td>
                                <td>
                                    <span class="status-badge status-{{ strtolower($pr->status) }}">{{ $pr->status }}</span>
                                </td>
                                <td style="text-align: center;">{{ $pr->items->count() }} item</td>
                                <td style="text-align: center;">
                                    <a href="/api/purchase-requests/{{ $pr->id }}" target="_blank" class="btn btn-secondary btn-sm">
                                        Lihat JSON Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 30px;">
                                    Belum ada data Purchase Request.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab 3: Master Items -->
            <div id="tab-items" class="tab-content">
                <div class="card-header" style="padding: 0 0 16px 0;">
                    <div>
                        <div class="card-title">Master Barang & Inventaris</div>
                        <div class="card-subtitle">Kelola master data barang (CRUD)</div>
                    </div>
                    <button class="btn btn-primary btn-sm" onclick="openModal('modal-item')">+ Tambah Barang</button>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Deskripsi</th>
                                <th>Satuan</th>
                                <th style="text-align: right;">Harga Standar</th>
                            </tr>
                        </thead>
                        <tbody id="items-table-body">
                            @foreach($items as $item)
                            <tr>
                                <td><code>{{ $item->code }}</code></td>
                                <td><strong>{{ $item->name }}</strong></td>
                                <td style="color: var(--text-muted); font-size: 12.5px;">{{ $item->description }}</td>
                                <td>{{ $item->unit }}</td>
                                <td style="text-align: right; font-weight: 600;">
                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab 4: Master Vendors -->
            <div id="tab-vendors" class="tab-content">
                <div class="card-header" style="padding: 0 0 16px 0;">
                    <div>
                        <div class="card-title">Master Vendor & Rekanan</div>
                        <div class="card-subtitle">Daftar supplier / vendor penyedia barang</div>
                    </div>
                    <button class="btn btn-primary btn-sm" onclick="openModal('modal-vendor')">+ Tambah Vendor</button>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Kode Vendor</th>
                                <th>Nama Vendor</th>
                                <th>Alamat</th>
                                <th>Telepon</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody id="vendors-table-body">
                            @foreach($vendors as $vendor)
                            <tr>
                                <td><code>{{ $vendor->code }}</code></td>
                                <td><strong>{{ $vendor->name }}</strong></td>
                                <td style="font-size: 12.5px;">{{ $vendor->address }}</td>
                                <td>{{ $vendor->phone }}</td>
                                <td><a href="mailto:{{ $vendor->email }}" style="color: var(--primary);">{{ $vendor->email }}</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab 5: API Simulator -->
            <div id="tab-api-tester" class="tab-content">
                <div class="card-header" style="padding: 0 0 16px 0;">
                    <div>
                        <div class="card-title">REST API Live Simulator</div>
                        <div class="card-subtitle">Uji respon endpoint REST API secara interaktif langsung dari browser</div>
                    </div>
                </div>

                <div class="api-console">
                    <div class="api-controls">
                        <select id="api-endpoint-selector" class="api-select">
                            <option value="GET /api/purchase-orders/lookup?q=PO-2026">GET /api/purchase-orders/lookup?q=PO-2026</option>
                            <option value="GET /api/purchase-orders">GET /api/purchase-orders (List PO with Pagination)</option>
                            <option value="GET /api/purchase-orders/1">GET /api/purchase-orders/1 (Detail PO)</option>
                            <option value="GET /api/purchase-requests/lookup?q=PR-2026">GET /api/purchase-requests/lookup?q=PR-2026</option>
                            <option value="GET /api/purchase-requests/1">GET /api/purchase-requests/1 (Detail PR)</option>
                            <option value="GET /api/items">GET /api/items (List Barang)</option>
                            <option value="GET /api/vendors">GET /api/vendors (List Vendor)</option>
                            <option value="POST /api/auth/login">POST /api/auth/login (Auth Token for Fastify)</option>
                        </select>
                        <button class="btn btn-primary" onclick="executeApiTest()">Kirim Request</button>
                    </div>

                    <div style="margin-bottom: 8px; font-size: 12px; color: #94a3b8;">Status Response: <span id="api-status-code" style="color: #38bdf8; font-weight: bold;">Siap</span></div>
                    <pre id="api-result-box" class="api-preview">// Klik tombol "Kirim Request" di atas untuk melihat respon JSON real-time dari REST API External Inventory System.</pre>
                </div>
            </div>

        </div>

    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toast-container"></div>

    <!-- Modal Tambah Purchase Request -->
    <div id="modal-pr" class="modal-backdrop">
        <div class="modal">
            <div class="modal-header">
                <h3>Tambah Purchase Request (PR) Baru</h3>
                <button class="modal-close" onclick="closeModal('modal-pr')">&times;</button>
            </div>
            <form id="form-add-pr" onsubmit="handleCreatePR(event)">
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" style="display: flex; justify-content: space-between; align-items: center;">
                                <span>Nomor PR *</span>
                                <span style="font-size: 11px; background: #e0e7ff; color: #4338ca; padding: 2px 8px; border-radius: 6px; font-weight: 600;">Otomatis</span>
                            </label>
                            <input type="text" name="pr_number" id="pr-number-input" class="form-control" value="{{ $nextPrNumber ?? 'PR-' . date('Y') . '-0001' }}" readonly style="background-color: #f8fafc; cursor: not-allowed; font-family: 'JetBrains Mono', monospace; font-weight: 700; color: #334155;" required>
                            <small style="font-size: 11px; color: var(--text-muted); margin-top: 4px; display: block;">Nomor PR di-generate secara otomatis oleh sistem</small>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tanggal Request *</label>
                            <input type="date" name="request_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Nama Requester *</label>
                            <input type="text" name="requester_name" class="form-control" placeholder="Contoh: Budi Santoso" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Departemen *</label>
                            <input type="text" name="department" class="form-control" placeholder="Contoh: IT Department" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Company *</label>
                            <select name="company_id" class="form-control" required>
                                <option value="">-- Pilih Company --</option>
                                @foreach($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->code }} - {{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control">
                                <option value="draft">Draft</option>
                                <option value="submitted">Submitted</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Keterangan atau keperluan pengadaan..."></textarea>
                    </div>

                    <div style="margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center;">
                        <label class="form-label" style="margin-bottom: 0;">Item Barang *</label>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="addPRItemRow()">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Tambah Baris
                        </button>
                    </div>
                    <div style="overflow-x: auto;">
                        <table class="items-table">
                            <thead>
                                <tr>
                                    <th style="min-width: 200px;">Barang</th>
                                    <th style="width: 80px;">Qty</th>
                                    <th style="width: 130px;">Est. Harga (Rp)</th>
                                    <th style="width: 130px;">Subtotal</th>
                                    <th style="width: 40px;"></th>
                                </tr>
                            </thead>
                            <tbody id="pr-items-body">
                                <!-- rows injected by JS -->
                            </tbody>
                        </table>
                    </div>

                    <div class="summary-box" id="pr-summary-box">
                        <div class="summary-item">
                            <span class="summary-label">Total Estimasi</span>
                            <span class="summary-value" id="pr-total-display">Rp 0</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('modal-pr')">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btn-submit-pr">Simpan Purchase Request</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Tambah Purchase Order -->
    <div id="modal-po" class="modal-backdrop">
        <div class="modal">
            <div class="modal-header">
                <h3>Tambah Purchase Order (PO) Baru</h3>
                <button class="modal-close" onclick="closeModal('modal-po')">&times;</button>
            </div>
            <form id="form-add-po" onsubmit="handleCreatePO(event)">
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" style="display: flex; justify-content: space-between; align-items: center;">
                                <span>Nomor PO *</span>
                                <span style="font-size: 11px; background: #e0e7ff; color: #4338ca; padding: 2px 8px; border-radius: 6px; font-weight: 600;">Otomatis</span>
                            </label>
                            <input type="text" name="po_number" id="po-number-input" class="form-control" value="{{ $nextPoNumber ?? 'PO-' . date('Y') . '-0001' }}" readonly style="background-color: #f8fafc; cursor: not-allowed; font-family: 'JetBrains Mono', monospace; font-weight: 700; color: #334155;" required>
                            <small style="font-size: 11px; color: var(--text-muted); margin-top: 4px; display: block;">Nomor PO di-generate secara otomatis oleh sistem</small>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tanggal Order *</label>
                            <input type="date" name="order_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Company *</label>
                            <select name="company_id" class="form-control" required>
                                <option value="">-- Pilih Company --</option>
                                @foreach($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->code }} - {{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Vendor *</label>
                            <select name="vendor_id" class="form-control" required>
                                <option value="">-- Pilih Vendor --</option>
                                @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}">{{ $vendor->code }} - {{ $vendor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Ref. Purchase Request (Opsional)</label>
                            <select name="purchase_request_id" class="form-control" id="po-pr-select">
                                <option value="">-- Tidak Ada / Bebas --</option>
                                @foreach($recentPRs as $pr)
                                <option value="{{ $pr->id }}">{{ $pr->pr_number }} — {{ $pr->requester_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control">
                                <option value="draft">Draft</option>
                                <option value="issued">Issued</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Pajak / Tax (Rp)</label>
                            <input type="number" name="tax" id="po-tax-input" class="form-control" placeholder="0" min="0" value="0" oninput="updatePOSummary()">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Deskripsi</label>
                            <input type="text" name="description" class="form-control" placeholder="Keterangan tambahan...">
                        </div>
                    </div>

                    <div style="margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center;">
                        <label class="form-label" style="margin-bottom: 0;">Item Barang *</label>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="addPOItemRow()">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Tambah Baris
                        </button>
                    </div>
                    <div style="overflow-x: auto;">
                        <table class="items-table">
                            <thead>
                                <tr>
                                    <th style="min-width: 200px;">Barang</th>
                                    <th style="width: 80px;">Qty</th>
                                    <th style="width: 130px;">Harga Satuan (Rp)</th>
                                    <th style="width: 130px;">Subtotal</th>
                                    <th style="width: 40px;"></th>
                                </tr>
                            </thead>
                            <tbody id="po-items-body">
                                <!-- rows injected by JS -->
                            </tbody>
                        </table>
                    </div>

                    <div class="summary-box" id="po-summary-box">
                        <div class="summary-item">
                            <span class="summary-label">Subtotal</span>
                            <span class="summary-value" id="po-subtotal-display">Rp 0</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Tax</span>
                            <span class="summary-value" style="color: var(--warning);" id="po-tax-display">Rp 0</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Total</span>
                            <span class="summary-value" style="color: var(--success);" id="po-total-display">Rp 0</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('modal-po')">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btn-submit-po">Simpan Purchase Order</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Tambah Barang -->
    <div id="modal-item" class="modal-backdrop">
        <div class="modal">
            <div class="modal-header">
                <h3>Tambah Master Barang Baru</h3>
                <button class="modal-close" onclick="closeModal('modal-item')">&times;</button>
            </div>
            <form id="form-add-item" onsubmit="handleCreateItem(event)">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Kode Barang *</label>
                        <input type="text" name="code" class="form-control" placeholder="Contoh: BRG-011" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Barang *</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Printer EPSON L3250" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Deskripsi peruntukan..."></textarea>
                    </div>
                    <div style="display: flex; gap: 12px;">
                        <div class="form-group" style="flex: 1;">
                            <label class="form-label">Satuan *</label>
                            <input type="text" name="unit" class="form-control" placeholder="unit, pcs, rim, box" required>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label class="form-label">Harga Standar (Rp) *</label>
                            <input type="number" name="price" class="form-control" placeholder="2850000" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('modal-item')">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Barang</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Tambah Vendor -->
    <div id="modal-vendor" class="modal-backdrop">
        <div class="modal">
            <div class="modal-header">
                <h3>Tambah Master Vendor Baru</h3>
                <button class="modal-close" onclick="closeModal('modal-vendor')">&times;</button>
            </div>
            <form id="form-add-vendor" onsubmit="handleCreateVendor(event)">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Kode Vendor *</label>
                        <input type="text" name="code" class="form-control" placeholder="Contoh: VND-006" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Vendor *</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: CV Media Kreatif Mandiri" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Alamat</label>
                        <input type="text" name="address" class="form-control" placeholder="Kota atau alamat lengkap...">
                    </div>
                    <div style="display: flex; gap: 12px;">
                        <div class="form-group" style="flex: 1;">
                            <label class="form-label">Telepon</label>
                            <input type="text" name="phone" class="form-control" placeholder="0271-xxxxxx">
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="vendor@example.com">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('modal-vendor')">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Vendor</button>
                </div>
            </form>
        </div>
    </div>

@php
    $itemsMasterJson = $items->map(function ($i) {
        return [
            'id'    => $i->id,
            'code'  => $i->code,
            'name'  => $i->name,
            'price' => (float) $i->price,
            'unit'  => $i->unit,
        ];
    })->values()->toJson();
@endphp
    <script>
        // =====================
        // Master data from Blade
        // =====================
        const ITEMS_MASTER = {!! $itemsMasterJson !!};

        // =====================
        // Toast Notification
        // =====================
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = 'toast ' + type;
            toast.innerHTML = `<span class="toast-icon">${type === 'success' ? '✅' : '❌'}</span><span>${message}</span>`;
            container.appendChild(toast);
            setTimeout(() => { toast.style.transition = 'opacity 0.4s'; toast.style.opacity = '0'; setTimeout(() => toast.remove(), 400); }, 3500);
        }

        // =====================
        // Tab switching
        // =====================
        function switchTab(tabId, el) {
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.getElementById(tabId).classList.add('active');
            el.classList.add('active');
        }

        function openModal(id) {
            document.getElementById(id).classList.add('open');
            if (id === 'modal-pr') {
                const prInput = document.getElementById('pr-number-input');
                if (prInput && !prInput.value) {
                    prInput.value = '{{ $nextPrNumber ?? "" }}';
                }
                const prDate = document.querySelector('#form-add-pr [name="request_date"]');
                if (prDate && !prDate.value) {
                    prDate.value = new Date().toISOString().split('T')[0];
                }
                if (document.querySelectorAll('#pr-items-body tr').length === 0) {
                    addPRItemRow();
                }
            }
            if (id === 'modal-po') {
                const poInput = document.getElementById('po-number-input');
                if (poInput && !poInput.value) {
                    poInput.value = '{{ $nextPoNumber ?? "" }}';
                }
                const poDate = document.querySelector('#form-add-po [name="order_date"]');
                if (poDate && !poDate.value) {
                    poDate.value = new Date().toISOString().split('T')[0];
                }
                if (document.querySelectorAll('#po-items-body tr').length === 0) {
                    addPOItemRow();
                }
            }
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('open');
        }

        // =====================
        // PR Item Rows
        // =====================
        function buildItemOptions() {
            return ITEMS_MASTER.map(i =>
                `<option value="${i.id}" data-price="${i.price}">${i.code} — ${i.name} (${i.unit})</option>`
            ).join('');
        }

        function addPRItemRow() {
            const tbody = document.getElementById('pr-items-body');
            const idx = Date.now();
            const row = document.createElement('tr');
            row.id = 'pr-row-' + idx;
            row.innerHTML = `
                <td>
                    <select onchange="prItemChanged(this, '${idx}')" required>
                        <option value="">-- Pilih Barang --</option>
                        ${buildItemOptions()}
                    </select>
                </td>
                <td>
                    <input type="number" id="pr-qty-${idx}" min="1" value="1" oninput="updatePRRowSubtotal('${idx}')" required>
                </td>
                <td>
                    <input type="number" id="pr-price-${idx}" min="0" placeholder="0" oninput="updatePRRowSubtotal('${idx}')" required>
                </td>
                <td>
                    <span id="pr-sub-${idx}" style="font-weight: 700; font-size: 12px; color: var(--primary); white-space:nowrap;">Rp 0</span>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="document.getElementById('pr-row-${idx}').remove(); updatePRTotal();" title="Hapus">×</button>
                </td>
            `;
            tbody.appendChild(row);
        }

        function prItemChanged(sel, idx) {
            const opt = sel.options[sel.selectedIndex];
            const price = parseFloat(opt.dataset.price || 0);
            document.getElementById('pr-price-' + idx).value = price;
            updatePRRowSubtotal(idx);
        }

        function updatePRRowSubtotal(idx) {
            const qty   = parseFloat(document.getElementById('pr-qty-'   + idx)?.value || 0);
            const price = parseFloat(document.getElementById('pr-price-' + idx)?.value || 0);
            const sub   = qty * price;
            const el = document.getElementById('pr-sub-' + idx);
            if (el) el.innerText = 'Rp ' + formatNumber(sub);
            updatePRTotal();
        }

        function updatePRTotal() {
            let total = 0;
            document.querySelectorAll('[id^="pr-sub-"]').forEach(el => {
                total += parseRp(el.innerText);
            });
            document.getElementById('pr-total-display').innerText = 'Rp ' + formatNumber(total);
        }

        // =====================
        // PO Item Rows
        // =====================
        function addPOItemRow() {
            const tbody = document.getElementById('po-items-body');
            const idx = Date.now();
            const row = document.createElement('tr');
            row.id = 'po-row-' + idx;
            row.innerHTML = `
                <td>
                    <select onchange="poItemChanged(this, '${idx}')" required>
                        <option value="">-- Pilih Barang --</option>
                        ${buildItemOptions()}
                    </select>
                </td>
                <td>
                    <input type="number" id="po-qty-${idx}" min="1" value="1" oninput="updatePORowSubtotal('${idx}')" required>
                </td>
                <td>
                    <input type="number" id="po-uprice-${idx}" min="0" placeholder="0" oninput="updatePORowSubtotal('${idx}')" required>
                </td>
                <td>
                    <span id="po-sub-${idx}" style="font-weight: 700; font-size: 12px; color: var(--primary); white-space:nowrap;">Rp 0</span>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="document.getElementById('po-row-${idx}').remove(); updatePOSummary();" title="Hapus">×</button>
                </td>
            `;
            tbody.appendChild(row);
        }

        function poItemChanged(sel, idx) {
            const opt = sel.options[sel.selectedIndex];
            const price = parseFloat(opt.dataset.price || 0);
            document.getElementById('po-uprice-' + idx).value = price;
            updatePORowSubtotal(idx);
        }

        function updatePORowSubtotal(idx) {
            const qty   = parseFloat(document.getElementById('po-qty-'   + idx)?.value || 0);
            const price = parseFloat(document.getElementById('po-uprice-' + idx)?.value || 0);
            const sub   = qty * price;
            const el = document.getElementById('po-sub-' + idx);
            if (el) el.innerText = 'Rp ' + formatNumber(sub);
            updatePOSummary();
        }

        function updatePOSummary() {
            let subtotal = 0;
            document.querySelectorAll('[id^="po-sub-"]').forEach(el => {
                subtotal += parseRp(el.innerText);
            });
            const tax = parseFloat(document.getElementById('po-tax-input')?.value || 0);
            const total = subtotal + tax;
            document.getElementById('po-subtotal-display').innerText = 'Rp ' + formatNumber(subtotal);
            document.getElementById('po-tax-display').innerText = 'Rp ' + formatNumber(tax);
            document.getElementById('po-total-display').innerText = 'Rp ' + formatNumber(total);
        }

        // =====================
        // Utility
        // =====================
        function formatNumber(n) {
            return Math.round(n).toLocaleString('id-ID');
        }

        function parseRp(str) {
            return parseFloat((str || '0').replace('Rp ', '').replace(/\./g, '').replace(',', '.')) || 0;
        }

        // =====================
        // Create PR Handler
        // =====================
        async function handleCreatePR(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            // Build items array from DOM
            const itemRows = document.querySelectorAll('#pr-items-body tr');
            if (itemRows.length === 0) { showToast('Tambahkan minimal 1 item barang!', 'error'); return; }

            data.items = [];
            let valid = true;
            itemRows.forEach(row => {
                const sel     = row.querySelector('select');
                // Extract idx from row id (format: pr-row-{idx})
                const rowIdx  = row.id.replace('pr-row-', '');
                const qtyEl   = document.getElementById('pr-qty-' + rowIdx);
                const priceEl = document.getElementById('pr-price-' + rowIdx);
                if (!sel?.value) { valid = false; return; }
                data.items.push({
                    item_id: parseInt(sel.value),
                    quantity: parseInt(qtyEl?.value || 1),
                    estimated_price: parseFloat(priceEl?.value || 0)
                });
            });

            if (!valid) { showToast('Pilih barang untuk setiap baris item!', 'error'); return; }

            const btn = document.getElementById('btn-submit-pr');
            btn.disabled = true; btn.innerText = 'Menyimpan...';

            try {
                const res = await fetch('/api/purchase-requests', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(data)
                });
                const result = await res.json();
                if (res.ok) {
                    showToast('Purchase Request ' + result.data?.pr_number + ' berhasil dibuat!');
                    closeModal('modal-pr');
                    document.getElementById('form-add-pr').reset();
                    document.getElementById('pr-items-body').innerHTML = '';
                    setTimeout(() => window.location.reload(), 1200);
                } else {
                    const errMsg = result.message + (result.errors ? '\n' + Object.values(result.errors).flat().join('\n') : '');
                    showToast(errMsg, 'error');
                }
            } catch (err) {
                showToast('Terjadi kesalahan: ' + err.message, 'error');
            } finally {
                btn.disabled = false; btn.innerText = 'Simpan Purchase Request';
            }
        }

        // =====================
        // Create PO Handler
        // =====================
        async function handleCreatePO(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            // Bersihkan purchase_request_id kosong
            if (!data.purchase_request_id) delete data.purchase_request_id;

            // Build items
            const itemRows = document.querySelectorAll('#po-items-body tr');
            if (itemRows.length === 0) { showToast('Tambahkan minimal 1 item barang!', 'error'); return; }

            data.items = [];
            let valid = true;
            itemRows.forEach(row => {
                const sel     = row.querySelector('select');
                // Extract idx from row id (format: po-row-{idx})
                const rowIdx  = row.id.replace('po-row-', '');
                const qtyEl   = document.getElementById('po-qty-' + rowIdx);
                const upriceEl= document.getElementById('po-uprice-' + rowIdx);
                if (!sel?.value) { valid = false; return; }
                data.items.push({
                    item_id: parseInt(sel.value),
                    quantity: parseInt(qtyEl?.value || 1),
                    unit_price: parseFloat(upriceEl?.value || 0)
                });
            });

            if (!valid) { showToast('Pilih barang untuk setiap baris item!', 'error'); return; }

            data.tax = parseFloat(data.tax || 0);

            const btn = document.getElementById('btn-submit-po');
            btn.disabled = true; btn.innerText = 'Menyimpan...';

            try {
                const res = await fetch('/api/purchase-orders', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(data)
                });
                const result = await res.json();
                if (res.ok) {
                    showToast('Purchase Order ' + result.data?.po_number + ' berhasil dibuat!');
                    closeModal('modal-po');
                    document.getElementById('form-add-po').reset();
                    document.getElementById('po-items-body').innerHTML = '';
                    updatePOSummary();
                    setTimeout(() => window.location.reload(), 1200);
                } else {
                    const errMsg = result.message + (result.errors ? '\n' + Object.values(result.errors).flat().join('\n') : '');
                    showToast(errMsg, 'error');
                }
            } catch (err) {
                showToast('Terjadi kesalahan: ' + err.message, 'error');
            } finally {
                btn.disabled = false; btn.innerText = 'Simpan Purchase Order';
            }
        }

        async function executeApiTest() {
            const selector = document.getElementById('api-endpoint-selector');
            const resultBox = document.getElementById('api-result-box');
            const statusBox = document.getElementById('api-status-code');

            const val = selector.value;
            const [method, url] = val.split(' ');

            resultBox.innerText = 'Mengirim request ke ' + url + '...';
            statusBox.innerText = 'Loading...';

            try {
                let options = { method: method };
                if (method === 'POST' && url === '/api/auth/login') {
                    options.headers = { 'Content-Type': 'application/json' };
                    options.body = JSON.stringify({
                        username: 'integration-service',
                        password: 'password'
                    });
                }

                const res = await fetch(url, options);
                statusBox.innerText = res.status + ' ' + res.statusText;
                const json = await res.json();
                resultBox.innerText = JSON.stringify(json, null, 2);
            } catch (err) {
                statusBox.innerText = 'Error';
                resultBox.innerText = err.toString();
            }
        }

        async function handleCreateItem(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            try {
                const res = await fetch('/api/items', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await res.json();
                if (res.ok) {
                    alert('Barang berhasil ditambahkan!');
                    closeModal('modal-item');
                    window.location.reload();
                } else {
                    alert('Gagal: ' + (result.message || JSON.stringify(result.errors)));
                }
            } catch (err) {
                alert('Terjadi kesalahan: ' + err.message);
            }
        }

        async function handleCreateVendor(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            try {
                const res = await fetch('/api/vendors', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await res.json();
                if (res.ok) {
                    alert('Vendor berhasil ditambahkan!');
                    closeModal('modal-vendor');
                    window.location.reload();
                } else {
                    alert('Gagal: ' + (result.message || JSON.stringify(result.errors)));
                }
            } catch (err) {
                alert('Terjadi kesalahan: ' + err.message);
            }
        }
    </script>
</body>
</html>
