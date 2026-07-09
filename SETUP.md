# POS Application

Sistem Point of Sale (POS) berbasis Laravel untuk manajemen transaksi, produk, stok, dan laporan.

---

## Fitur Utama

- **Point of Sale** — Transaksi cepat dengan keranjang dinamis, catatan item, kontrol jumlah
- **Manajemen Produk** — CRUD produk dengan gambar, diskon, stok, kategori
- **Manajemen Kategori** — Pengelompokan produk
- **Manajemen Stok** — Restock dan opname dengan riwayat pergerakan
- **Transaksi** — Riwayat transaksi, void, cetak struk (HTML & PDF)
- **Laporan** — Penjualan, produk terlaris, keuntungan, export Excel/PDF
- **Multi Role** — Owner, Admin, Kasir dengan hak akses berbeda
- **Pengaturan Toko** — Nama, alamat, pajak, logo, metode pembayaran, footer struk
- **Cetak Struk** — Format thermal printer (58mm) + download PDF

---

## Prasyarat

| Software | Versi |
|----------|-------|
| PHP | 8.2+ |
| Composer | 2.x |
| Node.js | 18+ |
| NPM | 9+ |
| Database | MySQL 8+ atau SQLite |
| Web Server | XAMPP / Laragon / Built-in PHP |

---

## Instalasi

### 1. Clone / Copy Project

```bash
cd C:\xampp\htdocs
# Clone atau copy folder project ke direktori ini
cd pos-app
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
npm install
```

### 4. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

### 5. Konfigurasi Database

**Opsi A — SQLite (Rekomendasi untuk dev):**

Buat file database kosong:
```bash
touch database/database.sqlite
```

Pastikan di `.env`:
```
DB_CONNECTION=sqlite
```

**Opsi B — MySQL:**

Buat database baru, lalu edit `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pos_app
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Jalankan Migration & Seeder

```bash
php artisan migrate --seed
```

Ini akan membuat:
- Semua tabel database
- User: Owner, Admin, Kasir
- Kategori: Makanan, Minuman, Snack, Lainnya
- Produk contoh: 12 item
- Pengaturan toko default

### 7. Buat Storage Link

```bash
php artisan storage:link
```

### 8. Build Frontend Assets

```bash
npm run build
```

Untuk development (hot reload):
```bash
npm run dev
```

### 9. Jalankan Server

```bash
php artisan serve
```

Buka: **http://localhost:8000**

---

## Akun Default

| Role | Email | Password | Akses |
|------|-------|----------|-------|
| Owner | owner@pos.test | password | Semua menu |
| Admin | admin@pos.test | password | Produk, Kategori, Stok, Laporan, POS, Void |
| Kasir | kasir@pos.test | password | POS saja |

---

## Struktur Folder

```
pos-app/
├── app/
│   ├── Http/Controllers/    # Controller
│   ├── Models/              # Model Eloquent
│   └── Providers/           # Service Provider
├── database/
│   ├── migrations/          # Struktur database
│   └── seeders/             # Data awal
├── resources/
│   ├── css/                 # Custom CSS (Tailwind)
│   ├── sass/                # SCSS
│   ├── js/                  # JavaScript
│   └── views/
│       ├── layouts/         # Layout utama
│       ├── pos/             # Halaman POS
│       ├── products/        # CRUD Produk
│       ├── categories/      # CRUD Kategori
│       ├── transactions/    # Riwayat & Struk
│       ├── stock/           # Restock & Opname
│       ├── reports/         # Laporan
│       ├── settings/        # Pengaturan Toko
│       └── users/           # Manajemen User
├── routes/
│   └── web.php              # Route aplikasi
├── print-service/           # (Opsional) Print service Node.js
└── vite.config.js           # Vite config
```

---

## Fitur Cetak Struk

### Via Browser (Default)
Setelah transaksi berhasil, klik **"Cetak Struk"** → jendela baru terbuka → klik tombol cetak → pilih printer.

### Download PDF
Buka halaman struk transaksi → klik **"Download PDF"**.

### Print Service (Opsional)
Jika ingin mencetak langsung ke printer thermal, jalankan print service di folder `print-service/` (lihat README di dalamnya).

---

## URL Halaman

| Halaman | URL |
|---------|-----|
| Login | `/login` |
| Dashboard | `/dashboard` |
| POS | `/pos` |
| Produk | `/products` |
| Kategori | `/categories` |
| Transaksi | `/transactions` |
| Stok | `/stock` |
| Laporan | `/reports` |
| Pengaturan | `/settings` |
| User | `/users` |

---

## Tech Stack

- **Backend**: Laravel 11, PHP 8.2
- **Frontend**: Blade Templates, Tailwind CSS 4, Vite
- **Database**: MySQL / SQLite
- **Auth**: Laravel Auth + Spatie Permission
- **PDF**: Barvdvdh DomPDF
- **Icons**: Heroicons (SVG)

---

## Troubleshooting

### Migration gagal
```bash
php artisan migrate:fresh --seed
```

### Asset tidak tampil
```bash
npm run build
```

### Storage link error
```bash
php artisan storage:link --force
```

### Cache bermasalah
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```
