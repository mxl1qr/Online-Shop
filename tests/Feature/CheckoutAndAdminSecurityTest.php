<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutAndAdminSecurityTest extends TestCase
{
    use RefreshDatabase;

    private function createProductWithVariant(int $price = 100000, int $stock = 10): ProductVariant
    {
        $category = Category::create([
            'name' => 'Atasan Test',
            'slug' => 'atasan-test',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name'        => 'Kemeja Uji',
            'slug'        => 'kemeja-uji',
            'description' => 'Deskripsi uji',
            'base_price'  => $price,
            'is_active'   => true,
        ]);

        return ProductVariant::create([
            'product_id'       => $product->id,
            'size'             => 'L',
            'color'            => 'Navy',
            'stock'            => $stock,
            'sku'              => 'SRK-TEST-L',
            'additional_price' => 0,
        ]);
    }

    public function test_owner_can_access_their_order_details(): void
    {
        $user = User::factory()->create();
        $order = Order::create([
            'order_number'     => 'ORD-20261104-00001',
            'user_id'          => $user->id,
            'total_amount'     => 150000,
            'status'           => 'pending',
            'shipping_name'    => 'Budi Test',
            'shipping_phone'   => '081234567890',
            'shipping_address' => 'Jl. Merdeka No 1',
            'payment_method'   => 'bca',
        ]);

        $response = $this->actingAs($user)->get("/checkout/success/{$order->order_number}");

        $response->assertOk();
        $response->assertSee('ORD-20261104-00001');
    }

    public function test_admin_can_access_any_order_details(): void
    {
        $owner = User::factory()->create();
        $admin = User::factory()->create(['role' => 'admin']);

        $order = Order::create([
            'order_number'     => 'ORD-20261104-00002',
            'user_id'          => $owner->id,
            'total_amount'     => 150000,
            'status'           => 'pending',
            'shipping_name'    => 'Customer Test',
            'shipping_phone'   => '081234567890',
            'shipping_address' => 'Jl. Melati No 2',
            'payment_method'   => 'mandiri',
        ]);

        $response = $this->actingAs($admin)->get("/checkout/success/{$order->order_number}");

        $response->assertOk();
        $response->assertSee('ORD-20261104-00002');
    }

    public function test_unauthorized_user_is_forbidden_from_viewing_others_order(): void
    {
        $owner = User::factory()->create();
        $stranger = User::factory()->create();

        $order = Order::create([
            'order_number'     => 'ORD-20261104-00003',
            'user_id'          => $owner->id,
            'total_amount'     => 150000,
            'status'           => 'pending',
            'shipping_name'    => 'Private User',
            'shipping_phone'   => '081234567890',
            'shipping_address' => 'Jl. Rahasia No 3',
            'payment_method'   => 'bca',
        ]);

        $response = $this->actingAs($stranger)->get("/checkout/success/{$order->order_number}");

        $response->assertStatus(403);
    }

    public function test_checkout_adds_shipping_cost_when_under_threshold(): void
    {
        $user = User::factory()->create();
        $variant = $this->createProductWithVariant(price: 150000); // 150k < 250k threshold

        $cart = [
            $variant->id => [
                'variant_id'   => $variant->id,
                'product_id'   => $variant->product->id,
                'product_name' => $variant->product->name,
                'slug'         => $variant->product->slug,
                'category'     => 'Atasan Test',
                'image'        => null,
                'color'        => 'Navy',
                'size'         => 'L',
                'price'        => 150000,
                'quantity'     => 1,
                'max_stock'    => 10,
            ],
        ];

        $response = $this->actingAs($user)
            ->withSession(['cart' => $cart])
            ->post('/checkout', [
                'shipping_name'    => 'Pembeli Test',
                'shipping_phone'   => '081122334455',
                'shipping_address' => 'Jl. Sudirman 45 Jakarta',
                'payment_method'   => 'bca',
            ]);

        $order = Order::where('user_id', $user->id)->latest()->first();
        $this->assertNotNull($order);
        // Subtotal (150.000) + Ongkir (20.000) = 170.000
        $this->assertEquals(170000, $order->total_amount);
        $response->assertRedirect("/checkout/success/{$order->order_number}");
    }

    public function test_checkout_gives_free_shipping_when_meeting_threshold(): void
    {
        $user = User::factory()->create();
        $variant = $this->createProductWithVariant(price: 260000); // 260k >= 250k threshold

        $cart = [
            $variant->id => [
                'variant_id'   => $variant->id,
                'product_id'   => $variant->product->id,
                'product_name' => $variant->product->name,
                'slug'         => $variant->product->slug,
                'category'     => 'Atasan Test',
                'image'        => null,
                'color'        => 'Navy',
                'size'         => 'L',
                'price'        => 260000,
                'quantity'     => 1,
                'max_stock'    => 10,
            ],
        ];

        $this->actingAs($user)
            ->withSession(['cart' => $cart])
            ->post('/checkout', [
                'shipping_name'    => 'Pembeli Bebas Ongkir',
                'shipping_phone'   => '081122334455',
                'shipping_address' => 'Jl. Thamrin 10 Jakarta',
                'payment_method'   => 'mandiri',
            ]);

        $order = Order::where('user_id', $user->id)->latest()->first();
        $this->assertNotNull($order);
        // Subtotal (260.000) + Ongkir (0) = 260.000
        $this->assertEquals(260000, $order->total_amount);
    }

    public function test_admin_cannot_delete_product_with_order_history(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $variant = $this->createProductWithVariant();

        $order = Order::create([
            'order_number'     => 'ORD-20261104-PAST',
            'user_id'          => $user->id,
            'total_amount'     => 100000,
            'status'           => 'completed',
            'shipping_name'    => 'Histori User',
            'shipping_phone'   => '0812345678',
            'shipping_address' => 'Jl. Antik 99',
            'payment_method'   => 'bca',
        ]);

        OrderItem::create([
            'order_id'           => $order->id,
            'product_id'         => $variant->product_id,
            'product_variant_id' => $variant->id,
            'product_name'       => $variant->product->name,
            'variant_info'       => 'Navy - L',
            'quantity'           => 1,
            'unit_price'         => 100000,
            'subtotal'           => 100000,
        ]);

        $response = $this->actingAs($admin)->delete("/admin/products/{$variant->product_id}");

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('products', ['id' => $variant->product_id]);
    }

    public function test_admin_cannot_delete_user_with_order_history(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();

        Order::create([
            'order_number'     => 'ORD-20261104-HIST',
            'user_id'          => $user->id,
            'total_amount'     => 100000,
            'status'           => 'completed',
            'shipping_name'    => 'User Riwayat',
            'shipping_phone'   => '0812345678',
            'shipping_address' => 'Jl. Antik 99',
            'payment_method'   => 'bca',
        ]);

        $response = $this->actingAs($admin)->delete("/admin/users/{$user->id}");

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    public function test_admin_order_status_toggle_does_not_leak_or_inflate_stock(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $variant = $this->createProductWithVariant(price: 100000, stock: 5);

        $order = Order::create([
            'order_number'     => 'ORD-20261104-STOCK',
            'user_id'          => $user->id,
            'total_amount'     => 200000,
            'status'           => 'pending',
            'shipping_name'    => 'Tester Stok',
            'shipping_phone'   => '0812345678',
            'shipping_address' => 'Jl. Uji Stok No 1',
            'payment_method'   => 'bca',
        ]);

        OrderItem::create([
            'order_id'           => $order->id,
            'product_id'         => $variant->product_id,
            'product_variant_id' => $variant->id,
            'product_name'       => $variant->product->name,
            'variant_info'       => 'Navy - L',
            'quantity'           => 2,
            'unit_price'         => 100000,
            'subtotal'           => 200000,
        ]);

        // 1. Batalkan pesanan -> stok bertambah dari 5 menjadi 7
        $this->actingAs($admin)->patch("/admin/orders/{$order->id}/status", ['status' => 'cancelled']);
        $this->assertEquals(7, $variant->fresh()->stock);

        // 2. Aktifkan kembali ke processing -> stok wajib berkurang kembali dari 7 menjadi 5 (tidak bocor/inflasi)
        $this->actingAs($admin)->patch("/admin/orders/{$order->id}/status", ['status' => 'processing']);
        $this->assertEquals(5, $variant->fresh()->stock);
        $this->assertEquals('processing', $order->fresh()->status);
    }

    public function test_firebase_google_auth_callback_creates_and_logs_in_customer(): void
    {
        $firebase = app(\App\Services\FirebaseService::class);
        $projectId = $firebase->getProjectId();

        $header = $firebase->base64UrlEncode(json_encode(['alg' => 'RS256', 'kid' => 'test-kid']));
        $payload = $firebase->base64UrlEncode(json_encode([
            'iss'            => "https://securetoken.google.com/{$projectId}",
            'aud'            => $projectId,
            'sub'            => 'firebase-google-uid-999',
            'email'          => 'googleuser@sirkula.id',
            'name'           => 'Google Sirkula Member',
            'picture'        => 'https://example.com/avatar.jpg',
            'email_verified' => true,
            'exp'            => time() + 3600,
        ]));
        $fakeToken = "{$header}.{$payload}.fakesignature";

        $response = $this->post('/auth/firebase/callback', [
            'id_token' => $fakeToken,
        ]);

        $response->assertRedirect(route('products.index', absolute: false));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email'        => 'googleuser@sirkula.id',
            'firebase_uid' => 'firebase-google-uid-999',
        ]);
    }

    public function test_firebase_nosql_snapshot_command_and_admin_endpoint(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->createProductWithVariant(price: 185000, stock: 3);

        $response = $this->actingAs($admin)->post('/admin/firebase/snapshot');
        $response->assertRedirect(route('admin.settings.index'));
        $response->assertSessionHas('success');

        $snapshotPath = config('firebase.firestore.snapshot_path');
        $this->assertFileExists($snapshotPath);

        $decoded = json_decode((string) file_get_contents($snapshotPath), true);
        $this->assertEquals('firestore-nosql-v1', $decoded['meta']['schema'] ?? null);
        $this->assertArrayHasKey('products', $decoded['collections'] ?? []);
    }

    public function test_admin_login_from_storefront_always_redirects_to_admin_cms_not_homepage(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Simulasikan Admin membuka /login dari halaman beranda toko (/)
        $this->from('/')->get('/login');

        $response = $this->post('/login', [
            'email'    => $admin->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($admin);
        $response->assertRedirect(route('admin.orders.index', absolute: false));
    }

    public function test_google_customer_login_when_intended_url_was_admin_never_triggers_403(): void
    {
        $firebase = app(\App\Services\FirebaseService::class);
        $projectId = $firebase->getProjectId();

        $header = $firebase->base64UrlEncode(json_encode(['alg' => 'RS256', 'kid' => 'test-kid']));
        $payload = $firebase->base64UrlEncode(json_encode([
            'iss'            => "https://securetoken.google.com/{$projectId}",
            'aud'            => $projectId,
            'sub'            => 'firebase-customer-uid-123',
            'email'          => 'customer.google@sirkula.id',
            'name'           => 'Customer Google',
            'email_verified' => true,
            'exp'            => time() + 3600,
        ]));
        $fakeToken = "{$header}.{$payload}.fakesignature";

        // Simulasikan session url.intended sebelumnya tertinggal di /admin/orders
        $response = $this->withSession(['url.intended' => url('/admin/orders')])
            ->post('/auth/firebase/callback', [
                'id_token' => $fakeToken,
            ]);

        $response->assertRedirect(route('products.index', absolute: false));
    }

    public function test_google_login_with_admin_email_preserves_admin_role_and_redirects_to_cms(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin.google@sirkula.id',
            'role'  => 'admin',
        ]);

        $firebase = app(\App\Services\FirebaseService::class);
        $projectId = $firebase->getProjectId();

        $header = $firebase->base64UrlEncode(json_encode(['alg' => 'RS256', 'kid' => 'test-kid']));
        $payload = $firebase->base64UrlEncode(json_encode([
            'iss'            => "https://securetoken.google.com/{$projectId}",
            'aud'            => $projectId,
            'sub'            => 'firebase-admin-uid-777',
            'email'          => 'admin.google@sirkula.id',
            'name'           => 'Admin Google',
            'email_verified' => true,
            'exp'            => time() + 3600,
        ]));
        $fakeToken = "{$header}.{$payload}.fakesignature";

        $response = $this->withSession(['url.intended' => url('/')])
            ->post('/auth/firebase/callback', [
                'id_token' => $fakeToken,
            ]);

        $this->assertAuthenticatedAs($admin);
        $this->assertEquals('admin', $admin->fresh()->role);
        $response->assertRedirect(route('admin.orders.index', absolute: false));
    }

    public function test_admin_orders_index_view_renders_successfully(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $product = $this->createProductWithVariant(price: 150000, stock: 5);

        // Test Dedicated Orders View
        $responseOrders = $this->actingAs($admin)->get(route('admin.orders.index'));
        $responseOrders->assertOk();
        $responseOrders->assertSee('Enterprise CMS');

        // Test Dedicated Products View
        $responseProducts = $this->actingAs($admin)->get(route('admin.products.index'));
        $responseProducts->assertOk();
        $responseProducts->assertSee($product->name);

        // Test Dedicated Dashboard View
        $responseDashboard = $this->actingAs($admin)->get(route('admin.dashboard'));
        $responseDashboard->assertOk();
        $responseDashboard->assertSee('Ringkasan Eksekutif');
    }

    public function test_admin_cannot_demote_themselves_from_admin_role(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->patch(route('admin.users.update', $admin->id), [
            'name'  => 'Admin Try Demote',
            'email' => $admin->email,
            'role'  => 'customer',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('error');
        $this->assertEquals('admin', $admin->fresh()->role);
    }

    public function test_unauthorized_user_cannot_register_firebase_domain(): void
    {
        // Tamu anonim mencoba mendaftarkan domain
        $responseGuest = $this->postJson('/auth/firebase/authorize-domain', ['domain' => 'evil-phishing.com']);
        $responseGuest->assertUnauthorized();

        // Customer biasa juga ditolak 403
        $customer = User::factory()->create(['role' => 'customer']);
        $responseCustomer = $this->actingAs($customer)->postJson('/auth/firebase/authorize-domain', ['domain' => 'evil-phishing.com']);
        $responseCustomer->assertForbidden();
    }

    public function test_user_with_order_history_cannot_delete_profile_account(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $variant = $this->createProductWithVariant(price: 100000, stock: 2);

        Order::create([
            'order_number'     => 'ORD-20261105-PROT',
            'user_id'          => $user->id,
            'total_amount'     => 100000,
            'status'           => 'pending',
            'shipping_name'    => 'Protected User',
            'shipping_phone'   => '0812345678',
            'shipping_address' => 'Jl. Aman No. 1',
            'payment_method'   => 'bca',
        ]);

        $response = $this->actingAs($user)->delete('/profile', [
            'password' => 'password',
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    public function test_product_multi_image_lookbook_and_reordering(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $variant = $this->createProductWithVariant(price: 120000, stock: 4);
        $product = $variant->product;

        // Update urutan foto lookbook via existing_images JSON
        $reordered = ['products/tshirt-boxy-2.jpg', 'products/tshirt-boxy-1.jpg'];
        $response = $this->actingAs($admin)->put(route('admin.products.update', $product->id), [
            'name'            => 'Reordered Shirt',
            'category_id'     => $product->category_id,
            'base_price'      => 125000,
            'description'     => 'Kemeja dengan galeri foto terurut',
            'existing_images' => json_encode($reordered),
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $fresh = $product->fresh();
        $this->assertEquals('products/tshirt-boxy-2.jpg', $fresh->image);
        $this->assertEquals($reordered, $fresh->images);
        $this->assertEquals($reordered, $fresh->gallery_images);
    }
}
