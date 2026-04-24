# E-Commerce Inventory API

API Manajemen Inventaris E-Commerce modern yang dibangun dengan Laravel 11. API ini dilengkapi dengan fitur autentikasi berbasis JWT, dan *endpoint* manajemen inventaris yang dinamis.

## Persyaratan (Requirements)

Silakan merujuk ke [requirements.txt](requirements.txt) untuk melihat daftar lengkap persyaratan sistem dan dependensi aplikasi.

## Cara Menjalankan Aplikasi

Ikuti langkah-langkah berikut untuk mengatur dan menjalankan aplikasi di komputer lokal Anda:

1. **Instal Dependensi**
   Buka terminal di direktori proyek dan instal paket PHP yang diperlukan:
   ```bash
   composer install
   ```

2. **Konfigurasi Environment**
   Salin file *environment* contoh dan perbarui konfigurasi database Anda:
   ```bash
   cp .env.example .env
   ```
   *Pastikan untuk mengatur `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` di dalam file `.env` agar sesuai dengan server lokal Anda.*

3. **Buat Key Aplikasi**
   Buat *key* aplikasi dan *secret key* untuk JWT:
   ```bash
   php artisan key:generate
   php artisan jwt:secret
   ```

4. **Migrasi & Seeding Database**
   Jalankan migrasi untuk membuat tabel database dan mengisinya dengan data awal (misalnya: data pengguna admin dan role default):
   ```bash
   php artisan migrate --seed
   ```

5. **Jalankan Server Lokal**
   Jalankan server *development* Laravel:
   ```bash
   php artisan serve
   ```
   API sekarang dapat diakses melalui `http://localhost:8000`.

## Penggunaan API & Pengujian

API ini menggunakan **JSON Web Tokens (JWT)** untuk autentikasi. Untuk mengakses *endpoint* yang dilindungi (*protected endpoints*), Anda harus melakukan autentikasi terlebih dahulu melalui *route login* untuk mendapatkan token, kemudian menyertakan token tersebut di dalam *header* permintaan HTTP Anda:
`Authorization: Bearer <token_jwt_anda>`

### Koleksi Postman

Untuk mempermudah proses pengujian, sebuah koleksi Postman yang telah dikonfigurasi sepenuhnya telah disertakan di dalam repositori ini. Koleksi ini berisi semua *route* API, *body* permintaan, dan *header* yang diperlukan untuk berinteraksi dengan sistem.

- **File Koleksi:** [`postman/E-Commerce API.postman_collection.json`](postman/E-Commerce%20API.postman_collection.json)

Impor file ini ke dalam *workspace* Postman Anda untuk mulai mengirim permintaan ke server lokal dengan cepat.

## Video Dokumentasi

Untuk panduan lengkap mengenai aplikasi, fitur-fitur yang tersedia, dan cara penggunaan API, silakan tonton video dokumentasi di bawah ini:

🎥 [**Tonton Video Dokumentasi Di Sini**](https://drive.google.com/file/d/1fP-e5kHBTvDrZ7pAdx60aQ3KaMfB3KiE/view?usp=sharing)
