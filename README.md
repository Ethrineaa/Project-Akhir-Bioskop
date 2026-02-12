

---

<h1 align="center">🎬 PROJECT AKHIR - SISTEM BIOSKOP</h1>

<p align="center">
  <em>Transforming Cinema Experience Through Innovation and Excellence</em>
</p>

---

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-Framework-red?style=for-the-badge&logo=laravel" />
  <img src="https://img.shields.io/badge/Midtrans-Payment-blue?style=for-the-badge" />
  <img src="https://img.shields.io/badge/Vite-Build-purple?style=for-the-badge&logo=vite" />
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" />
  <img src="https://img.shields.io/badge/License-Educational-green?style=for-the-badge" />
</p>

---

## 📌 Tentang Project

**PROJECT AKHIR BIOSKOP** adalah sistem booking tiket bioskop berbasis **Laravel** yang dilengkapi dengan fitur lengkap untuk Admin dan User, serta terintegrasi dengan **Midtrans Payment Gateway**.

### ✨ Fitur Utama

* 🎬 Manajemen Film
* 🪑 Manajemen Studio & Kursi
* 📅 Manajemen Jadwal Tayang
* 🛒 Sistem Pemesanan Tiket
* 💳 Integrasi Midtrans (Payment Gateway)
* 📊 Dashboard Admin & Laporan Penjualan
* 🔐 Sistem Role (Admin & User)

---

# ⚙️ Cara Menjalankan Project

## 1️⃣ Clone Repository

```bash
git clone https://github.com/USERNAME/REPOSITORY.git
cd REPOSITORY
```

---

## 2️⃣ Install Dependency

```bash
composer install
npm install
```

---

## 3️⃣ Setup Environment

Copy file `.env.example`:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

---

## 4️⃣ Setup Database

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_DATABASE=nama_database
DB_USERNAME=root
DB_PASSWORD=
```

Lalu jalankan migration & seeder:

```bash
php artisan migrate --seed
```

---

# 💳 Konfigurasi Midtrans

Tambahkan konfigurasi berikut ke file `.env`:

```env
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false
```

🔗 Server Key & Client Key bisa didapat dari:
[https://dashboard.midtrans.com/](https://dashboard.midtrans.com/)

---

# 🌍 Menggunakan Ngrok (WAJIB untuk Webhook Midtrans)

Karena Midtrans membutuhkan URL publik untuk mengirim notifikasi pembayaran, maka kita harus menggunakan **Ngrok**.

---

## 1️⃣ Jalankan Laravel

```bash
php artisan serve
```

Default berjalan di:

```
http://127.0.0.1:8000
```

---

## 2️⃣ Install Ngrok

Download di:
[https://ngrok.com/download](https://ngrok.com/download)

Login dan set authtoken:

```bash
ngrok config add-authtoken TOKEN_KAMU
```

---

## 3️⃣ Jalankan Ngrok

```bash
ngrok http 8000
```

Contoh output:

```
Forwarding  https://abcd-1234.ngrok-free.app -> http://localhost:8000
```

Salin URL HTTPS tersebut.

---

## 4️⃣ Update APP_URL

Edit file `.env`:

```env
APP_URL=https://abcd-1234.ngrok-free.app
```

Lalu clear config:

```bash
php artisan config:clear
php artisan cache:clear
```

---

## 5️⃣ Update Notification URL di Midtrans

Masuk ke:

Dashboard Midtrans → Settings → Configuration

Isi **Payment Notification URL** dengan:

```
https://abcd-1234.ngrok-free.app/midtrans/callback
```

(Sesuaikan dengan route callback di project Anda)

---

# 🔁 Urutan Menjalankan Project (Saat Testing Pembayaran)

Setiap kali ingin testing:

```bash
php artisan serve
ngrok http 8000
```

⚠ Jika URL Ngrok berubah, wajib update:

* `APP_URL` di `.env`
* Notification URL di Dashboard Midtrans

---

# 🧪 Mode Testing

Pastikan menggunakan:

```env
MIDTRANS_IS_PRODUCTION=false
```

Gunakan kartu testing dari dokumentasi Midtrans Sandbox.

---

# 👨‍💻 Role Sistem

## 👑 Admin

* Kelola Film
* Kelola Jadwal
* Kelola Studio & Kursi
* Lihat Laporan Penjualan
* Lihat Data Pemesanan

## 👤 User

* Register / Login
* Pilih Film & Jadwal
* Pilih Kursi
* Lakukan Pembayaran
* Lihat Status Pesanan

---

# 📌 Catatan Penting

* ❌ Jangan commit file `.env`
* ❌ Jangan gunakan Server Key Production di repository publik
* ✅ Pastikan webhook aktif agar status pembayaran otomatis berubah
* ✅ Gunakan sandbox saat development

---

<p align="center">
🚀 Built with Laravel & Midtrans Integration  
</p>

---

