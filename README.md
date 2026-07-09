# Point of Sale (POS) System

Sistem Point of Sale berbasis web untuk UMKM (Usaha Mikro, Kecil, dan Menengah). Dibangun menggunakan **Laravel 12** dengan antarmuka modern, responsif, dan mudah digunakan.

## Fitur Utama

### POS (Point of Sale)
- Pilih produk dari grid menu dengan pencarian & filter kategori
- Tambah catatan pada item pesanan
- Dine In & Takeaway
- Metode pembayaran: Tunai, QRIS, Kartu, Transfer, GoPay
- Diskon transaksi (Rp atau %)
- Pajak PPN & Service Charge otomatis
- Cetak struk thermal via browser print dialog
- Auto-refresh setelah transaksi selesai

### Manajemen Produk
- CRUD produk dengan gambar (otomatis konversi ke WebP)
- Kategori produk
- Diskon produk (persen/nominal)
- Validasi upload gambar (type: JPEG, PNG, GIF, WebP | max: 2MB)
- Auto-pause produk saat stok habis

### Manajemen Stok
- Restock barang masuk
- Stok opname (penyesuaian fisik)
- Riwayat pergerakan stok
- Alert stok menipis di dashboard

### Dashboard
- Statistik penjualan (hari ini, minggu ini, bulan ini)
- Grafik penjualan 7 hari terakhir
- Grafik penjualan 12 bulan terakhir
- Grafik metode pembayaran (filter: Hari/Minggu/Bulan/Tahun)
- Produk terlaris (filter: Hari/Minggu/Bulan/Tahun)
- Daftar produk yang dinonaktifkan otomatis (stok habis)
- Transaksi terbaru

### Laporan
- Laporan penjualan
- Laporan pendapatan (laba/rugi)
- Laporan produk terlaris
- Export ke Excel (maatwebsite/excel)
- Export ke PDF (barryvdh/laravel-dompdf)
- Filter rentang tanggal

### Manajemen Pengguna
- Role & Permission (Owner, Admin, Kasir)
- CRUD pengguna (Owner only)
- Login, Logout, Register

### Pengaturan
- Nama toko, alamat, telepon
- Logo toko (dengan upload & validasi)
- Pajak PPN & Service Charge (%)
- Metode pembayaran aktif
- Footer struk

### Loading Global
- Overlay loading spinner otomatis saat submit form
- Loading pada transaksi POS

## Tech Stack

| Komponen | Teknologi |
|----------|-----------|
| Backend | Laravel 12 (PHP 8.2+) |
| Frontend | Blade, Tailwind CSS, Chart.js |
| Database | MariaDB / MySQL |
| PDF | barryvdh/laravel-dompdf |
| Excel | maatwebsite/excel |
| Auth | Laravel UI + spatie/laravel-permission |

## Requirements

- PHP >= 8.2
- Composer
- MariaDB / MySQL
- XAMPP / Laragon / Valet

## Installation

1. **Clone repository**
   ```bash
   git clone https://github.com/shabianay/Point-of-Sale.git
   cd Point-of-Sale
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Copy environment file**
   ```bash
   cp .env.example .env
   ```

4. **Generate app key**
   ```bash
   php artisan key:generate
   ```

5. **Konfigurasi database** di file `.env`
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=pos_app
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Jalankan migrasi & seed**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

7. **Buat storage link**
   ```bash
   php artisan storage:link
   ```

8. **Jalankan server**
   ```bash
   php artisan serve
   ```

9. **Buka browser**
   ```
   http://localhost:8000
   ```

## Default Login

| Role | Email | Password |
|------|-------|----------|
| Owner | owner@pos.com | password |
| Admin | admin@pos.com | password |
| Kasir | kasir@pos.com | password |

## Struktur Folder

```
pos-app/
├── app/
│   ├── Exports/          # Export classes (Excel)
│   ├── Helpers/          # ImageHelper (WebP conversion)
│   ├── Http/Controllers/ # Controllers
│   └── Models/           # Eloquent Models
├── config/
│   └── payment.php       # Metode pembayaran
├── database/
│   ├── migrations/       # Database migrations
│   └── seeders/          # Database seeders
├── resources/
│   ├── css/              # Custom CSS
│   ├── sass/             # SCSS (variables, app)
│   └── views/
│       ├── auth/         # Login, register
│       ├── layouts/      # Layout utama
│       ├── pos/          # Halaman POS
│       ├── products/     # Manajemen produk
│       ├── reports/      # Laporan & export
│       ├── settings/     # Pengaturan toko
│       ├── stock/        # Manajemen stok
│       ├── transactions/ # Riwayat transaksi
│       └── users/        # Manajemen pengguna
├── routes/
│   └── web.php           # Routes
└── storage/              # File uploads
```

## Screenshot

- **POS**: Interface transaksi dengan grid produk, keranjang, dan metode pembayaran
- **Dashboard**: Statistik penjualan, grafik interaktif, dan alert stok
- **Laporan**: Export ke Excel & PDF dengan filter tanggal
- **Produk**: Manajemen produk dengan upload gambar & validasi

## License

MIT License
