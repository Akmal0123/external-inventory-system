<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Item;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestItem;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchasePrivacyTest extends TestCase
{
    use RefreshDatabase;

    protected User $userA;
    protected User $userB;
    protected User $adminUser;
    protected User $integrationUser;
    protected Company $company;
    protected Vendor $vendor;
    protected Item $item;
    protected PurchaseRequest $prA;
    protected PurchaseRequest $prB;
    protected PurchaseOrder $poA;
    protected PurchaseOrder $poB;

    protected function setUp(): void
    {
        parent::setUp();

        // Create users
        $this->userA = User::factory()->create([
            'name' => 'User A',
            'username' => 'usera',
            'email' => 'usera@example.com',
        ]);

        $this->userB = User::factory()->create([
            'name' => 'User B',
            'username' => 'userb',
            'email' => 'userb@example.com',
        ]);

        $this->adminUser = User::factory()->create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@example.com',
        ]);

        $this->integrationUser = User::factory()->create([
            'name' => 'Integration Service',
            'username' => 'integration-service',
            'email' => 'integration@external-system.local',
        ]);

        // Create master data
        $this->company = Company::create([
            'code' => 'TS',
            'name' => 'PT Tiga Serangkai',
        ]);

        $this->vendor = Vendor::create([
            'code' => 'VND-001',
            'name' => 'PT Vendor Utama',
        ]);

        $this->item = Item::create([
            'code' => 'BRG-001',
            'name' => 'Laptop ThinkPad',
            'unit' => 'unit',
            'price' => 10000000,
        ]);

        // Create PR A for User A
        $this->prA = PurchaseRequest::create([
            'user_id' => $this->userA->id,
            'pr_number' => 'PR-2026-0001',
            'company_id' => $this->company->id,
            'requester_name' => $this->userA->name,
            'department' => 'IT',
            'request_date' => '2026-10-01',
            'status' => 'approved',
        ]);

        // Create PR B for User B
        $this->prB = PurchaseRequest::create([
            'user_id' => $this->userB->id,
            'pr_number' => 'PR-2026-0002',
            'company_id' => $this->company->id,
            'requester_name' => $this->userB->name,
            'department' => 'Finance',
            'request_date' => '2026-10-02',
            'status' => 'approved',
        ]);

        // Create PO A for User A
        $this->poA = PurchaseOrder::create([
            'user_id' => $this->userA->id,
            'po_number' => 'PO-2026-0001',
            'purchase_request_id' => $this->prA->id,
            'company_id' => $this->company->id,
            'vendor_id' => $this->vendor->id,
            'order_date' => '2026-10-01',
            'status' => 'issued',
            'subtotal' => 10000000,
            'tax' => 0,
            'total' => 10000000,
        ]);
        PurchaseOrderItem::create([
            'purchase_order_id' => $this->poA->id,
            'item_id' => $this->item->id,
            'quantity' => 1,
            'unit_price' => 10000000,
            'subtotal' => 10000000,
        ]);

        // Create PO B for User B
        $this->poB = PurchaseOrder::create([
            'user_id' => $this->userB->id,
            'po_number' => 'PO-2026-0002',
            'purchase_request_id' => $this->prB->id,
            'company_id' => $this->company->id,
            'vendor_id' => $this->vendor->id,
            'order_date' => '2026-10-02',
            'status' => 'issued',
            'subtotal' => 20000000,
            'tax' => 0,
            'total' => 20000000,
        ]);
        PurchaseOrderItem::create([
            'purchase_order_id' => $this->poB->id,
            'item_id' => $this->item->id,
            'quantity' => 2,
            'unit_price' => 10000000,
            'subtotal' => 20000000,
        ]);
    }

    private function generateJwtBToken(array $scopes = ['purchase-order:read', 'purchase-request:read']): string
    {
        $header = ['alg' => 'HS256', 'typ' => 'JWT'];
        $payload = [
            'iss' => env('EIS_JWT_ISSUER', 'integration-service'),
            'aud' => env('EIS_JWT_AUDIENCE', 'eis'),
            'sub' => 'microservice-lookup',
            'scope' => $scopes,
            'iat' => time(),
            'exp' => time() + 3600,
        ];

        $b64Header = rtrim(strtr(base64_encode(json_encode($header)), '+/', '-_'), '=');
        $b64Payload = rtrim(strtr(base64_encode(json_encode($payload)), '+/', '-_'), '=');
        $secret = env('EIS_JWT_SECRET', 'fastify-eis-secret-key-super-secure-token-b');
        $signature = rtrim(strtr(base64_encode(hash_hmac('sha256', "{$b64Header}.{$b64Payload}", $secret, true)), '+/', '-_'), '=');

        return "{$b64Header}.{$b64Payload}.{$signature}";
    }

    public function test_unauthenticated_user_cannot_access_po_pr_endpoints(): void
    {
        $this->getJson('/api/purchase-orders')->assertStatus(401);
        $this->getJson('/api/purchase-requests')->assertStatus(401);
        $this->getJson('/api/purchase-orders/lookup')->assertStatus(401);
        $this->getJson('/api/purchase-requests/lookup')->assertStatus(401);
        $this->getJson("/api/purchase-orders/{$this->poA->id}")->assertStatus(401);
        $this->getJson("/api/purchase-requests/{$this->prA->id}")->assertStatus(401);
    }

    public function test_regular_user_can_only_see_their_own_purchase_orders(): void
    {
        // User A only sees PO A
        $responseA = $this->actingAs($this->userA, 'sanctum')->getJson('/api/purchase-orders');
        $responseA->assertStatus(200);
        $responseA->assertJsonCount(1, 'data');
        $responseA->assertJsonFragment(['po_number' => 'PO-2026-0001']);
        $responseA->assertJsonMissing(['po_number' => 'PO-2026-0002']);

        // User B only sees PO B
        $responseB = $this->actingAs($this->userB, 'sanctum')->getJson('/api/purchase-orders');
        $responseB->assertStatus(200);
        $responseB->assertJsonCount(1, 'data');
        $responseB->assertJsonFragment(['po_number' => 'PO-2026-0002']);
        $responseB->assertJsonMissing(['po_number' => 'PO-2026-0001']);
    }

    public function test_regular_user_cannot_view_or_modify_other_user_po(): void
    {
        // User A tries to view User B's PO detail
        $this->actingAs($this->userA, 'sanctum')
            ->getJson("/api/purchase-orders/{$this->poB->id}")
            ->assertStatus(403);

        // User A tries to view User B's PO PDF
        $this->actingAs($this->userA, 'sanctum')
            ->getJson("/api/purchase-orders/{$this->poB->id}/pdf")
            ->assertStatus(403);

        // User A tries to delete User B's PO
        $this->actingAs($this->userA, 'sanctum')
            ->deleteJson("/api/purchase-orders/{$this->poB->id}")
            ->assertStatus(403);

        // User A can view their own PO
        $this->actingAs($this->userA, 'sanctum')
            ->getJson("/api/purchase-orders/{$this->poA->id}")
            ->assertStatus(200)
            ->assertJsonFragment(['po_number' => 'PO-2026-0001']);
    }

    public function test_regular_user_can_only_see_their_own_purchase_requests(): void
    {
        // User A only sees PR A
        $responseA = $this->actingAs($this->userA, 'sanctum')->getJson('/api/purchase-requests');
        $responseA->assertStatus(200);
        $responseA->assertJsonCount(1, 'data');
        $responseA->assertJsonFragment(['pr_number' => 'PR-2026-0001']);
        $responseA->assertJsonMissing(['pr_number' => 'PR-2026-0002']);

        // User A cannot view User B's PR
        $this->actingAs($this->userA, 'sanctum')
            ->getJson("/api/purchase-requests/{$this->prB->id}")
            ->assertStatus(403);
    }

    public function test_new_po_and_pr_automatically_assigned_to_authenticated_user(): void
    {
        // User A creates PR
        $prResponse = $this->actingAs($this->userA, 'sanctum')->postJson('/api/purchase-requests', [
            'pr_number' => 'PR-2026-0999',
            'company_id' => $this->company->id,
            'department' => 'Engineering',
            'request_date' => '2026-10-10',
            'items' => [
                [
                    'item_id' => $this->item->id,
                    'quantity' => 5,
                    'estimated_price' => 10000000,
                ],
            ],
        ]);
        $prResponse->assertStatus(201);
        $this->assertDatabaseHas('purchase_requests', [
            'pr_number' => 'PR-2026-0999',
            'user_id' => $this->userA->id,
        ]);

        // User A creates PO
        $poResponse = $this->actingAs($this->userA, 'sanctum')->postJson('/api/purchase-orders', [
            'po_number' => 'PO-2026-0999',
            'company_id' => $this->company->id,
            'vendor_id' => $this->vendor->id,
            'order_date' => '2026-10-10',
            'items' => [
                [
                    'item_id' => $this->item->id,
                    'quantity' => 3,
                    'unit_price' => 10000000,
                ],
            ],
        ]);
        $poResponse->assertStatus(201);
        $this->assertDatabaseHas('purchase_orders', [
            'po_number' => 'PO-2026-0999',
            'user_id' => $this->userA->id,
        ]);
    }

    public function test_microservice_with_jwt_b_can_access_all_users_pos_and_prs(): void
    {
        $token = $this->generateJwtBToken();

        // Microservice reads v1 POs - must see both PO A and PO B
        $poResponse = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/v1/purchase-orders');
        $poResponse->assertStatus(200);
        $poResponse->assertJsonCount(2, 'data');
        $poResponse->assertJsonFragment(['po_number' => 'PO-2026-0001']);
        $poResponse->assertJsonFragment(['po_number' => 'PO-2026-0002']);

        // Microservice reads single PO of User B
        $singlePoResponse = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson("/api/v1/purchase-orders/{$this->poB->id}");
        $singlePoResponse->assertStatus(200)
            ->assertJsonFragment(['po_number' => 'PO-2026-0002']);

        // Microservice reads v1 PRs - must see both PR A and PR B
        $prResponse = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/v1/purchase-requests');
        $prResponse->assertStatus(200);
        $prResponse->assertJsonCount(2, 'data');
        $prResponse->assertJsonFragment(['pr_number' => 'PR-2026-0001']);
        $prResponse->assertJsonFragment(['pr_number' => 'PR-2026-0002']);
    }

    public function test_integration_service_account_can_access_all_users_pos_and_prs(): void
    {
        // integration-service account acting via Sanctum / API
        $response = $this->actingAs($this->integrationUser, 'sanctum')->getJson('/api/purchase-orders');
        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment(['po_number' => 'PO-2026-0001']);
        $response->assertJsonFragment(['po_number' => 'PO-2026-0002']);
    }
}
