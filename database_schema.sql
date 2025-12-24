-- Database Schema untuk Sistem Presensi RFID
-- Created: 2024

CREATE DATABASE IF NOT EXISTS presensi_rfid DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE presensi_rfid;

-- Table: users (Admin, Guru, Staff, BK)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    role ENUM('admin', 'guru', 'guru_wali_kelas', 'guru_piket', 'bk') NOT NULL DEFAULT 'guru',
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_role (role),
    INDEX idx_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: tahun_ajaran
CREATE TABLE tahun_ajaran (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tahun VARCHAR(20) NOT NULL,
    keterangan VARCHAR(100),
    is_active TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: semester
CREATE TABLE semester (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tahun_ajaran_id INT NOT NULL,
    semester ENUM('ganjil', 'genap') NOT NULL,
    is_active TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (tahun_ajaran_id) REFERENCES tahun_ajaran(id) ON DELETE CASCADE,
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: kelas
CREATE TABLE kelas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kelas VARCHAR(50) NOT NULL,
    tingkat INT NOT NULL,
    jurusan VARCHAR(50),
    tahun_ajaran_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (tahun_ajaran_id) REFERENCES tahun_ajaran(id) ON DELETE CASCADE,
    INDEX idx_tahun_ajaran (tahun_ajaran_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: guru
CREATE TABLE guru (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    nip VARCHAR(50) UNIQUE,
    rfid_uid VARCHAR(50) UNIQUE,
    nama_lengkap VARCHAR(100) NOT NULL,
    jenis_kelamin ENUM('L', 'P') NOT NULL,
    tempat_lahir VARCHAR(100),
    tanggal_lahir DATE,
    alamat TEXT,
    no_telepon VARCHAR(20),
    email VARCHAR(100),
    foto VARCHAR(255),
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_rfid (rfid_uid),
    INDEX idx_nip (nip)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: siswa
CREATE TABLE siswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nis VARCHAR(50) UNIQUE NOT NULL,
    nisn VARCHAR(50) UNIQUE,
    rfid_uid VARCHAR(50) UNIQUE,
    nama_lengkap VARCHAR(100) NOT NULL,
    jenis_kelamin ENUM('L', 'P') NOT NULL,
    tempat_lahir VARCHAR(100),
    tanggal_lahir DATE,
    alamat TEXT,
    kelas_id INT,
    no_telepon_ortu VARCHAR(20),
    nama_ortu VARCHAR(100),
    foto VARCHAR(255),
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (kelas_id) REFERENCES kelas(id) ON DELETE SET NULL,
    INDEX idx_rfid (rfid_uid),
    INDEX idx_nis (nis),
    INDEX idx_kelas (kelas_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: wali_kelas
CREATE TABLE wali_kelas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    guru_id INT NOT NULL,
    kelas_id INT NOT NULL,
    tahun_ajaran_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (guru_id) REFERENCES guru(id) ON DELETE CASCADE,
    FOREIGN KEY (kelas_id) REFERENCES kelas(id) ON DELETE CASCADE,
    FOREIGN KEY (tahun_ajaran_id) REFERENCES tahun_ajaran(id) ON DELETE CASCADE,
    UNIQUE KEY unique_wali (guru_id, kelas_id, tahun_ajaran_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: guru_piket
CREATE TABLE guru_piket (
    id INT AUTO_INCREMENT PRIMARY KEY,
    guru_id INT NOT NULL,
    hari ENUM('Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu') NOT NULL,
    tahun_ajaran_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (guru_id) REFERENCES guru(id) ON DELETE CASCADE,
    FOREIGN KEY (tahun_ajaran_id) REFERENCES tahun_ajaran(id) ON DELETE CASCADE,
    INDEX idx_hari (hari)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: mata_pelajaran
CREATE TABLE mata_pelajaran (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_mapel VARCHAR(20) UNIQUE NOT NULL,
    nama_mapel VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: jadwal_pelajaran
CREATE TABLE jadwal_pelajaran (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kelas_id INT NOT NULL,
    guru_id INT NOT NULL,
    mapel_id INT NOT NULL,
    hari ENUM('Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu') NOT NULL,
    jam_mulai TIME NOT NULL,
    jam_selesai TIME NOT NULL,
    tahun_ajaran_id INT NOT NULL,
    semester_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (kelas_id) REFERENCES kelas(id) ON DELETE CASCADE,
    FOREIGN KEY (guru_id) REFERENCES guru(id) ON DELETE CASCADE,
    FOREIGN KEY (mapel_id) REFERENCES mata_pelajaran(id) ON DELETE CASCADE,
    FOREIGN KEY (tahun_ajaran_id) REFERENCES tahun_ajaran(id) ON DELETE CASCADE,
    FOREIGN KEY (semester_id) REFERENCES semester(id) ON DELETE CASCADE,
    INDEX idx_hari (hari),
    INDEX idx_guru (guru_id),
    INDEX idx_kelas (kelas_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: jurnal
CREATE TABLE jurnal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jadwal_id INT NOT NULL,
    guru_id INT NOT NULL,
    tanggal DATE NOT NULL,
    materi TEXT,
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (jadwal_id) REFERENCES jadwal_pelajaran(id) ON DELETE CASCADE,
    FOREIGN KEY (guru_id) REFERENCES guru(id) ON DELETE CASCADE,
    INDEX idx_tanggal (tanggal),
    INDEX idx_guru (guru_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: absensi_harian (Absensi datang/pulang via RFID)
CREATE TABLE absensi_harian (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_type ENUM('siswa', 'guru') NOT NULL,
    user_id INT NOT NULL,
    tanggal DATE NOT NULL,
    jam_masuk TIME,
    jam_pulang TIME,
    status_masuk ENUM('tepat_waktu', 'terlambat') DEFAULT 'tepat_waktu',
    keterlambatan_menit INT DEFAULT 0,
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_tanggal (tanggal),
    INDEX idx_user (user_type, user_id),
    UNIQUE KEY unique_attendance (user_type, user_id, tanggal)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: absensi_mapel (Absensi per mapel: H/S/I/A)
CREATE TABLE absensi_mapel (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jurnal_id INT NOT NULL,
    siswa_id INT NOT NULL,
    status ENUM('H', 'S', 'I', 'A') NOT NULL DEFAULT 'H',
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (jurnal_id) REFERENCES jurnal(id) ON DELETE CASCADE,
    FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE,
    INDEX idx_siswa (siswa_id),
    INDEX idx_jurnal (jurnal_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: izin_siswa (Izin siswa keluar/masuk saat KBM)
CREATE TABLE izin_siswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    siswa_id INT NOT NULL,
    tanggal DATE NOT NULL,
    jenis_izin ENUM('sakit', 'izin', 'keluar_kbm', 'masuk_kbm') NOT NULL,
    jam_keluar TIME,
    jam_masuk TIME,
    keterangan TEXT,
    guru_piket_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE,
    FOREIGN KEY (guru_piket_id) REFERENCES guru(id) ON DELETE SET NULL,
    INDEX idx_tanggal (tanggal),
    INDEX idx_siswa (siswa_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: pengaturan (Settings sekolah)
CREATE TABLE pengaturan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_sekolah VARCHAR(200),
    alamat_sekolah TEXT,
    nama_kepala_sekolah VARCHAR(100),
    logo_sekolah VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: hari_kerja
CREATE TABLE hari_kerja (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hari ENUM('Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu') NOT NULL UNIQUE,
    is_aktif TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: jam_kerja
CREATE TABLE jam_kerja (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jam_masuk TIME NOT NULL,
    jam_pulang TIME NOT NULL,
    toleransi_terlambat INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: hari_libur
CREATE TABLE hari_libur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tanggal DATE NOT NULL UNIQUE,
    keterangan VARCHAR(200),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_tanggal (tanggal)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: wa_config
CREATE TABLE wa_config (
    id INT AUTO_INCREMENT PRIMARY KEY,
    url VARCHAR(255),
    api_key VARCHAR(255),
    sender VARCHAR(50),
    link_url VARCHAR(255),
    is_active TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: wa_template
CREATE TABLE wa_template (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipe ENUM('masuk', 'pulang') NOT NULL,
    template TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: wa_notifikasi_kelas (Kelas yang akan menerima notifikasi WA)
CREATE TABLE wa_notifikasi_kelas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kelas_id INT NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (kelas_id) REFERENCES kelas(id) ON DELETE CASCADE,
    UNIQUE KEY unique_kelas (kelas_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: wa_queue (Antrian notifikasi WA)
CREATE TABLE wa_queue (
    id INT AUTO_INCREMENT PRIMARY KEY,
    no_telepon VARCHAR(20) NOT NULL,
    pesan TEXT NOT NULL,
    status ENUM('pending', 'sent', 'failed') DEFAULT 'pending',
    retry_count INT DEFAULT 0,
    error_message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    sent_at TIMESTAMP NULL,
    INDEX idx_status (status),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: monitoring_bk
CREATE TABLE monitoring_bk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    siswa_id INT NOT NULL,
    jenis ENUM('alpha_3x', 'terlambat_5x') NOT NULL,
    bulan INT NOT NULL,
    tahun INT NOT NULL,
    jumlah INT NOT NULL,
    status ENUM('open', 'proses', 'selesai') DEFAULT 'open',
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE,
    INDEX idx_siswa (siswa_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: surat_bk
CREATE TABLE surat_bk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    monitoring_bk_id INT NOT NULL,
    nomor_surat VARCHAR(100),
    tanggal_surat DATE,
    isi_surat TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (monitoring_bk_id) REFERENCES monitoring_bk(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default data
INSERT INTO users (username, password, nama_lengkap, email, role, is_active) 
VALUES ('admin', MD5('admin123'), 'Administrator', 'admin@sekolah.com', 'admin', 1);

INSERT INTO pengaturan (nama_sekolah, alamat_sekolah, nama_kepala_sekolah) 
VALUES ('SMA Negeri 1', 'Jl. Pendidikan No. 1', 'Drs. Kepala Sekolah, M.Pd');

INSERT INTO jam_kerja (jam_masuk, jam_pulang, toleransi_terlambat) 
VALUES ('07:00:00', '15:00:00', 15);

INSERT INTO hari_kerja (hari, is_aktif) VALUES
('Senin', 1),
('Selasa', 1),
('Rabu', 1),
('Kamis', 1),
('Jumat', 1),
('Sabtu', 0),
('Minggu', 0);

INSERT INTO wa_template (tipe, template) VALUES
('masuk', 'Assalamualaikum, Bapak/Ibu {NAMA_ORTU}. Putra/i Anda {NAMA_SISWA} telah masuk sekolah pada pukul {JAM_MASUK}. Terima kasih.'),
('pulang', 'Assalamualaikum, Bapak/Ibu {NAMA_ORTU}. Putra/i Anda {NAMA_SISWA} telah pulang sekolah pada pukul {JAM_PULANG}. Terima kasih.');

INSERT INTO wa_config (url, api_key, sender, is_active) 
VALUES ('https://api.whatsapp.com', '', '628123456789', 0);
