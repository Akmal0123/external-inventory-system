<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DocsController extends Controller
{
    /**
     * Render Scalar API Documentation & Testing Console
     */
    public function scalar(): Response
    {
        $html = <<<'HTML'
<!doctype html>
<html lang="id">
  <head>
    <title>External Inventory System (EIS) — API Documentation & Testing</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%2310b981'><path d='M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'/></svg>">
    <style>
      body { margin: 0; padding: 0; font-family: system-ui, -apple-system, sans-serif; }
      .quick-bar {
        background: #064e3b;
        color: #ecfdf5;
        padding: 8px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13px;
        border-bottom: 1px solid #047857;
      }
      .quick-bar a {
        color: #a7f3d0;
        text-decoration: none;
        font-weight: 500;
        margin-left: 12px;
      }
      .quick-bar a:hover {
        text-decoration: underline;
      }
      .badge {
        background: #047857;
        color: #ffffff;
        padding: 2px 8px;
        border-radius: 9999px;
        font-size: 11px;
        font-weight: 600;
      }
    </style>
  </head>
  <body>
    <div class="quick-bar">
      <div>
        <span class="badge">EIS PORT 9000</span>
        <strong style="margin-left: 8px;">External Inventory System API</strong>
      </div>
      <div>
        <a href="/api/dev/tokens" target="_blank">🔑 Get Test Token (JWT B)</a>
        <a href="/swagger">Swagger UI View</a>
        <a href="http://localhost:5000/docs/hub" target="_blank">🌐 Unified API Hub</a>
        <a href="http://localhost:5000/docs" target="_blank">Fastify Docs (:5000)</a>
        <a href="http://localhost:8000/docs" target="_blank">AMS Docs (:8000)</a>
      </div>
    </div>
    <script
      id="api-reference"
      data-url="/docs/openapi.json"
      data-configuration='{
        "theme": "emerald",
        "darkMode": true,
        "searchHotKey": "k",
        "metaData": {
          "title": "EIS API Reference"
        }
      }'>
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@scalar/api-reference"></script>
  </body>
</html>
HTML;

        return response($html, 200, ['Content-Type' => 'text/html; charset=utf-8']);
    }

    /**
     * Render Swagger UI alternative
     */
    public function swagger(): Response
    {
        $html = <<<'HTML'
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>External Inventory System — Swagger UI</title>
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/swagger-ui-dist@5.18.2/swagger-ui.css" />
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%2310b981'><path d='M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'/></svg>">
  <style>
    body { margin: 0; background: #fafafa; font-family: sans-serif; }
    .top-nav {
      background: #064e3b;
      color: #fff;
      padding: 10px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 14px;
    }
    .top-nav a { color: #a7f3d0; text-decoration: none; margin-left: 15px; font-weight: 500; }
  </style>
</head>
<body>
  <div class="top-nav">
    <div><strong>External Inventory System (EIS)</strong> &mdash; Swagger UI</div>
    <div>
      <a href="/docs">Scalar Docs</a>
      <a href="/api/dev/tokens" target="_blank">🔑 Get Test Token</a>
      <a href="http://localhost:5000/docs/hub" target="_blank">🌐 Unified API Hub</a>
    </div>
  </div>
  <div id="swagger-ui"></div>
  <script src="https://cdn.jsdelivr.net/npm/swagger-ui-dist@5.18.2/swagger-ui-bundle.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swagger-ui-dist@5.18.2/swagger-ui-standalone-preset.js"></script>
  <script>
    window.onload = function() {
      SwaggerUIBundle({
        url: "/docs/openapi.json",
        dom_id: '#swagger-ui',
        deepLinking: true,
        presets: [SwaggerUIBundle.presets.apis, SwaggerUIStandalonePreset],
        layout: "BaseLayout"
      });
    };
  </script>
</body>
</html>
HTML;

        return response($html, 200, ['Content-Type' => 'text/html; charset=utf-8']);
    }

    /**
     * Return OpenAPI 3.1 specification for EIS
     */
    public function openapi(): JsonResponse
    {
        $spec = [
            'openapi' => '3.1.0',
            'info' => [
                'title' => 'External Inventory System (EIS) API',
                'version' => '1.0.0',
                'description' => "
### External Inventory & Procurement System API

Sistem manajemen inventaris eksternal yang menyediakan data Purchase Order (PO), Purchase Request (PR), Vendor, Perusahaan, dan Master Barang.

#### Keamanan & Otentikasi:
- **API v1 Dedicated Endpoints (/api/v1/...)**: Dilindungi dengan **JWT B** (Service-to-Service) yang diterbitkan oleh Fastify Integration Service. Memerlukan scope `purchase-order:read` atau `purchase-request:read`.
- **Legacy CRUD Endpoints (/api/...)**: Dilindungi dengan otentikasi Sanctum Bearer Token atau opsional untuk pengujian.
- Untuk menguji endpoint v1 langsung di Scalar, dapatkan token JWT B dari endpoint **GET /api/dev/tokens**, lalu klik tombol **Authorize** dan masukkan token.
                ",
            ],
            'servers' => [
                ['url' => 'http://localhost:9000', 'description' => 'Local EIS Server (Port 9000)'],
            ],
            'tags' => [
                ['name' => 'v1 Service-to-Service PO', 'description' => 'Endpoint Purchase Order untuk Fastify (JWT B)'],
                ['name' => 'v1 Service-to-Service PR', 'description' => 'Endpoint Purchase Request untuk Fastify (JWT B)'],
                ['name' => 'Auth & User', 'description' => 'Otentikasi pengguna EIS'],
                ['name' => 'Master Data (Legacy)', 'description' => 'CRUD Perusahaan, Vendor, dan Barang'],
                ['name' => 'Dev Helpers', 'description' => 'Alat bantu pengujian'],
            ],
            'paths' => [
                '/api/dev/tokens' => [
                    'get' => [
                        'tags' => ['Dev Helpers'],
                        'summary' => 'Generate Test JWT B Token untuk Pengujian API v1',
                        'description' => 'Menghasilkan token JWT B valid untuk otentikasi langsung ke endpoint API v1 EIS.',
                        'responses' => [
                            '200' => [
                                'description' => 'Token berhasil dibuat',
                            ],
                        ],
                    ],
                ],
                '/api/v1/purchase-orders' => [
                    'get' => [
                        'tags' => ['v1 Service-to-Service PO'],
                        'summary' => 'Daftar Purchase Orders (v1 S2S)',
                        'security' => [['JwtBScheme' => ['purchase-order:read']]],
                        'parameters' => [
                            ['name' => 'search', 'in' => 'query', 'required' => false, 'schema' => ['type' => 'string']],
                            ['name' => 'status', 'in' => 'query', 'required' => false, 'schema' => ['type' => 'string']],
                            ['name' => 'per_page', 'in' => 'query', 'required' => false, 'schema' => ['type' => 'integer', 'default' => 15]],
                        ],
                        'responses' => ['200' => ['description' => 'Daftar PO berhasil diambil']],
                    ],
                ],
                '/api/v1/purchase-orders/lookup' => [
                    'get' => [
                        'tags' => ['v1 Service-to-Service PO'],
                        'summary' => 'Lookup Cepat PO untuk Autocomplete (v1 S2S)',
                        'security' => [['JwtBScheme' => ['purchase-order:read']]],
                        'parameters' => [
                            ['name' => 'q', 'in' => 'query', 'required' => false, 'schema' => ['type' => 'string']],
                        ],
                        'responses' => ['200' => ['description' => 'Hasil lookup PO']],
                    ],
                ],
                '/api/v1/purchase-orders/{id}' => [
                    'get' => [
                        'tags' => ['v1 Service-to-Service PO'],
                        'summary' => 'Detail Purchase Order by ID / Number (v1 S2S)',
                        'security' => [['JwtBScheme' => ['purchase-order:read']]],
                        'parameters' => [
                            ['name' => 'id', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'string', 'example' => 'PO-2026-0001']],
                        ],
                        'responses' => [
                            '200' => ['description' => 'Detail PO ditemukan'],
                            '404' => ['description' => 'PO tidak ditemukan'],
                        ],
                    ],
                ],
                '/api/v1/purchase-orders/{id}/pdf' => [
                    'get' => [
                        'tags' => ['v1 Service-to-Service PO'],
                        'summary' => 'Download / Stream Berkas Asli PDF PO (v1 S2S)',
                        'security' => [['JwtBScheme' => ['purchase-order:read']]],
                        'parameters' => [
                            ['name' => 'id', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'string', 'example' => '1']],
                        ],
                        'responses' => [
                            '200' => ['description' => 'Berkas binary PDF'],
                        ],
                    ],
                ],
                '/api/v1/purchase-requests' => [
                    'get' => [
                        'tags' => ['v1 Service-to-Service PR'],
                        'summary' => 'Daftar Purchase Requests (v1 S2S)',
                        'security' => [['JwtBScheme' => ['purchase-request:read']]],
                        'parameters' => [
                            ['name' => 'search', 'in' => 'query', 'required' => false, 'schema' => ['type' => 'string']],
                        ],
                        'responses' => ['200' => ['description' => 'Daftar PR berhasil diambil']],
                    ],
                ],
                '/api/v1/purchase-requests/lookup' => [
                    'get' => [
                        'tags' => ['v1 Service-to-Service PR'],
                        'summary' => 'Lookup Cepat PR untuk Autocomplete (v1 S2S)',
                        'security' => [['JwtBScheme' => ['purchase-request:read']]],
                        'parameters' => [
                            ['name' => 'q', 'in' => 'query', 'required' => false, 'schema' => ['type' => 'string']],
                        ],
                        'responses' => ['200' => ['description' => 'Hasil lookup PR']],
                    ],
                ],
                '/api/v1/purchase-requests/{id}' => [
                    'get' => [
                        'tags' => ['v1 Service-to-Service PR'],
                        'summary' => 'Detail Purchase Request by ID / Number (v1 S2S)',
                        'security' => [['JwtBScheme' => ['purchase-request:read']]],
                        'parameters' => [
                            ['name' => 'id', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'string', 'example' => 'PR-2026-0001']],
                        ],
                        'responses' => ['200' => ['description' => 'Detail PR ditemukan']],
                    ],
                ],
                '/api/auth/login' => [
                    'post' => [
                        'tags' => ['Auth & User'],
                        'summary' => 'Login User EIS (Mendapatkan Sanctum Token)',
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'required' => ['email', 'password'],
                                        'properties' => [
                                            'email' => ['type' => 'string', 'example' => 'admin@external-system.local'],
                                            'password' => ['type' => 'string', 'example' => 'password'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'responses' => ['200' => ['description' => 'Login berhasil']],
                    ],
                ],
                '/api/auth/me' => [
                    'get' => [
                        'tags' => ['Auth & User'],
                        'summary' => 'Profil User Aktif',
                        'security' => [['SanctumAuth' => []]],
                        'responses' => ['200' => ['description' => 'Data user']],
                    ],
                ],
                '/api/companies' => [
                    'get' => [
                        'tags' => ['Master Data (Legacy)'],
                        'summary' => 'Daftar Perusahaan',
                        'responses' => ['200' => ['description' => 'List companies']],
                    ],
                ],
                '/api/vendors' => [
                    'get' => [
                        'tags' => ['Master Data (Legacy)'],
                        'summary' => 'Daftar Vendor',
                        'responses' => ['200' => ['description' => 'List vendors']],
                    ],
                ],
                '/api/items' => [
                    'get' => [
                        'tags' => ['Master Data (Legacy)'],
                        'summary' => 'Daftar Master Barang',
                        'responses' => ['200' => ['description' => 'List items']],
                    ],
                ],
            ],
            'components' => [
                'securitySchemes' => [
                    'JwtBScheme' => [
                        'type' => 'http',
                        'scheme' => 'bearer',
                        'bearerFormat' => 'JWT',
                        'description' => 'Token JWT B (Service-to-Service Fastify -> EIS). Dapatkan token siap pakai dari /api/dev/tokens.',
                    ],
                    'SanctumAuth' => [
                        'type' => 'http',
                        'scheme' => 'bearer',
                        'description' => 'Sanctum token dari endpoint /api/auth/login.',
                    ],
                ],
            ],
        ];

        return response()->json($spec, 200, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    /**
     * Generate test tokens for EIS (JWT B & test credentials)
     */
    public function tokens(): JsonResponse
    {
        $secret = env('EIS_JWT_SECRET', 'fastify-eis-secret-key-super-secure-token-b');
        $issuer = env('EIS_JWT_ISSUER', 'integration-service');
        $audience = env('EIS_JWT_AUDIENCE', 'eis');

        $header = ['alg' => 'HS256', 'typ' => 'JWT'];
        $now = time();
        $payload = [
            'iss' => $issuer,
            'aud' => $audience,
            'sub' => 'integration-service',
            'scope' => ['purchase-order:read', 'purchase-request:read'],
            'iat' => $now,
            'exp' => $now + 86400, // 24 hours for dev test
        ];

        $b64Header = $this->base64UrlEncode(json_encode($header));
        $b64Payload = $this->base64UrlEncode(json_encode($payload));
        $signature = hash_hmac('sha256', "{$b64Header}.{$b64Payload}", $secret, true);
        $b64Signature = $this->base64UrlEncode($signature);
        $jwtB = "{$b64Header}.{$b64Payload}.{$b64Signature}";

        return response()->json([
            'success' => true,
            'message' => 'Token pengujian EIS berhasil dibuat.',
            'generated_at' => date('c'),
            'jwt_b_token' => [
                'description' => 'Gunakan token ini untuk mengakses endpoint /api/v1/... di EIS',
                'token' => $jwtB,
                'scopes' => $payload['scope'],
                'expires_in' => '24 hours (dev testing)',
                'curl_example' => "curl -H \"Authorization: Bearer {$jwtB}\" http://localhost:9000/api/v1/purchase-orders/PO-2026-0001",
            ],
            'sample_user_credentials' => [
                'email' => 'admin@external-system.local',
                'password' => 'password',
                'login_url' => 'POST http://localhost:9000/api/auth/login',
            ],
        ]);
    }

    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
