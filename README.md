# 🎁 Laravel QRIS Donation Platform

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![Midtrans](https://img.shields.io/badge/Midtrans-QRIS-green.svg)](https://midtrans.com)

Platform donasi sederhana yang terinspirasi dari Saweria, dibangun dengan Laravel 12 dan terintegrasi dengan Midtrans Payment Gateway untuk pembayaran QRIS.

## ✨ Fitur

- 💳 **Pembayaran QRIS** - Integrasi lengkap dengan Midtrans QRIS
- 👤 **Form Donasi** - Interface sederhana dan user-friendly
- 📊 **Dashboard Donasi** - Lihat semua donasi yang masuk
- 🔔 **Webhook Handler** - Notifikasi otomatis dari Midtrans
- ⚡ **Real-time Status** - Polling status pembayaran
- 🎨 **Modern UI** - Design menarik dengan Tailwind CSS
- 📱 **Responsive** - Tampilan optimal di semua perangkat

## 🛠️ Tech Stack

- **Framework**: Laravel 12
- **Database**: SQLite (bisa diganti MySQL/PostgreSQL)
- **Payment Gateway**: Midtrans QRIS
- **Frontend**: Tailwind CSS
- **PHP**: 8.2+

## 📋 Requirements

- PHP 8.2 atau lebih tinggi
- Composer
- SQLite/MySQL/PostgreSQL
- Akun Midtrans (Sandbox untuk testing)
- Ngrok (untuk webhook testing di localhost)

## 🚀 Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/yourusername/saweria-clone-laravel.git
cd saweria-clone-laravel
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Setup Environment

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Konfigurasi Database

Edit file `.env`:

```env
DB_CONNECTION=sqlite
# Atau gunakan MySQL
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=saweria_clone
# DB_USERNAME=root
# DB_PASSWORD=
```

Untuk SQLite, buat file database:

```bash
touch database/database.sqlite
```

### 5. Konfigurasi Midtrans

Daftar di [Midtrans](https://dashboard.midtrans.com) dan dapatkan kredensial Sandbox.

Edit file `.env`:

```env
MIDTRANS_SERVER_KEY=your-server-key
MIDTRANS_CLIENT_KEY=your-client-key
MIDTRANS_MERCHANT_ID=your-merchant-id
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

### 6. Setup Ngrok (untuk Webhook)

Jalankan ngrok:

```bash
ngrok http 8000
```

Copy URL yang diberikan dan tambahkan ke `.env`:

```env
NGROK_URL=https://your-ngrok-url.ngrok-free.app
```

### 7. Migrasi Database

```bash
php artisan migrate
```

### 8. Jalankan Aplikasi

```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

## 🔧 Konfigurasi Webhook Midtrans

1. Login ke [Midtrans Dashboard](https://dashboard.midtrans.com)
2. Pilih environment **Sandbox**
3. Masuk ke menu **Settings** → **Configuration**
4. Set **Payment Notification URL** ke:
   ```
   https://your-ngrok-url.ngrok-free.app/api/midtrans/notification
   ```
5. Simpan perubahan

## 📖 Cara Penggunaan

### Membuat Donasi

1. Akses halaman utama (`/`)
2. Isi form donasi:
   - Nama donatur
   - Pesan (opsional)
   - Nominal donasi (minimal Rp 10.000)
3. Klik "Lanjut ke Pembayaran"
4. Scan QRIS code dengan aplikasi e-wallet
5. Selesaikan pembayaran
6. Status akan otomatis terupdate

### Melihat Daftar Donasi

Akses `/donations` untuk melihat semua donasi yang masuk beserta statusnya.

## 🗂️ Struktur Project

```
Laravel QRIS Donation Platform/
├── app/
│   ├── Http/Controllers/
│   │   └── PaymentController.php    # Handle payment logic
│   └── Models/
│       └── Donation.php              # Model donasi
├── database/
│   └── migrations/
│       └── create_donations_table.php
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php         # Layout utama
│       └── donation/
│           ├── index.blade.php       # Form donasi
│           ├── qris.blade.php        # Halaman QRIS
│           ├── success.blade.php     # Halaman sukses
│           └── list.blade.php        # Daftar donasi
├── routes/
│   └── web.php                       # Route definitions
└── config/
    └── services.php                  # Midtrans config
```

## 🔐 Security Notes

**PENTING**: Aplikasi ini menonaktifkan beberapa middleware untuk demo purposes:

```php
// Di bootstrap/app.php
$middleware->remove([
    \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
    // ...
]);
```

**Untuk Production**:
1. Aktifkan kembali CSRF protection
2. Aktifkan session dan cookie encryption
3. Gunakan HTTPS
4. Set `MIDTRANS_IS_PRODUCTION=true`
5. Gunakan kredensial Production Midtrans

## 🧪 Testing

### Testing dengan Midtrans Sandbox

Gunakan test card/e-wallet yang disediakan Midtrans:
- **GoPay**: Simulasi scan QRIS di Midtrans Dashboard
- **QRIS**: Gunakan simulator QRIS Midtrans

Dokumentasi: [Midtrans Testing](https://docs.midtrans.com/en/technical-reference/sandbox-test)

## 📚 API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/` | Halaman form donasi |
| POST | `/donate` | Proses pembuatan transaksi |
| GET | `/donation/status/{orderId}` | Cek status pembayaran |
| GET | `/donation/success/{orderId}` | Halaman sukses |
| GET | `/donations` | Daftar semua donasi |
| POST | `/api/midtrans/notification` | Webhook dari Midtrans |

## 🐛 Troubleshooting

### Webhook tidak bekerja

1. Pastikan ngrok berjalan
2. Update URL ngrok di Midtrans Dashboard
3. Periksa log di `storage/logs/laravel.log`
4. Atau cek menggunakan teleskop

### QRIS tidak muncul

1. Periksa kredensial Midtrans
2. Pastikan menggunakan Server Key, bukan Client Key
3. Cek response dari Midtrans di log

### Payment status tidak terupdate

1. Pastikan webhook URL sudah benar
2. Test webhook manual dari Midtrans Dashboard
3. Periksa log notifikasi di database (`raw_notification`)


## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - The PHP Framework
- [Midtrans](https://midtrans.com) - Payment Gateway
- [Tailwind CSS](https://tailwindcss.com) - CSS Framework
- [Saweria](https://saweria.co) - Original inspiration


⭐ **Star this repository** jika project ini membantu Anda!
