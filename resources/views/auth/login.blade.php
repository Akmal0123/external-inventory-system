<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — External Inventory System</title>
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
            --bg-card: rgba(17, 24, 39, 0.85);
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
            padding: 24px;
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
            width: 480px;
            height: 480px;
            background: #4f46e5;
            top: -120px;
            left: -120px;
        }
        .glow-2 {
            width: 420px;
            height: 420px;
            background: #06b6d4;
            bottom: -100px;
            right: -100px;
        }

        .login-card {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 440px;
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.05);
        }

        .login-header {
            text-align: center;
            margin-bottom: 32px;
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

        .login-header h1 {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 6px;
        }

        .login-header p {
            font-size: 13px;
            color: var(--text-muted);
        }

        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-danger {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }
        .alert-success {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #6ee7b7;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #cbd5e1;
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

        .form-input:focus {
            border-color: #818cf8;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25);
            background: rgba(15, 23, 42, 0.9);
        }

        .form-input::placeholder {
            color: #64748b;
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
            margin-top: 6px;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #4338ca, #3730a3);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.5);
        }

        .quick-hint {
            margin-top: 24px;
            padding: 16px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px dashed rgba(255, 255, 255, 0.12);
            border-radius: 12px;
            font-size: 12px;
        }

        .quick-hint-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            color: #94a3b8;
            font-weight: 600;
        }

        .btn-quick-fill {
            background: rgba(79, 70, 229, 0.2);
            border: 1px solid rgba(79, 70, 229, 0.4);
            color: #a5b4fc;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-quick-fill:hover {
            background: rgba(79, 70, 229, 0.35);
            color: #ffffff;
        }

        .credential-item {
            display: flex;
            justify-content: space-between;
            font-family: 'JetBrains Mono', monospace;
            color: #cbd5e1;
            font-size: 11px;
            padding: 2px 0;
        }
        .credential-item span:first-child {
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="ambient-glow glow-1"></div>
    <div class="ambient-glow glow-2"></div>

    <div class="login-card">
        <div class="login-header">
            <div class="brand-badge">EIS</div>
            <h1>External Inventory System</h1>
            <p>Silakan masuk untuk mengelola inventory, PO, & PR</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <div>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        @if (session('status'))
            <div class="alert alert-success">
                <div>{{ session('status') }}</div>
            </div>
        @endif

        <form action="{{ url('/login') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label" for="login">Email atau Username</label>
                <input 
                    type="text" 
                    id="login" 
                    name="login" 
                    class="form-input" 
                    placeholder="test@mail.com" 
                    value="{{ old('login', 'test@mail.com') }}" 
                    required 
                    autofocus
                >
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-input" 
                    placeholder="••••••••" 
                    value="password123" 
                    required
                >
            </div>

            <button type="submit" class="btn-submit">
                Masuk ke Dashboard EIS
            </button>
        </form>

        <div class="quick-hint">
            <div class="quick-hint-header">
                <span>Kredensial Default</span>
                <button type="button" class="btn-quick-fill" onclick="fillTestUser()">Isi Otomatis</button>
            </div>
            <div class="credential-item">
                <span>Username:</span>
                <span>test@mail.com</span>
            </div>
            <div class="credential-item">
                <span>Password:</span>
                <span>password123</span>
            </div>
        </div>
    </div>

    <script>
        function fillTestUser() {
            document.getElementById('login').value = 'test@mail.com';
            document.getElementById('password').value = 'password123';
        }
    </script>
</body>
</html>
