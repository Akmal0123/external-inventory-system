<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru — External Inventory System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --primary-light: #eef2ff;
            --secondary: #06b6d4;
            --bg-body: #0b0f19;
            --bg-card: rgba(17, 24, 39, 0.88);
            --border-color: rgba(255, 255, 255, 0.1);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --danger: #ef4444;
            --success: #10b981;
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
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Ambient Glow Backgrounds */
        .ambient-glow {
            position: absolute;
            border-radius: 50%;
            filter: blur(140px);
            opacity: 0.25;
            pointer-events: none;
            z-index: 0;
        }
        .glow-1 {
            width: 520px;
            height: 520px;
            background: #4f46e5;
            top: -140px;
            left: -140px;
        }
        .glow-2 {
            width: 460px;
            height: 460px;
            background: #06b6d4;
            bottom: -120px;
            right: -120px;
        }

        .register-card {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 480px;
            background: var(--bg-card);
            backdrop-filter: blur(24px);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6), 0 0 0 1px rgba(255, 255, 255, 0.05);
        }

        .register-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .brand-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #4f46e5, #06b6d4);
            border-radius: 16px;
            color: #ffffff;
            font-size: 26px;
            font-weight: 800;
            margin-bottom: 16px;
            box-shadow: 0 8px 24px rgba(79, 70, 229, 0.35);
        }

        .register-header h1 {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 6px;
        }

        .register-header p {
            font-size: 13px;
            color: var(--text-muted);
        }

        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        .alert-danger {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }
        .alert-danger svg {
            flex-shrink: 0;
            margin-top: 2px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 7px;
            color: #cbd5e1;
        }

        .form-label .badge-opt {
            font-size: 11px;
            font-weight: 500;
            color: #64748b;
        }

        .input-wrapper {
            position: relative;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 10px;
            font-family: inherit;
            font-size: 14px;
            color: #ffffff;
            transition: all 0.2s ease;
            outline: none;
        }

        .form-input.has-icon-right {
            padding-right: 44px;
        }

        .form-input:focus {
            border-color: #818cf8;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25);
            background: rgba(15, 23, 42, 0.9);
        }

        .form-input::placeholder {
            color: #64748b;
        }

        .btn-toggle-pwd {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s;
        }

        .btn-toggle-pwd:hover {
            color: #cbd5e1;
        }

        .password-hints {
            margin-top: 6px;
            font-size: 11.5px;
            color: #94a3b8;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .password-hints.valid {
            color: #34d399;
        }

        .password-hints.invalid {
            color: #f87171;
        }

        .btn-submit {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #4f46e5, #4338ca);
            border: none;
            border-radius: 10px;
            color: #ffffff;
            font-family: inherit;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.4);
            margin-top: 10px;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #4338ca, #3730a3);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.5);
        }

        .register-footer {
            margin-top: 24px;
            text-align: center;
            font-size: 13px;
            color: var(--text-muted);
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            padding-top: 20px;
        }

        .register-footer a {
            color: #818cf8;
            font-weight: 700;
            text-decoration: none;
            transition: color 0.2s;
        }

        .register-footer a:hover {
            color: #a5b4fc;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="ambient-glow glow-1"></div>
    <div class="ambient-glow glow-2"></div>

    <div class="register-card">
        <div class="register-header">
            <div class="brand-badge">EIS</div>
            <h1>Daftar Akun Baru</h1>
            <p>Buat akun untuk mengakses External Inventory System</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <div>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <form action="{{ url('/register') }}" method="POST" id="registerForm">
            @csrf

            <!-- Nama Lengkap -->
            <div class="form-group">
                <label class="form-label" for="name">
                    <span>Nama Lengkap <span style="color: #f87171;">*</span></span>
                </label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    class="form-input" 
                    placeholder="Contoh: Budi Pratama" 
                    value="{{ old('name') }}" 
                    required 
                    autofocus
                >
            </div>

            <!-- Username -->
            <div class="form-group">
                <label class="form-label" for="username">
                    <span>Username</span>
                    <span class="badge-opt">Opsional</span>
                </label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    class="form-input" 
                    placeholder="budipratama" 
                    value="{{ old('username') }}"
                >
            </div>

            <!-- Email -->
            <div class="form-group">
                <label class="form-label" for="email">
                    <span>Alamat Email <span style="color: #f87171;">*</span></span>
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-input" 
                    placeholder="nama@perusahaan.com" 
                    value="{{ old('email') }}" 
                    required
                >
            </div>

            <!-- Password -->
            <div class="form-group">
                <label class="form-label" for="password">
                    <span>Password <span style="color: #f87171;">*</span></span>
                </label>
                <div class="input-wrapper">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input has-icon-right" 
                        placeholder="Minimal 8 karakter" 
                        required
                        oninput="validatePasswords()"
                    >
                    <button type="button" class="btn-toggle-pwd" onclick="togglePassword('password', this)" title="Tampilkan / Sembunyikan Password">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
                <div id="pwd-length-hint" class="password-hints">
                    <span>• Minimal 8 karakter</span>
                </div>
            </div>

            <!-- Konfirmasi Password -->
            <div class="form-group">
                <label class="form-label" for="password_confirmation">
                    <span>Konfirmasi Password <span style="color: #f87171;">*</span></span>
                </label>
                <div class="input-wrapper">
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        class="form-input has-icon-right" 
                        placeholder="Ulangi password di atas" 
                        required
                        oninput="validatePasswords()"
                    >
                    <button type="button" class="btn-toggle-pwd" onclick="togglePassword('password_confirmation', this)" title="Tampilkan / Sembunyikan Password">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
                <div id="pwd-match-hint" class="password-hints" style="display: none;">
                    <span id="pwd-match-text"></span>
                </div>
            </div>

            <button type="submit" class="btn-submit" id="submitBtn">
                Daftar Akun Sekarang
            </button>
        </form>

        <div class="register-footer">
            Sudah memiliki akun? 
            <a href="{{ route('login') }}">Masuk di sini</a>
        </div>
    </div>

    <script>
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            if (!input) return;
            const isPassword = input.getAttribute('type') === 'password';
            input.setAttribute('type', isPassword ? 'text' : 'password');
            btn.innerHTML = isPassword 
                ? '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>'
                : '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
        }

        function validatePasswords() {
            const pwd = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirmation').value;
            const lengthHint = document.getElementById('pwd-length-hint');
            const matchHint = document.getElementById('pwd-match-hint');
            const matchText = document.getElementById('pwd-match-text');

            if (pwd.length > 0) {
                if (pwd.length >= 8) {
                    lengthHint.className = 'password-hints valid';
                    lengthHint.innerHTML = '<span>✓ Panjang password memenuhi syarat (minimal 8 karakter)</span>';
                } else {
                    lengthHint.className = 'password-hints invalid';
                    lengthHint.innerHTML = '<span>✗ Minimal 8 karakter (' + pwd.length + '/8)</span>';
                }
            } else {
                lengthHint.className = 'password-hints';
                lengthHint.innerHTML = '<span>• Minimal 8 karakter</span>';
            }

            if (confirm.length > 0) {
                matchHint.style.display = 'flex';
                if (pwd === confirm) {
                    matchHint.className = 'password-hints valid';
                    matchText.textContent = '✓ Konfirmasi password sesuai';
                } else {
                    matchHint.className = 'password-hints invalid';
                    matchText.textContent = '✗ Konfirmasi password tidak cocok';
                }
            } else {
                matchHint.style.display = 'none';
            }
        }
    </script>
</body>
</html>
