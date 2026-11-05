<?php

namespace App\Console\Commands;

use App\Services\FirebaseService;
use Illuminate\Console\Command;

class MigrateToFirebaseCommand extends Command
{
    protected $signature = 'firebase:migrate
                            {--push : Push dokumen langsung ke Cloud Firestore REST API menggunakan Service Account}';

    protected $description = 'Siapkan snapshot NoSQL Firestore (storage/firebase/firestore-snapshot.json) atau push langsung ke Cloud Firestore';

    public function handle(FirebaseService $firebase): int
    {
        $this->info('=== SIRKULA FIREBASE NoSQL MIGRATION PREPARATION ===');
        $this->line('Project ID      : ' . $firebase->getProjectId());
        $this->line('Service Account : ' . ($firebase->isServiceAccountReady() ? 'READY (storage/firebase/firebase-credentials.json)' : 'NOT FOUND'));

        $result = $firebase->exportSnapshotToFile();

        $this->newLine();
        $this->info('Snapshot NoSQL berhasil digenerate: ' . $result['path'] . ' (' . round($result['size'] / 1024, 2) . ' KB)');

        $rows = [];
        foreach ($result['counts'] as $collection => $count) {
            $rows[] = [$collection, $count, 'Ready (Denormalized NoSQL Document)'];
        }
        $rows[] = ['settings', 1, 'Ready (store_config)'];

        $this->table(['Koleksi Firestore', 'Jumlah Dokumen', 'Status Skema'], $rows);

        if ($firebase->isServiceAccountReady()) {
            try {
                $domains = $firebase->syncAuthorizedDomains();
                $this->info('Authorized Domains Firebase Auth disinkronkan: ' . implode(', ', $domains));
            } catch (\Throwable $e) {
                $this->warn('Catatan Authorized Domains: ' . $e->getMessage());
            }
        }

        if ($this->option('push')) {
            if (!$firebase->isServiceAccountReady()) {
                $this->error('Gagal push: File storage/firebase/firebase-credentials.json tidak ditemukan.');
                return self::FAILURE;
            }

            $this->warn('Mengirim dokumen ke Cloud Firestore REST API...');
            try {
                $pushed = $firebase->pushToFirestore();
                $this->info('Push ke Cloud Firestore selesai: ' . json_encode($pushed));
            } catch (\Throwable $e) {
                $this->error('Error saat push ke Firestore: ' . $e->getMessage());
                return self::FAILURE;
            }
        } else {
            $this->comment('Tip: Jalankan `php artisan firebase:migrate --push` saat siap memindahkan seluruh data ke Cloud Firestore.');
        }

        return self::SUCCESS;
    }
}
