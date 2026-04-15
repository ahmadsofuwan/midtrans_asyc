# Midtrans CSV Manager Pro

Sistem manajemen laporan transaksi Midtrans berbasis Laravel yang dirancang untuk efisiensi, kecepatan, dan kemudahan penggunaan. Sistem ini mendukung pengelolaan multi-perusahaan dengan fitur import CSV yang cerdas dan antarmuka premium.

## 🚀 Fitur Utama

- **Premium UI/UX**: Desain modern menggunakan font 'Outfit', Glassmorphism, dan navigasi yang intuitif.
- **Username-Based Authentication**: Login aman hanya menggunakan username dan password.
- **Multi-Company Management**: Kelola transaksi dari berbagai perusahaan dalam satu platform.
- **Advanced CSV Importer**:
    - Mendukung _Upsert_ (Update or Insert) otomatis berdasarkan Order ID.
    - Fitur **Drag & Drop** area unggah.
    - Validasi pemilihan perusahaan sebelum import.
- **Yajra Server-Side DataTables**: Penanganan ribuan data transaksi dengan performa tinggi dan pencarian instan.
- **Filter Tanggal Akurat**: Saring data berdasarkan rentang waktu `Settlement Time` yang presisi.
- **Pro Export**: Ekspor data kembali ke format laporan Midtrans sesuai filter yang aktif.
- **User Management**: Kelola akun pengguna sistem langsung dari dashboard.

## 🛠️ Tech Stack

- **Framework**: Laravel 11
- **PHP**: 8.3+
- **Database**: SQLite (Default) / MySQL
- **Library**:
    - `yajra/laravel-datatables-oracle` (Server-side processing)
    - `nesbot/carbon` (Date manipulation)
    - `Bootstrap 5` (Styling)

## 📥 Instalasi

Ikuti langkah-langkah berikut untuk menjalankan project di lokal Anda:

1. **Clone Repository**

    ```bash
    git clone https://github.com/ahmadsofuwan/midtrans_asyc.git
    cd midtrans_asyc
    ```

2. **Install Dependensi**

    ```bash
    composer install
    npm install && npm run build
    ```

3. **Konfigurasi Environment**
   Salin file `.env.example` menjadi `.env` dan sesuaikan pengaturan database Anda.

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. **Siapkan Database**
   Jalankan migrasi dan seeder untuk membuat tabel dan akun admin default.

    ```bash
    php artisan migrate:fresh --seed
    ```

5. **Jalankan Aplikasi**
    ```bash
    php artisan serve
    ```
    Akses di: `http://127.0.0.1:8000`

## 🔐 Akun Login Default

Gunakan kredensial berikut untuk masuk pertama kali:

- **Username**: `admin`
- **Password**: `password`

## 📂 Struktur Penting

- `app/Http/Controllers/TransactionController.php`: Logika utama import, export, dan datatables.
- `app/Http/Controllers/CompanyController.php`: Manajemen data perusahaan.
- `app/Http/Controllers/UserController.php`: Manajemen akun sistem.
- `database/migrations/`: Skema database terstruktur.
- `resources/views/`: Antarmuka modern menggunakan Blade Templates.

## 📝 Catatan Tambahan

- Pastikan kolom pada file CSV yang diunggah sesuai dengan header standar laporan Midtrans (Order ID, Status, Amount, dll).
- Untuk mengubah database ke MySQL, sesuaikan file `.env` dan jalankan migrasi ulang.

---

Dikembangkan dengan ❤️ untuk kemudahan rekonsiliasi pembayaran.
