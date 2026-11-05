<?php

namespace App\Services;

use App\Http\Controllers\AdminOrderController;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class FirebaseService
{
    protected ?array $credentials = null;

    public function __construct()
    {
        $credPath = $this->resolveCredentialsPath();
        if ($credPath && file_exists($credPath)) {
            $decoded = json_decode((string) file_get_contents($credPath), true);
            if (is_array($decoded) && isset($decoded['project_id'], $decoded['private_key'], $decoded['client_email'])) {
                $this->credentials = $decoded;
            }
        }
    }

    public function resolveCredentialsPath(): string
    {
        $configured = (string) config('firebase.credentials', storage_path('firebase/firebase-credentials.json'));
        if (file_exists($configured)) {
            return $configured;
        }

        $relative = base_path($configured);
        if (file_exists($relative)) {
            return $relative;
        }

        return storage_path('firebase/firebase-credentials.json');
    }

    public function isServiceAccountReady(): bool
    {
        return $this->credentials !== null;
    }

    public function getProjectId(): string
    {
        return $this->credentials['project_id']
            ?? (string) config('firebase.project_id', 'nuevauno-1');
    }

    public function getWebConfig(): array
    {
        $storeConfig = AdminOrderController::getStoreConfig();
        $projectId = $this->getProjectId();

        $apiKey = (string) (config('firebase.web.api_key') ?: ($storeConfig['firebase_api_key'] ?? ''));
        $appId = (string) (config('firebase.web.app_id') ?: ($storeConfig['firebase_app_id'] ?? ''));
        $authDomain = (string) (config('firebase.web.auth_domain') ?: ($storeConfig['firebase_auth_domain'] ?? "{$projectId}.firebaseapp.com"));

        return [
            'apiKey'     => trim($apiKey),
            'authDomain' => trim($authDomain),
            'projectId'  => $projectId,
            'appId'      => trim($appId),
            'enabled'    => trim($apiKey) !== '',
        ];
    }

    public function getStatus(): array
    {
        $snapshotPath = (string) config('firebase.firestore.snapshot_path', storage_path('firebase/firestore-snapshot.json'));
        $snapshotExists = file_exists($snapshotPath);
        $snapshotMeta = null;

        if ($snapshotExists) {
            $raw = json_decode((string) file_get_contents($snapshotPath), true);
            $snapshotMeta = [
                'updated_at' => date('Y-m-d H:i:s', (int) filemtime($snapshotPath)),
                'size_kb'    => round(filesize($snapshotPath) / 1024, 2),
                'counts'     => $raw['meta']['counts'] ?? [],
            ];
        }

        $web = $this->getWebConfig();

        return [
            'service_account_ready' => $this->isServiceAccountReady(),
            'credentials_path'      => 'storage/firebase/firebase-credentials.json',
            'project_id'            => $this->getProjectId(),
            'client_email'          => $this->credentials['client_email'] ?? null,
            'web_auth_ready'        => $web['enabled'],
            'auth_domain'           => $web['authDomain'],
            'snapshot_exists'       => $snapshotExists,
            'snapshot'              => $snapshotMeta,
        ];
    }

    /**
     * Verify Firebase Auth ID Token (RS256 JWT from Google Sign-In)
     */
    public function verifyIdToken(string $idToken): array
    {
        $parts = explode('.', $idToken);
        if (count($parts) !== 3) {
            throw new \InvalidArgumentException('Format token Firebase tidak valid.');
        }

        [$headerB64, $payloadB64, $sigB64] = $parts;
        $header = json_decode($this->base64UrlDecode($headerB64), true);
        $payload = json_decode($this->base64UrlDecode($payloadB64), true);

        if (!is_array($header) || !is_array($payload)) {
            throw new \InvalidArgumentException('Payload token Firebase gagal didekode.');
        }

        $projectId = $this->getProjectId();

        if (($payload['aud'] ?? '') !== $projectId) {
            throw new \InvalidArgumentException('Audience token Firebase tidak cocok dengan project ' . $projectId);
        }

        if (($payload['iss'] ?? '') !== "https://securetoken.google.com/{$projectId}") {
            throw new \InvalidArgumentException('Issuer token Firebase tidak valid.');
        }

        if (($payload['exp'] ?? 0) < time()) {
            throw new \InvalidArgumentException('Sesi token Firebase telah kedaluwarsa.');
        }

        if (empty($payload['sub']) || empty($payload['email'])) {
            throw new \InvalidArgumentException('Token Firebase tidak memuat UID atau alamat email.');
        }

        // Di luar environment testing, verifikasi signature RS256 menggunakan sertifikat publik Google
        if (!app()->environment('testing')) {
            $kid = $header['kid'] ?? '';
            $certs = Cache::remember('firebase_google_public_certs', 3600, function () {
                $res = Http::timeout(10)->get('https://www.googleapis.com/robot/v1/metadata/x509/securetoken@system.gserviceaccount.com');
                return $res->successful() ? $res->json() : [];
            });

            if (empty($certs[$kid])) {
                throw new \InvalidArgumentException('Sertifikat publik Google untuk token ini tidak ditemukan.');
            }

            $signature = $this->base64UrlDecode($sigB64);
            $verified = openssl_verify("{$headerB64}.{$payloadB64}", $signature, $certs[$kid], OPENSSL_ALGO_SHA256);
            if ($verified !== 1) {
                throw new \InvalidArgumentException('Signature kriptografi token Firebase tidak sah.');
            }
        }

        return [
            'uid'            => (string) $payload['sub'],
            'email'          => strtolower((string) $payload['email']),
            'name'           => (string) ($payload['name'] ?? explode('@', (string) $payload['email'])[0]),
            'picture'        => $payload['picture'] ?? null,
            'email_verified' => (bool) ($payload['email_verified'] ?? true),
        ];
    }

    /**
     * Build complete denormalized NoSQL document structure for Firestore migration
     */
    public function buildNoSqlPayload(): array
    {
        $categories = Category::withCount('products')->get()->mapWithKeys(function (Category $cat) {
            return [
                $cat->slug => [
                    'id'             => (string) $cat->id,
                    'name'           => $cat->name,
                    'slug'           => $cat->slug,
                    'products_count' => (int) $cat->products_count,
                    'updated_at'     => optional($cat->updated_at)->toIso8601String(),
                ],
            ];
        })->all();

        $products = Product::with(['category', 'variants'])->get()->mapWithKeys(function (Product $prod) {
            return [
                $prod->slug => [
                    'id'              => (string) $prod->id,
                    'name'            => $prod->name,
                    'slug'            => $prod->slug,
                    'category_id'     => (string) $prod->category_id,
                    'category_slug'   => $prod->category->slug ?? '',
                    'category_name'   => $prod->category->name ?? '',
                    'description'     => (string) $prod->description,
                    'base_price'      => (int) $prod->base_price,
                    'image'           => (string) $prod->image,
                    'secondary_image' => (string) $prod->secondary_image,
                    'is_active'       => (bool) $prod->is_active,
                    'total_stock'     => (int) $prod->variants->sum('stock'),
                    'variants'        => $prod->variants->map(fn ($v) => [
                        'id'               => (string) $v->id,
                        'sku'              => (string) $v->sku,
                        'size'             => (string) $v->size,
                        'color'            => (string) $v->color,
                        'stock'            => (int) $v->stock,
                        'additional_price' => (int) $v->additional_price,
                    ])->values()->all(),
                    'updated_at'      => optional($prod->updated_at)->toIso8601String(),
                ],
            ];
        })->all();

        $orders = Order::with(['user', 'items'])->get()->mapWithKeys(function (Order $order) {
            return [
                $order->order_number => [
                    'id'               => (string) $order->id,
                    'order_number'     => $order->order_number,
                    'user_id'          => (string) $order->user_id,
                    'user_email'       => $order->user->email ?? '',
                    'total_amount'     => (int) $order->total_amount,
                    'status'           => $order->status,
                    'shipping_name'    => $order->shipping_name,
                    'shipping_phone'   => $order->shipping_phone,
                    'shipping_address' => $order->shipping_address,
                    'shipping_notes'   => (string) ($order->shipping_notes ?? ''),
                    'payment_method'   => $order->payment_method,
                    'items'            => $order->items->map(fn ($item) => [
                        'product_id'         => (string) $item->product_id,
                        'product_variant_id' => (string) ($item->product_variant_id ?? ''),
                        'product_name'       => $item->product_name,
                        'variant_info'       => $item->variant_info,
                        'quantity'           => (int) $item->quantity,
                        'unit_price'         => (int) $item->unit_price,
                        'subtotal'           => (int) $item->subtotal,
                    ])->values()->all(),
                    'created_at'       => optional($order->created_at)->toIso8601String(),
                    'updated_at'       => optional($order->updated_at)->toIso8601String(),
                ],
            ];
        })->all();

        $users = User::withCount('orders')->get()->mapWithKeys(function (User $u) {
            return [
                'user_' . $u->id => [
                    'id'           => (string) $u->id,
                    'firebase_uid' => (string) ($u->firebase_uid ?? ''),
                    'name'         => $u->name,
                    'email'        => $u->email,
                    'role'         => $u->role,
                    'avatar'       => (string) ($u->avatar ?? ''),
                    'orders_count' => (int) $u->orders_count,
                    'created_at'   => optional($u->created_at)->toIso8601String(),
                ],
            ];
        })->all();

        $storeConfig = AdminOrderController::getStoreConfig();

        return [
            'meta' => [
                'project_id'   => $this->getProjectId(),
                'generated_at' => now()->toIso8601String(),
                'schema'       => 'firestore-nosql-v1',
                'counts'       => [
                    'categories' => count($categories),
                    'products'   => count($products),
                    'orders'     => count($orders),
                    'users'      => count($users),
                ],
            ],
            'collections' => [
                'categories' => $categories,
                'products'   => $products,
                'orders'     => $orders,
                'users'      => $users,
                'settings'   => [
                    'store_config' => $storeConfig,
                ],
            ],
        ];
    }

    /**
     * Save NoSQL migration snapshot to storage/firebase/firestore-snapshot.json
     */
    public function exportSnapshotToFile(): array
    {
        $payload = $this->buildNoSqlPayload();
        $path = (string) config('firebase.firestore.snapshot_path', storage_path('firebase/firestore-snapshot.json'));
        $dir = dirname($path);

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        file_put_contents($path, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        return [
            'path'   => $path,
            'counts' => $payload['meta']['counts'],
            'size'   => filesize($path),
        ];
    }

    /**
     * Generate OAuth2 Bearer Token using Service Account RS256 Private Key
     */
    public function getAccessToken(): string
    {
        if (!$this->isServiceAccountReady()) {
            throw new \RuntimeException('File kredensial storage/firebase/firebase-credentials.json tidak ditemukan atau tidak valid.');
        }

        $now = time();
        $header = $this->base64UrlEncode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
        $claimSet = $this->base64UrlEncode(json_encode([
            'iss'   => $this->credentials['client_email'],
            'scope' => 'https://www.googleapis.com/auth/datastore https://www.googleapis.com/auth/identitytoolkit https://www.googleapis.com/auth/cloud-platform',
            'aud'   => $this->credentials['token_uri'] ?? 'https://oauth2.googleapis.com/token',
            'iat'   => $now,
            'exp'   => $now + 3600,
        ]));

        $unsigned = "{$header}.{$claimSet}";
        $signature = '';
        $ok = openssl_sign($unsigned, $signature, $this->credentials['private_key'], OPENSSL_ALGO_SHA256);

        if (!$ok) {
            throw new \RuntimeException('Gagal menandatangani JWT Service Account Firebase.');
        }

        $jwt = $unsigned . '.' . $this->base64UrlEncode($signature);

        $response = Http::asForm()->timeout(15)->post($this->credentials['token_uri'] ?? 'https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion'  => $jwt,
        ]);

        if (!$response->successful() || empty($response->json('access_token'))) {
            throw new \RuntimeException('Gagal memperoleh OAuth2 Access Token dari Google: ' . $response->body());
        }

        return (string) $response->json('access_token');
    }

    /**
     * Automatically register local & production domains (127.0.0.1, nuevaweb.test, localhost, etc.)
     * into Firebase Authentication -> Settings -> Authorized Domains via Identity Toolkit Admin v2 API.
     */
    public function syncAuthorizedDomains(array $extraDomains = []): array
    {
        $token = $this->getAccessToken();
        $projectId = $this->getProjectId();
        $url = "https://identitytoolkit.googleapis.com/admin/v2/projects/{$projectId}/config";

        $getRes = Http::withToken($token)->timeout(15)->get($url);
        if (!$getRes->successful()) {
            throw new \RuntimeException('Gagal membaca konfigurasi Firebase Auth: ' . $getRes->body());
        }

        $currentDomains = $getRes->json('authorizedDomains') ?? [];
        $appHost = parse_url((string) config('app.url'), PHP_URL_HOST);

        $requiredDomains = array_filter(array_unique(array_merge(
            $currentDomains,
            [
                'localhost',
                '127.0.0.1',
                'nuevaweb.test',
                'nuevaweb.local',
                'sirkula.test',
                'sirkula1.wasmer.app',
                $appHost ?: null,
            ],
            $extraDomains
        )));

        $patchRes = Http::withToken($token)->timeout(15)->patch($url . '?updateMask=authorizedDomains', [
            'authorizedDomains' => array_values($requiredDomains),
        ]);

        if (!$patchRes->successful()) {
            throw new \RuntimeException('Gagal memperbarui Authorized Domains Firebase: ' . $patchRes->body());
        }

        return $patchRes->json('authorizedDomains') ?? array_values($requiredDomains);
    }

    /**
     * Push NoSQL collections directly to Cloud Firestore REST API
     */
    public function pushToFirestore(): array
    {
        $token = $this->getAccessToken();
        $projectId = $this->getProjectId();
        $payload = $this->buildNoSqlPayload();
        $baseUrl = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents";

        $pushed = ['categories' => 0, 'products' => 0, 'orders' => 0, 'users' => 0, 'settings' => 0];

        foreach ($payload['collections'] as $collectionName => $documents) {
            foreach ($documents as $docId => $docData) {
                $firestoreFields = $this->encodeFirestoreFields($docData);
                $url = "{$baseUrl}/{$collectionName}/" . rawurlencode((string) $docId);
                $res = Http::withToken($token)->timeout(15)->patch($url, [
                    'fields' => $firestoreFields,
                ]);

                if (!$res->successful()) {
                    throw new \RuntimeException("Gagal push dokumen {$collectionName}/{$docId}: " . $res->body());
                }
                $pushed[$collectionName] = ($pushed[$collectionName] ?? 0) + 1;
            }
        }

        return $pushed;
    }

    /**
     * Convert associative PHP array into Firestore REST API Value Map
     */
    public function encodeFirestoreFields(array $data): array
    {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[(string) $key] = $this->encodeFirestoreValue($value);
        }
        return $fields;
    }

    private function encodeFirestoreValue(mixed $value): array
    {
        if (is_null($value)) {
            return ['nullValue' => null];
        }
        if (is_bool($value)) {
            return ['booleanValue' => $value];
        }
        if (is_int($value)) {
            return ['integerValue' => (string) $value];
        }
        if (is_float($value)) {
            return ['doubleValue' => $value];
        }
        if (is_string($value)) {
            return ['stringValue' => $value];
        }
        if (is_array($value)) {
            if (array_is_list($value)) {
                return [
                    'arrayValue' => [
                        'values' => array_map(fn ($item) => $this->encodeFirestoreValue($item), $value),
                    ],
                ];
            }
            return [
                'mapValue' => [
                    'fields' => $this->encodeFirestoreFields($value),
                ],
            ];
        }

        return ['stringValue' => (string) $value];
    }

    public function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public function base64UrlDecode(string $data): string
    {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $data .= str_repeat('=', 4 - $remainder);
        }
        return (string) base64_decode(strtr($data, '-_', '+/'));
    }
}
