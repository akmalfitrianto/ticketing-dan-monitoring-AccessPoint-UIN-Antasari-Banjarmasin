# MAHARAGU

![Laravel](https://img.shields.io/badge/Laravel-12.x-red)
![PHP](https://img.shields.io/badge/PHP-8.2-blue)
![MySQL](https://img.shields.io/badge/Database-MySQL-orange)
![Tailwind](https://img.shields.io/badge/Tailwind-3.x-38bdf8)

**MAHARAGU (Monitoring Access Point & Handling Rangkaian Gangguan Umum)** adalah aplikasi berbasis web yang dirancang khusus untuk **UIN Antasari Banjarmasin**. Aplikasi ini berfungsi untuk memetakan, memantau, dan mengelola inventaris jaringan (Access Point) secara visual.

Aplikasi ini mengintegrasikan sistem pelaporan gangguan (*ticketing*) dengan visualisasi denah kampus interaktif, memudahkan tim IT dalam mendeteksi dan menangani lokasi gangguan jaringan.

##  Fitur Unggulan

###  Visualisasi & Pemetaan (Interactive Map)
* **Denah Kampus Visual:** Visualisasi lokasi gedung dalam format SVG yang interaktif.
* **Status Warna Access-Points:** Gedung pada peta memiliki indikator warna berdasarkan kesehatan perangkat di dalamnya (Custom Color Identity dengan indikator status).
* **Detail Lantai & Ruangan:** Drill-down dari gedung ke denah lantai untuk melihat posisi spesifik Access Point.

###  Manajemen Tiket (Ticketing Flow)
Sistem otomatis mengubah status perangkat berdasarkan siklus hidup tiket:
1.  **Ticket Open**  Status AP otomatis menjadi **Bermasalah** (Merah).
2.  **In Progress**  Status AP otomatis menjadi **Maintenance** (Kuning).
3.  **Resolved/Closed**  Status AP kembali **Normal** (Hijau).

###  Manajemen Data
* CRUD Data Gedung, Lantai, dan Ruangan.
* Manajemen Inventaris Access Point.
* Role-Based Access Control (Superadmin & Admin).

##  Screenshots

Berikut adalah tampilan visualisasi denah pada aplikasi:

| Denah Kampus Utama | Denah Detail Lantai |
|:---:|:---:|
| ![Peta Kampus](public/images/halaman-peta.png) | ![Peta Lantai](public/images/peta-ruangan.png) |
| *Visualisasi sebaran gedung UIN Antasari* | *Posisi Access Point pada ruangan* |



##  Teknologi yang Digunakan

* **Backend:** Laravel 12 (PHP 8.2)
* **Frontend:** Blade Templates, Tailwind CSS
* **Interactivity:** Alpine.js (Untuk fitur Drag & Drop peta dan Modal)
* **Database:** MySQL
* **Icons:** Heroicons

##  Cara Instalasi (Localhost)

Ikuti langkah-langkah berikut untuk menjalankan project di komputer lokal:

### Prasyarat
* PHP >= 8.2
* Composer
* MySQL
* Node.js & NPM

### Langkah-langkah

1.  **Clone Repository**
    ```bash
    git clone https://github.com/akmalfitrianto/MAHARAGU.git
    cd MAHARAGU
    ```

2.  **Install Dependencies**
    ```bash
    composer install
    npm install
    ```

3.  **Konfigurasi Environment**
    Duplikasi file `.env.example` menjadi `.env`:
    ```bash
    cp .env.example .env
    ```
    Buka file `.env` dan sesuaikan konfigurasi database Anda:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nama_database_anda
    DB_USERNAME=root
    DB_PASSWORD=
    ```

4.  **Generate App Key**
    ```bash
    php artisan key:generate
    ```

5.  **Migrasi & Seeding Database**
    Jalankan perintah ini untuk membuat tabel dan mengisi akun default:
    ```bash
    php artisan migrate --seed
    ```

6.  **Jalankan Aplikasi**
    Buka dua terminal terpisah untuk menjalankan server dan build assets:
    
    *Terminal 1:*
    ```bash
    php artisan serve
    ```
    
    *Terminal 2:*
    ```bash
    npm run dev
    ```

7.  **Selesai!**
    Buka browser dan akses `http://127.0.0.1:8000`.

##  Akun Default

Gunakan akun Superadmin berikut untuk login pertama kali:

* **Email:** `superadmin@uin.ac.id`
* **Password:** `password`

##  Kontribusi & Pengembangan

Aplikasi ini dikembangkan sebagai bagian dari PKL di UIN Antasari Banjarmasin.

## Rencana Pengembangan Lanjutan Sistem

Aplikasi ini akan dikembangkan lebih lanjut agar status Access Point bisa update secara real-time.

##  Lisensi

[MIT license](https://opensource.org/licenses/MIT).