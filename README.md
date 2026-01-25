<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Tentang Proyek: SmartDoorz (Sistem Manajemen Akses Pintu Digital)

Proyek SmartDoorz adalah aplikasi berbasis web yang dirancang untuk sistem manajemen akses pintu digital menggunakan teknologi QR Code sebagai metode autentikasi utama. Sistem ini dikembangkan untuk menggantikan penggunaan kunci fisik konvensional dengan solusi yang lebih efisien, aman, dan mudah dikelola, khususnya untuk lingkungan kos-kosan atau apartemen.

Aplikasi ini berfungsi sebagai platform manajemen terpusat yang memungkinkan administrator untuk mengelola hak akses pengguna secara *real-time*, memantau aktivitas akses ruangan, dan mengintegrasikan sistem dengan perangkat keras IoT untuk kontrol pintu otomatis.

### Fitur Utama

Berikut adalah fitur-fitur kunci yang dikembangkan dalam sistem SmartDoorz:

- **Autentikasi QR Code**: Menggunakan kode QR unik sebagai kunci digital untuk membuka pintu, menggantikan kunci fisik konvensional
- **Manajemen Ruangan**: Admin dapat mengelola data ruangan, status ketersediaan, dan mengatur penghuni ruangan
- **Sistem Rental**: Pengelolaan masa sewa ruangan dengan tracking tanggal mulai dan berakhir sewa
- **Dashboard Analytics**: Antarmuka visual dengan statistik real-time untuk monitoring sistem secara menyeluruh
- **Log Aktivitas**: Pencatatan lengkap semua aktivitas akses pintu dengan timestamp dan status keberhasilan
- **Manajemen User**: Sistem multi-role dengan pembedaan admin dan user biasa
- **QR Code Generator**: Pembuatan otomatis kode QR unik untuk setiap ruangan
- **Status Monitoring**: Tracking status ruangan (tersedia, ditempati, expired, delay, maintenance)
- **API Scanner**: Endpoint untuk integrasi dengan perangkat scanner QR Code
- **Email Notifications**: Sistem notifikasi email menggunakan Brevo service

### Arsitektur Sistem

Sistem SmartDoorz dibangun dengan arsitektur yang modular dan scalable:

#### Models & Database
- **User**: Manajemen pengguna dengan role admin/user dan data rental
- **Room**: Data ruangan dengan QR code dan status
- **DoorAccessLog**: Log aktivitas akses pintu
- **Barcode**: Manajemen kode QR
- **DashboardStat**: Statistik dashboard

#### Controllers & Services
- **QrScannerController**: Handling scan QR code dan validasi akses
- **RoomController**: CRUD operasi ruangan dan QR code management
- **UserController**: Manajemen pengguna dan rental
- **BrevoService**: Integrasi email service
- **QrCodeGenerator**: Helper untuk generate QR code

### Teknologi yang Digunakan

- **Framework Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Blade Templates dengan Tailwind CSS
- **Database**: MySQL/SQLite
- **QR Code**: API eksternal (qr-server.com)
- **Testing**: Pest PHP
- **Build Tools**: Vite, NPM
- **Authentication**: Laravel Breeze

### Instalasi & Setup

1. **Clone Repository**
   ```bash
   git clone <repository-url>
   cd SmartDoorz
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Build Assets**
   ```bash
   npm run build
   ```

6. **Run Application**
   ```bash
   php artisan serve
   ```

### API Endpoints

- `POST /api/scanner/scan` - Public endpoint untuk scan QR code
- `POST /scanner/scan` - Authenticated scan endpoint
- `POST /rental/extend` - Extend rental period
- `POST /barcode/{user}/send-link` - Send QR code via email

### Fitur Keamanan

- **CSRF Protection**: Perlindungan terhadap Cross-Site Request Forgery
- **Authentication**: Sistem login dengan verifikasi email
- **Authorization**: Role-based access control (Admin/User)
- **QR Code Validation**: Validasi kode QR dengan format khusus
- **Access Logging**: Pencatatan semua aktivitas akses untuk audit trail

### Struktur Database

#### Users Table
- Personal information (name, email, phone)
- Rental data (start_date, end_date, monthly_fee)
- Status tracking (active, delay)
- Admin privileges

#### Rooms Table
- Room identification (room_number)
- QR code assignment
- User assignment
- Status management

#### Door Access Logs Table
- Access attempts logging
- Success/failure tracking
- IP address and user agent logging
- Timestamp recording

### Pengembangan Lebih Lanjut

Sistem ini dapat dikembangkan lebih lanjut dengan:
- Integrasi dengan perangkat keras IoT (ESP32/Arduino)
- Mobile application untuk user
- Push notifications
- Advanced analytics dan reporting
- Integration dengan sistem pembayaran
- Facial recognition sebagai backup authentication

## Identitas Pengembang

- Daffa Ramadhan
- Moch Farizal T.G


## Lisensi

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
