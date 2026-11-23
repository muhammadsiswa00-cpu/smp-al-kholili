-- =======================================================
-- SKEMA DATABASE MYSQL: SMP ISLAM AL-KHOLILI
-- Nama Database: smp_alkholili
-- =======================================================

CREATE DATABASE IF NOT EXISTS `smp_alkholili` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `smp_alkholili`;

-- 1. Tabel Administrator
CREATE TABLE IF NOT EXISTS `admin` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `nama_lengkap` VARCHAR(100) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `admin` (`id`, `username`, `password`, `nama_lengkap`) VALUES
(1, 'smp al-kholili', 'al-kholili', 'Administrator SMP Islam Al-Kholili')
ON DUPLICATE KEY UPDATE `username`=`username`;

-- 2. Tabel Pengaturan Website
CREATE TABLE IF NOT EXISTS `pengaturan` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `site_name` VARCHAR(100) NOT NULL,
    `site_tagline` VARCHAR(150),
    `alamat` TEXT,
    `phone` VARCHAR(30),
    `whatsapp_number` VARCHAR(30),
    `whatsapp_display` VARCHAR(30),
    `email` VARCHAR(100),
    `ppdb_url` VARCHAR(255),
    `logo` VARCHAR(255),
    `hero_bg` VARCHAR(255),
    `nama_kepsek` VARCHAR(150),
    `sambutan_kepsek` TEXT,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Tabel Data Guru
CREATE TABLE IF NOT EXISTS `guru` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nama` VARCHAR(150) NOT NULL,
    `jabatan` VARCHAR(100) NOT NULL,
    `mapel` VARCHAR(100),
    `pendidikan` VARCHAR(100),
    `foto` VARCHAR(255),
    `urutan` INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Tabel Galeri Foto
CREATE TABLE IF NOT EXISTS `galeri` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `judul` VARCHAR(150) NOT NULL,
    `kategori` VARCHAR(50) DEFAULT 'kegiatan',
    `gambar` VARCHAR(255) NOT NULL,
    `deskripsi` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Tabel Berita Sekolah
CREATE TABLE IF NOT EXISTS `berita` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `judul` VARCHAR(200) NOT NULL,
    `kategori` VARCHAR(50) DEFAULT 'Berita',
    `tanggal` DATE,
    `penulis` VARCHAR(100) DEFAULT 'Admin',
    `gambar` VARCHAR(255),
    `isi` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Tabel Pendaftaran PPDB
CREATE TABLE IF NOT EXISTS `ppdb` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `no_pendaftaran` VARCHAR(50) UNIQUE,
    `nama_lengkap` VARCHAR(150) NOT NULL,
    `nik` VARCHAR(30),
    `nisn` VARCHAR(30),
    `jenis_kelamin` ENUM('L', 'P'),
    `tempat_lahir` VARCHAR(100),
    `tanggal_lahir` DATE,
    `asal_sekolah` VARCHAR(150),
    `nama_ayah` VARCHAR(150),
    `nama_ibu` VARCHAR(150),
    `no_whatsapp` VARCHAR(30),
    `alamat` TEXT,
    `status` ENUM('Menunggu', 'Diterima', 'Ditolak') DEFAULT 'Menunggu',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
