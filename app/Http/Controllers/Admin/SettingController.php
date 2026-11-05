<?php

namespace App\Http\Controllers\Admin;

use App\Services\FirebaseService;
use Illuminate\Http\Request;

class SettingController extends AdminBaseController
{
    public function index()
    {
        $storeConfig = self::getStoreConfig();
        $firebaseStatus = app(FirebaseService::class)->getStatus();

        return view('admin.settings.index', compact('storeConfig', 'firebaseStatus'));
    }

    public function update(Request $request)
    {
        $config = self::getStoreConfig();

        if ($request->has('remove_coupon_index')) {
            $idx = (int) $request->remove_coupon_index;
            if (isset($config['coupons'][$idx])) {
                $code = $config['coupons'][$idx]['code'] ?? 'Kupon';
                array_splice($config['coupons'], $idx, 1);
                $this->saveStoreConfig($config);
                return redirect()->route('admin.settings.index')
                    ->with('success', "Kupon {$code} berhasil dihapus dari sistem.");
            }
        }

        if ($request->filled('new_coupon_code') && $request->filled('new_coupon_discount')) {
            $validated = $request->validate([
                'new_coupon_code'     => ['required', 'string', 'max:25', 'regex:/^[A-Za-z0-9_\-]+$/'],
                'new_coupon_discount' => 'required|numeric|min:1000',
                'new_coupon_title'    => 'required|string|max:120',
                'new_coupon_min'      => 'nullable|numeric|min:0',
                'new_coupon_expires'  => 'nullable|string|max:50',
            ]);

            $cleanCode = strtoupper(trim($validated['new_coupon_code']));
            $existingCodes = array_map('strtoupper', array_column($config['coupons'] ?? [], 'code'));
            if (in_array($cleanCode, $existingCodes, true)) {
                return redirect()->route('admin.settings.index')
                    ->with('error', "Kode kupon \"{$cleanCode}\" sudah terdaftar. Gunakan kode lain atau hapus kupon lama terlebih dahulu.");
            }

            $discount = (int) $validated['new_coupon_discount'];
            $minPurchase = (int) ($validated['new_coupon_min'] ?? 0);
            if ($minPurchase > 0 && $discount > $minPurchase) {
                return redirect()->route('admin.settings.index')
                    ->with('error', "Nilai diskon (Rp " . number_format($discount, 0, ',', '.') . ") tidak boleh melebihi belanja minimum (Rp " . number_format($minPurchase, 0, ',', '.') . ").");
            }

            // Normalisasi tanggal kedaluwarsa
            $expiry = $validated['new_coupon_expires'] ?: '31/12/2026';
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $expiry)) {
                $expiry = date('d/m/Y', strtotime($expiry));
            }

            $config['coupons'][] = [
                'code'         => $cleanCode,
                'title'        => trim($validated['new_coupon_title']),
                'discount'     => $discount,
                'min_purchase' => $minPurchase,
                'expires'      => $expiry,
            ];

            $this->saveStoreConfig($config);
            return redirect()->route('admin.settings.index')
                ->with('success', "Kupon promo \"{$cleanCode}\" berhasil diterbitkan.");
        }

        $validated = $request->validate([
            'announcement'            => 'nullable|string|max:255',
            'free_shipping_threshold' => 'required|numeric|min:0',
            'whatsapp_cs'             => 'nullable|string|max:30',
            'bank_bca'                => 'nullable|string|max:120',
            'bank_mandiri'            => 'nullable|string|max:120',
            'bank_bri'                => 'nullable|string|max:120',
            'firebase_api_key'        => 'nullable|string|max:255',
            'firebase_auth_domain'    => 'nullable|string|max:255',
            'firebase_app_id'         => 'nullable|string|max:255',
        ]);

        $config['announcement'] = $validated['announcement'] ?? $config['announcement'];
        $config['free_shipping_threshold'] = (int) $validated['free_shipping_threshold'];

        // Sanitasi nomor WhatsApp ke format internasional murni angka (628...)
        if (!empty($validated['whatsapp_cs'])) {
            $rawWa = preg_replace('/[^0-9]/', '', (string) $validated['whatsapp_cs']);
            if (str_starts_with($rawWa, '08')) {
                $rawWa = '62' . substr($rawWa, 1);
            } elseif (str_starts_with($rawWa, '8')) {
                $rawWa = '62' . $rawWa;
            }
            $config['whatsapp_cs'] = $rawWa;
        }

        $config['bank_bca'] = $validated['bank_bca'] ?? $config['bank_bca'];
        $config['bank_mandiri'] = $validated['bank_mandiri'] ?? $config['bank_mandiri'];
        $config['bank_bri'] = $validated['bank_bri'] ?? $config['bank_bri'];

        if ($request->has('firebase_api_key')) {
            $config['firebase_api_key'] = trim((string) $validated['firebase_api_key']);
        }
        if ($request->has('firebase_auth_domain')) {
            $config['firebase_auth_domain'] = trim((string) $validated['firebase_auth_domain']);
        }
        if ($request->has('firebase_app_id')) {
            $config['firebase_app_id'] = trim((string) $validated['firebase_app_id']);
        }

        $this->saveStoreConfig($config);

        return redirect()->route('admin.settings.index')
            ->with('success', 'Konfigurasi toko dan integrasi Firebase berhasil diperbarui.');
    }

    public function generateFirebaseSnapshot(FirebaseService $firebase)
    {
        try {
            $path = $firebase->exportSnapshotToFile();
            $meta = $firebase->getStatus();
            $size = $meta['snapshot']['size_kb'] ?? 0;

            return redirect()->route('admin.settings.index')
                ->with('success', "Snapshot dokumen Firestore NoSQL berhasil diperbarui ({$size} KB). File siap dimigrasikan via CLI: php artisan firebase:migrate --push");
        } catch (\Throwable $e) {
            return redirect()->route('admin.settings.index')
                ->with('error', "Gagal membuat snapshot Firestore: {$e->getMessage()}");
        }
    }

    private function saveStoreConfig(array $config): void
    {
        $path = storage_path('app/store_config.json');
        file_put_contents($path, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}
