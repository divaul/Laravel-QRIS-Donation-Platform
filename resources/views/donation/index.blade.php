@extends('layouts.app')

@section('title', 'Kirim Donasi - Saweria')

@section('content')
<div class="max-w-2xl mx-auto">

    <!-- Header Section -->
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">
            Kirim Dukunganmu! 💝
        </h1>
        <p class="text-gray-600">
            Berikan donasi untuk mendukung karya kami
        </p>
    </div>

    <!-- Donation Form Card -->
    <div class="bg-white rounded-2xl shadow-xl p-8">

@if(isset($errors) && $errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

        <form action="{{ route('donation.create') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Nama Donatur -->
            <div>
                <label for="donor_name" class="block text-sm font-semibold text-gray-700 mb-2">
                    Nama Kamu <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="donor_name"
                    name="donor_name"
                    value="{{ old('donor_name') }}"
                    required
                    placeholder="Masukkan nama kamu"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                >
            </div>

            <!-- Pesan -->
            <div>
                <label for="message" class="block text-sm font-semibold text-gray-700 mb-2">
                    Pesan (Opsional)
                </label>
                <textarea
                    id="message"
                    name="message"
                    rows="4"
                    placeholder="Tulis pesan semangat untuk kami..."
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition resize-none"
                >{{ old('message') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Maksimal 500 karakter</p>
            </div>

            <!-- Nominal Donasi -->
            <div>
                <label for="amount" class="block text-sm font-semibold text-gray-700 mb-2">
                    Nominal Donasi <span class="text-red-500">*</span>
                </label>

                <!-- Quick Amount Buttons -->
                <div class="grid grid-cols-3 gap-3 mb-3">
                    <button
                        type="button"
                        onclick="setAmount(10000)"
                        class="amount-btn py-3 px-4 border-2 border-gray-200 rounded-lg text-gray-700 font-semibold hover:border-purple-500 hover:bg-purple-50 transition"
                    >
                        Rp 10.000
                    </button>
                    <button
                        type="button"
                        onclick="setAmount(15000)"
                        class="amount-btn py-3 px-4 border-2 border-gray-200 rounded-lg text-gray-700 font-semibold hover:border-purple-500 hover:bg-purple-50 transition"
                    >
                        Rp 15.000
                    </button>
                    <button
                        type="button"
                        onclick="setAmount(20000)"
                        class="amount-btn py-3 px-4 border-2 border-gray-200 rounded-lg text-gray-700 font-semibold hover:border-purple-500 hover:bg-purple-50 transition"
                    >
                        Rp 20.000
                    </button>
                    <button
                        type="button"
                        onclick="setAmount(50000)"
                        class="amount-btn py-3 px-4 border-2 border-gray-200 rounded-lg text-gray-700 font-semibold hover:border-purple-500 hover:bg-purple-50 transition"
                    >
                        Rp 50.000
                    </button>
                    <button
                        type="button"
                        onclick="setAmount(100000)"
                        class="amount-btn py-3 px-4 border-2 border-gray-200 rounded-lg text-gray-700 font-semibold hover:border-purple-500 hover:bg-purple-50 transition"
                    >
                        Rp 100.000
                    </button>
                    <button
                        type="button"
                        onclick="setAmount(200000)"
                        class="amount-btn py-3 px-4 border-2 border-gray-200 rounded-lg text-gray-700 font-semibold hover:border-purple-500 hover:bg-purple-50 transition"
                    >
                        Rp 200.000
                    </button>
                </div>

                <!-- Custom Amount Input -->
                <div class="relative">
                    <span class="absolute left-4 top-3.5 text-gray-500 font-semibold">Rp</span>
                    <input
                        type="number"
                        id="amount"
                        name="amount"
                        value="{{ old('amount') }}"
                        min="1000"
                        required
                        placeholder="Atau masukkan nominal sendiri"
                        class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                    >
                </div>
                <p class="text-xs text-gray-500 mt-1">Minimal donasi Rp 10.000</p>
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold py-4 rounded-lg hover:from-purple-700 hover:to-pink-700 transform hover:scale-[1.02] transition shadow-lg"
            >
                💳 Lanjut ke Pembayaran
            </button>

            <!-- Payment Info -->
            <div class="flex items-center justify-center space-x-2 text-sm text-gray-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <span>Pembayaran aman dengan QRIS</span>
            </div>
        </form>
    </div>

    <!-- Info Section -->
    <div class="mt-8 bg-white rounded-xl shadow-md p-6">
        <h3 class="font-bold text-gray-800 mb-3">📱 Cara Pembayaran:</h3>
        <ol class="list-decimal list-inside space-y-2 text-sm text-gray-600">
            <li>Isi form donasi di atas</li>
            <li>Klik tombol "Lanjut ke Pembayaran"</li>
            <li>Scan QRIS yang muncul dengan aplikasi e-wallet kamu (GoPay, OVO, Dana, ShopeePay, dll)</li>
            <li>Konfirmasi pembayaran di aplikasi e-wallet</li>
            <li>Selesai! Donasi kamu akan otomatis tercatat</li>
        </ol>
    </div>
</div>

<script>
    function setAmount(amount) {
        document.getElementById('amount').value = amount;

        // Highlight selected button
        document.querySelectorAll('.amount-btn').forEach(btn => {
            btn.classList.remove('border-purple-500', 'bg-purple-50');
            btn.classList.add('border-gray-200');
        });

        event.target.classList.remove('border-gray-200');
        event.target.classList.add('border-purple-500', 'bg-purple-50');
    }
</script>
@endsection
