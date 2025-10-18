@extends('layouts.app')

@section('title', 'Scan QRIS - Saweria')

@section('content')
<div class="max-w-2xl mx-auto">

    <!-- Header -->
    <div class="text-center mb-8">
        <div class="inline-block p-4 bg-purple-100 rounded-full mb-4">
            <svg class="w-12 h-12 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-800 mb-2">
            Scan QRIS untuk Bayar
        </h1>
        <p class="text-gray-600">
            Gunakan aplikasi e-wallet favorit kamu
        </p>
    </div>

    <!-- QRIS Card -->
    <div class="bg-white rounded-2xl shadow-xl p-8">

        <!-- Donation Details -->
        <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg p-6 mb-6">
            <div class="flex items-center justify-between mb-3">
                <span class="text-gray-600 font-medium">Donatur:</span>
                <span class="text-gray-800 font-bold">{{ $donation->donor_name }}</span>
            </div>
            <div class="flex items-center justify-between mb-3">
                <span class="text-gray-600 font-medium">Nominal:</span>
                <span class="text-2xl font-bold text-purple-600">
                    Rp {{ number_format($donation->amount, 0, ',', '.') }}
                </span>
            </div>
            @if($donation->message)
            <div class="mt-4 pt-4 border-t border-purple-100">
                <p class="text-sm text-gray-600 italic">"{{ $donation->message }}"</p>
            </div>
            @endif
        </div>

        <!-- QRIS Code -->
        @if($qris)
        <div class="text-center mb-6">
            <div class="inline-block p-4 bg-white border-4 border-purple-200 rounded-2xl shadow-lg">
                <img src="{{ $qris }}" alt="QRIS Code" class="w-64 h-64 mx-auto">
            </div>
            <p class="text-sm text-gray-500 mt-4">
                Order ID: <span class="font-mono font-bold">{{ $donation->order_id }}</span>
            </p>
        </div>
        @else
        <div class="text-center py-8">
            <p class="text-red-600 font-semibold">QRIS code tidak tersedia</p>
        </div>
        @endif

        <!-- Status Indicator -->
        <div id="status-indicator" class="text-center mb-6">
            <div class="inline-flex items-center space-x-2 px-4 py-2 bg-yellow-50 border border-yellow-200 rounded-full">
                <div class="w-3 h-3 bg-yellow-500 rounded-full animate-pulse"></div>
                <span class="text-yellow-700 font-semibold">Menunggu Pembayaran...</span>
            </div>
        </div>

        <!-- Instructions -->
        <div class="bg-gray-50 rounded-lg p-6">
            <h3 class="font-bold text-gray-800 mb-3 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Cara Pembayaran:
            </h3>
            <ol class="list-decimal list-inside space-y-2 text-sm text-gray-600">
                <li>Buka aplikasi e-wallet (GoPay, OVO, Dana, ShopeePay, dll)</li>
                <li>Pilih menu Scan QR / QRIS</li>
                <li>Arahkan kamera ke QR code di atas</li>
                <li>Konfirmasi pembayaran di aplikasi</li>
                <li>Tunggu hingga status berubah menjadi "Berhasil"</li>
            </ol>
        </div>

        <!-- Support Payment Methods -->
        <div class="mt-6 text-center">
            <p class="text-xs text-gray-500 mb-2">Mendukung pembayaran dari:</p>
            <div class="flex items-center justify-center space-x-3 flex-wrap">
                <span class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full font-semibold">GoPay</span>
                <span class="text-xs bg-purple-100 text-purple-700 px-3 py-1 rounded-full font-semibold">OVO</span>
                <span class="text-xs bg-teal-100 text-teal-700 px-3 py-1 rounded-full font-semibold">Dana</span>
                <span class="text-xs bg-orange-100 text-orange-700 px-3 py-1 rounded-full font-semibold">ShopeePay</span>
                <span class="text-xs bg-red-100 text-red-700 px-3 py-1 rounded-full font-semibold">LinkAja</span>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-6 text-center">
        <a href="/" class="text-sm text-gray-600 hover:text-purple-600 transition">
            ← Kembali ke halaman utama
        </a>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Polling untuk cek status pembayaran setiap 3 detik
    let checkInterval = setInterval(function() {
        fetch('/donation/status/{{ $donation->order_id }}')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    clearInterval(checkInterval);

                    // Update status indicator
                    document.getElementById('status-indicator').innerHTML = `
                        <div class="inline-flex items-center space-x-2 px-4 py-2 bg-green-50 border border-green-200 rounded-full">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-green-700 font-semibold">Pembayaran Berhasil!</span>
                        </div>
                    `;

                    setTimeout(function() {
                        window.location.href = '/donation/success/{{ $donation->order_id }}';
                    }, 2000);
                }
            })
            .catch(error => console.error('Error checking status:', error));
    }, 3000);

    setTimeout(function() {
        clearInterval(checkInterval);
        document.getElementById('status-indicator').innerHTML = `
            <div class="inline-flex items-center space-x-2 px-4 py-2 bg-red-50 border border-red-200 rounded-full">
                <span class="text-red-700 font-semibold">⏱️ Waktu pembayaran habis</span>
            </div>
        `;
    }, 600000);
</script>
@endsection
