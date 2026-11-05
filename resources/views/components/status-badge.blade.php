@props(['status', 'mode' => 'short'])

@php
    $labels = [
        'pending'    => ['short' => 'Menunggu Bayar', 'long' => 'Menunggu Transfer Bank', 'detail' => 'Status: Menunggu Pembayaran Transfer'],
        'processing' => ['short' => 'Disanitasi & Kemas', 'long' => 'Sedang Disanitasi & Dikemas', 'detail' => 'Status: Pembayaran Terverifikasi • Disanitasi Ozon SirkulaCare'],
        'completed'  => ['short' => 'Selesai ✓', 'long' => 'Pesanan Selesai ✓', 'detail' => 'Status: Selesai • Telah Dikirim Kurir'],
        'cancelled'  => ['short' => 'Batal', 'long' => 'Dibatalkan', 'detail' => 'Status: Dibatalkan'],
    ];
    $styles = [
        'pending'    => ['badge' => 'bg-yellow-50 text-yellow-800 border border-yellow-200', 'dot' => 'bg-yellow-500 animate-pulse'],
        'processing' => ['badge' => 'bg-emerald-soft/40 text-emerald border border-emerald-soft', 'dot' => 'bg-emerald'],
        'completed'  => ['badge' => 'bg-emerald text-white', 'dot' => 'bg-white'],
        'cancelled'  => ['badge' => 'bg-red-50 text-red-700 border border-red-200', 'dot' => 'bg-red-600'],
    ];
    $key = array_key_exists($status, $labels) ? $status : 'cancelled';
@endphp

<span {{ $attributes->class(['badge gap-1.5 whitespace-normal', $styles[$key]['badge']]) }}>
    @if($mode === 'detail')
        <span class="w-2 h-2 rounded-full shrink-0 {{ $styles[$key]['dot'] }}" aria-hidden="true"></span>
    @endif
    {{ $labels[$key][$mode] ?? $labels[$key]['short'] }}
</span>
