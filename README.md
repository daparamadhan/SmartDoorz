<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Tentang Proyek: Smart Access (Sistem Kunci Pintu Digital)

[cite_start]Proyek ini adalah aplikasi berbasis web yang dirancang untuk sistem **Smart Access**, sebuah inovasi sistem kunci pintu digital yang menggunakan pemindai **QR Code** sebagai metode autentikasi utama[cite: 7]. [cite_start]Sistem ini dibangun untuk menggantikan penggunaan kunci fisik konvensional dengan solusi yang lebih efisien, aman, dan mudah dikelola[cite: 15].

[cite_start]Aplikasi ini berfungsi sebagai antarmuka manajemen (dashboard) yang memungkinkan administrator untuk mengelola hak akses pengguna secara *real-time*, memantau aktivitas pintu, dan menghubungkan logika perangkat lunak dengan perangkat keras (mikrokontroler/solenoid lock)[cite: 15, 29].

### Fitur Utama

Berikut adalah fitur-fitur kunci yang dikembangkan dalam sistem ini untuk menjawab kebutuhan keamanan dan efisiensi akses:

- [cite_start]**Autentikasi QR Code**: Menggunakan kode QR sebagai kunci digital untuk membuka pintu, menggantikan kunci fisik, kartu RFID, atau PIN[cite: 10, 15].
- [cite_start]**Manajemen Akses Terpusat**: Admin dapat menambah, menghapus, dan memodifikasi hak akses pengguna melalui antarmuka website yang *user-friendly*[cite: 15, 27].
- [cite_start]**Monitoring Real-Time**: Menyediakan log aktivitas untuk memantau siapa yang mengakses pintu dan kapan akses tersebut dilakukan[cite: 15, 27].
- [cite_start]**Dashboard Admin**: Antarmuka visual untuk pengelolaan sistem secara menyeluruh, mengatasi kesenjangan penelitian sebelumnya yang hanya fokus pada akses *single-user*[cite: 26, 27].
- [cite_start]**Integrasi IoT**: Dirancang untuk berkomunikasi dengan mikrokontroler (seperti Raspberry Pi atau ESP32) guna menggerakkan *solenoid lock*[cite: 29].

### Latar Belakang Masalah

[cite_start]Sistem ini dikembangkan untuk mengatasi keterbatasan kunci konvensional dan meningkatkan keamanan di lingkungan seperti perkantoran, area residensial (rumah/kos), fasilitas pendidikan (perpustakaan), atau ruang loker[cite: 15]. [cite_start]Kajian literatur menunjukkan adanya tren peralihan dari kunci fisik ke autentikasi digital, namun masih kurangnya solusi yang menawarkan manajemen akses terpusat yang komprehensif berbasis website[cite: 21, 24].

### Teknologi yang Digunakan

- **Framework Backend**: Laravel (PHP).
- [cite_start]**Metode Autentikasi**: QR Code Generator & Scanner[cite: 28].
- [cite_start]**Perangkat Keras Pendukung**: Mikrokontroler (Arduino/Raspberry Pi) & Solenoid Lock[cite: 29].

## Identitas Pengembang

- [cite_start]**Nama**: Daffa Ramadhan [cite: 4]
- [cite_start]**NIM**: 714240018 [cite: 4]
- [cite_start]**Program Studi**: D4 TI ULBI [cite: 5]
- [cite_start]**Kelas**: 2B [cite: 5]

## Lisensi

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
