# Sistem Presensi/Absensi RFID

Aplikasi Presensi/Absensi Siswa dan Guru berbasis kartu RFID menggunakan framework CodeIgniter 3 dan CSS Tailwind Modern.

## 🚀 Fitur Utama

### Sistem Absensi
- **Absensi Masuk/Pulang**: Menggunakan kartu RFID secara real-time
- **Absensi Per Mapel**: Input H/S/I/A saat guru mengisi jurnal
- **Notifikasi WA**: Dengan sistem antrian (queue) agar tidak blocking
- **Halaman RFID Scanner**: Real-time tanpa login

### Panel Admin
- Dashboard dengan statistik lengkap
- Pengaturan sekolah (nama, alamat, kepala sekolah, logo)
- Pengaturan hari kerja dan jam kerja
- Data Master (Tahun Ajaran, Semester, Kelas, Siswa, Guru)
- Naik kelas otomatis
- Import/Export Excel untuk data siswa dan guru
- Pengaturan notifikasi WhatsApp dengan sistem queue
- Laporan dan rekap absensi (PDF & Excel dengan kop sekolah)
- Mata Pelajaran & Jadwal Pelajaran
- Notifikasi otomatis untuk siswa yang belum absen

### Panel Guru
- Dashboard dengan jadwal dan jurnal hari ini
- Input jurnal mengajar dengan absensi siswa (H/S/I/A)
- Lihat jadwal mengajar
- Laporan kinerja
- Rekap jurnal dan absensi per mapel
- Profile management

### Panel Guru Wali Kelas
- Semua fitur guru + Input sakit/izin siswa di kelasnya

### Panel Guru Piket
- Semua fitur guru + Input izin siswa keluar/masuk saat KBM
- Rekap piket

### Panel BK (Bimbingan Konseling)
- Monitoring siswa alpha 3x dan terlambat 5x dalam sebulan
- Cetak surat pemanggilan
- Profile management

## 📋 Requirements

- PHP 7.2 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Apache/Nginx Web Server
- mod_rewrite enabled (untuk clean URL)

## 🔧 Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/apriansasyadrik/presensi-rfid.git
cd presensi-rfid
```

### 2. Konfigurasi Database

1. Buat database baru:
```sql
CREATE DATABASE presensi_rfid;
```

2. Import schema database:
```bash
mysql -u root -p presensi_rfid < database_schema.sql
```

3. Konfigurasi database di `application/config/database.php`:
```php
'hostname' => 'localhost',
'username' => 'root',
'password' => '',
'database' => 'presensi_rfid',
```

### 3. Konfigurasi Base URL

Edit file `application/config/config.php` (sudah di-setting auto-detect, tapi bisa disesuaikan):
```php
$config['base_url'] = 'http://localhost/presensi-rfid/';
```

### 4. Set Permissions

```bash
chmod -R 777 application/cache
chmod -R 777 application/logs
chmod -R 777 uploads
```

### 5. Akses Aplikasi

Buka browser dan akses:
```
http://localhost/presensi-rfid
```

## 👤 Default Login

**Administrator:**
- Username: `admin`
- Password: `admin123`

⚠️ **PENTING**: Segera ubah password default setelah login pertama kali!

## 📁 Struktur Database

Database terdiri dari 23 tabel utama:
1. users - Data pengguna sistem
2. siswa - Data siswa
3. guru - Data guru
4. kelas - Data kelas
5. tahun_ajaran - Tahun ajaran
6. semester - Semester
7. mata_pelajaran - Mata pelajaran
8. jadwal_pelajaran - Jadwal
9. jurnal - Jurnal guru
10. absensi_harian - Absensi datang/pulang
11. absensi_mapel - Absensi per mapel
12. pengaturan - Setting sekolah
13. hari_kerja - Setting hari kerja
14. jam_kerja - Setting jam kerja
15. hari_libur - Hari libur nasional
16. wa_config - Konfigurasi WA
17. wa_template - Template pesan WA
18. wa_queue - Antrian notifikasi WA
19. wa_notifikasi_kelas - Kelas yang menerima notifikasi
20. izin_siswa - Data izin siswa
21. wali_kelas - Relasi guru-kelas
22. guru_piket - Data guru piket
23. monitoring_bk - Log monitoring BK
24. surat_bk - Surat pemanggilan BK

## 🎨 Tech Stack

- **Backend**: CodeIgniter 3.1.13
- **Frontend**: Tailwind CSS 3.x (via CDN)
- **Database**: MySQL with InnoDB engine
- **Icons**: Font Awesome 6.4.0
- **JavaScript**: Vanilla JS with AJAX

## 🔐 Keamanan

- CSRF Protection enabled
- Session management
- Password encryption (MD5 - untuk production disarankan menggunakan bcrypt)
- Role-based access control
- XSS filtering
- SQL injection protection (via Query Builder)

## 📱 Responsive Design

Aplikasi fully responsive dan dapat diakses dari:
- Desktop
- Tablet
- Mobile devices

## 🔄 Queue System untuk WhatsApp

Sistem queue memastikan notifikasi WhatsApp tidak menghambat proses absensi:
1. Saat tapping RFID, data langsung tersimpan
2. Notifikasi masuk ke queue (tabel wa_queue)
3. Cron job/background process mengirim notifikasi secara asynchronous
4. Retry mechanism untuk notifikasi yang gagal

## 📊 Export & Import

### Import Data
- Format: Excel (.xlsx)
- Template tersedia di menu import
- Validasi otomatis

### Export Data
- Format: Excel (.xlsx) & PDF
- Dengan kop sekolah (logo, nama, alamat)
- Filter by periode/bulan

## 🛠️ Development

### Menambah Controller Baru
```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class NamaController extends MY_Controller {
    protected $allowed_roles = array('admin'); // Set role yang diizinkan
    
    public function __construct() {
        parent::__construct();
        // Load model, library, dll
    }
}
```

### Menggunakan Template
```php
$data['title'] = 'Judul Halaman';
$this->load_template('admin/view_name', $data);
```

## 🐛 Troubleshooting

### Error: 404 Page Not Found
- Pastikan mod_rewrite Apache enabled
- Periksa file .htaccess di root folder
- Pastikan `$config['index_page'] = '';` di config.php

### Error: Database Connection
- Periksa kredensial database di `application/config/database.php`
- Pastikan MySQL service running
- Pastikan database sudah dibuat dan diimport

### Error: Session
- Periksa permission folder `application/cache`
- Pastikan `chmod 777 application/cache`

## 📝 License

Copyright © 2024 Sistem Presensi RFID. All rights reserved.

## 👨‍💻 Developer

Dikembangkan dengan ❤️ menggunakan CodeIgniter 3 & Tailwind CSS

## 📞 Support

Untuk bantuan dan pertanyaan, silakan buat issue di repository ini.

---

**Happy Coding! 🚀**
