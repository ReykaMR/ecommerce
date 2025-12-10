# E-Commerce
E-Commerce ini adalah solusi belanja online. Website ini mendukung beberapa proses e-commerce mulai dari pencarian produk, manajemen keranjang, checkout, hingga manajemen pesanan dan inventori.

## ✨ Fitur Utama
- Untuk Pelanggan:
	- ✅ Registrasi dan login user
	- ✅ Pencarian dan filter produk
	- ✅ Keranjang belanja dinamis
	- ✅ Sistem wishlist/favorit
	- ✅ Checkout dan pembayaran
	- ✅ Riwayat pesanan
	- ✅ Upload bukti pembayaran
	- ✅ Profil pengguna
 - Untuk Administrator:
	- ✅ Dashboard admin
	- ✅ Manajemen produk (CRUD)
	- ✅ Manajemen kategori
	- ✅ Manajemen pesanan
	- ✅ Manajemen user
	- ✅ Sistem gambar produk (multiple images)
	- ✅ Konfirmasi pembayaran
	- ✅ Statistik penjualan

## 🛠 Teknologi yang Digunakan
- Backend: CodeIgniter 3
- Frontend: Bootstrap 5, jQuery
- Database: MySQL
- Icons: Font Awesome
- Server: Apache (XAMPP/Laragon)

## 📌 Requirement
- PHP 7.4+
- MySQL 5.7+
- Apache 2.4+
- XAMPP / Laragon (disarankan)

## 📦 Instalasi
1. Clone Repository

```bash
git clone https://github.com/ReykaMR/ecommerce.git
cd ecommerce
```

2. Setup Database

- Buat database baru
```sql
CREATE DATABASE ecommerce_db;
```
- Kemudian import file SQL yang disediakan (ecommerce_db.sql)

3. Edit konfigurasi database

Edit file berikut di application/config/database.php:
```php
<?php
$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
	'dsn'	=> '',
	'hostname' => 'localhost',
	'username' => 'your_username', // Seuaikan dengan username anda
	'password' => 'your_password', // Sesuaikan dengan password anda
	'database' => 'ecommerce_db',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);
```

4. Konfigurasi Base URL

Edit application/config/config.php menjadi seperti berikut:
```php
$config['base_url'] = 'http://localhost/ecommerce/';
```

5. Jalankan Aplikasi

Akses melalui browser:
```
http://localhost/ecommerce/
```
