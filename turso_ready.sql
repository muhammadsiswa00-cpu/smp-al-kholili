-- =========================================================================
-- TURSO / LIB-SQL READY SQLITE DUMP
-- SMP ISLAM AL-KHOLILI KOTA PROBOLINGGO
-- 100% Kompatibel dengan SQLite & Turso Database Import
-- =========================================================================

BEGIN TRANSACTION;

-- 1. Tabel Administrator
DROP TABLE IF EXISTS "admin";
CREATE TABLE "admin" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "username" TEXT NOT NULL UNIQUE,
    "password" TEXT NOT NULL,
    "nama_lengkap" TEXT NOT NULL,
    "created_at" TEXT DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO "admin" ("id", "username", "password", "nama_lengkap") VALUES
(1, 'smp al-kholili', 'al-kholili', 'Administrator SMP Islam Al-Kholili');

-- 2. Tabel Pengaturan Website
DROP TABLE IF EXISTS "pengaturan";
CREATE TABLE "pengaturan" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "site_name" TEXT NOT NULL,
    "site_tagline" TEXT,
    "hero_title" TEXT,
    "hero_subtitle" TEXT,
    "alamat" TEXT,
    "phone" TEXT,
    "whatsapp_number" TEXT,
    "whatsapp_display" TEXT,
    "email" TEXT,
    "ppdb_url" TEXT,
    "logo" TEXT,
    "hero_bg" TEXT,
    "nama_kepsek" TEXT,
    "kepsek_foto" TEXT,
    "sambutan_kepsek" TEXT,
    "updated_at" TEXT DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO "pengaturan" (
    "id", "site_name", "site_tagline", "hero_title", "hero_subtitle", 
    "alamat", "phone", "whatsapp_number", "whatsapp_display", "email", 
    "ppdb_url", "logo", "hero_bg", "nama_kepsek", "kepsek_foto", "sambutan_kepsek"
) VALUES (
    1,
    'SMP ISLAM AL-KHOLILI',
    'Kota Probolinggo • Berkarakter Qur''ani',
    'Selamat Datang Di SMP Islam Al-Kholili',
    'Mewujudkan generasi Muslim yang kokoh dalam akidah, unggul dalam prestasi akademik, santun dalam budi pekerti, serta terampil dalam penguasaan teknologi.',
    'Jl. KUA Blok Masjid Baiturrohman RT03 RW04 Kel. Kedopok, Kec. Kedopok, Kota Probolinggo',
    '(0335) 421xxx / 0852-3456-7890',
    '6285234567890',
    '+62 852-3456-7890',
    'info@smpislam-alkholili.sch.id',
    'https://ppdb.smpislam-alkholili.sch.id',
    'assets/img/logo.png',
    'assets/uploads/hero_bg_1795050753.jpeg',
    'Muhammad Taufiqur Rohman, S.Pd,',
    'assets/uploads/kepsek_1794985994.png',
    'Assalamu’alaikum Warahmatullahi Wabarakaatuh.

Salam sejahtera untuk kita semua. Selamat datang di website SMP Islam Al-Kholili. Website ini dibangun sebagai sarana atau media informasi dan komunikasi sekolah, karena sejalan dengan perkembangan teknologi industri 4.0 yang berguna untuk memudahkan mencari informasi tentang SMP Islam Al-Kholili. Kualitas layanan menjadi salah satu misi sekolah dan kaitannya dengan transparansi dan akuntabilitas sekolah.

Menjalankan sebagai pimpinan di SMP Islam Al-Kholili, harapan tentu untuk mengembangkan sekolah besar ini menjadi lebih baik lagi, bertabur bintang baik akademik maupun non akademik. Sekolah yang akan melahirkan generasi yang inovatif, kreatif, religius, dan akhlakul karimah yang berwawasan luas serta mengabdi kepada agama, bangsa, dan negara.

Selaku pimpinan saya mendorong terus perkembangan inovasi dan kreasi warga sekolah. Mengeratkan tali saliturahmi, kekeluargaan, dan kerjasama seluruh warga sekolah. Dari lubuk hati yang paling dalam, saya mengajak kepada seluruh civitas akademik SMP Islam Al-Kholili untuk sama-sama bergandeng tangan, berkolaborasi guna mengembangkan sekolah nyaman, aman, dan menyenangkan sebagai tempat belajar untuk putra-putri kita menuntut ilmu dan tetap mempertahankan serta mengembangkan image SMP Islam Al-Kholili sebagai sekolah unggulan di Kota Probolinggo.

Wassalamu’alaikum Warohmatullahi Wabarokatuh.'
);

-- 3. Tabel Data Dewan Guru
DROP TABLE IF EXISTS "guru";
CREATE TABLE "guru" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "nama" TEXT NOT NULL,
    "jabatan" TEXT NOT NULL,
    "mapel" TEXT,
    "pendidikan" TEXT,
    "foto" TEXT,
    "urutan" INTEGER DEFAULT 0
);

INSERT INTO "guru" ("id", "nama", "jabatan", "mapel", "pendidikan", "foto", "urutan") VALUES
(1, 'Muhammad Taufiqur Rohman, S.Pd,', 'Kepala Sekolah', 'Manajemen Pendidikan & Kepemimpinan', 'S2 Manajemen Pendidikan', 'assets/uploads/kepsek_1794985994.png', 1),
(2, 'Siti Rohmah, S.Pd.', 'Waka Kesiswaan', 'Matematika', 'S1 Pendidikan Matematika', 'assets/img/teachers/female-1.svg', 2),
(3, 'Ust. Ahmad Thohir, S.Q.', 'Koordinator Program Tahfidz', 'Tahfidzul Qur''an & Tajwid', 'S1 Ilmu Al-Qur''an & Tafsir (Hafidz 30 Juz)', 'assets/img/teachers/male-1.svg', 3),
(4, 'Nurul Hidayati, S.Pd.', 'Guru Mata Pelajaran', 'Bahasa Indonesia', 'S1 Pendidikan Bahasa & Sastra Indonesia', 'assets/img/teachers/female-1.svg', 4),
(5, 'Bambang Kurniawan, S.Pd.', 'Guru Mata Pelajaran', 'Bahasa Inggris', 'S1 Pendidikan Bahasa Inggris', 'assets/img/teachers/male-1.svg', 5),
(6, 'Dewi Anggraini, S.Si.', 'Guru Mata Pelajaran', 'Ilmu Pengetahuan Alam (IPA)', 'S1 Sains Fisika & Biologi', 'assets/img/teachers/female-1.svg', 6),
(7, 'Fathur Rahman, S.Kom.', 'Guru Mata Pelajaran & IT', 'Informatika / Komputer', 'S1 Teknik Informatika', 'assets/img/teachers/male-1.svg', 7),
(8, 'Hendra Prasetyo, S.Pd.', 'Guru Mapel & Pembina Pramuka', 'Pendidikan Jasmani & Olahraga (PJOK)', 'S1 Pendidikan Olahraga & Kesehatan', 'assets/img/teachers/male-1.svg', 8),
(9, 'Aisyah Zahira, S.Sos.', 'Guru Bimbingan Konseling (BK)', 'Bimbingan & Konseling Siswa', 'S1 Bimbingan Konseling', 'assets/img/teachers/female-1.svg', 9),
(10, 'M. Rizqi Pratama, A.Md.', 'Kepala Tata Usaha', 'Administrasi & Layanan Surat-Menyurat', 'D3 Administrasi Perkantoran', 'assets/img/teachers/male-1.svg', 10),
(11, 'Umi Hanik, S.E.', 'Staf Keuangan & Bendahara', 'Administrasi Keuangan Sekolah', 'S1 Manajemen Keuangan', 'assets/img/teachers/female-1.svg', 11);

-- 4. Tabel Galeri Foto Kegiatan
DROP TABLE IF EXISTS "galeri";
CREATE TABLE "galeri" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "judul" TEXT NOT NULL,
    "kategori" TEXT DEFAULT 'kegiatan',
    "gambar" TEXT,
    "deskripsi" TEXT,
    "created_at" TEXT DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO "galeri" ("id", "judul", "kategori", "gambar", "deskripsi") VALUES
(1, 'Sholat Berjamaah di Masjid Baiturrohman', 'ibadah', 'assets/uploads/hero_bg_1795050753.jpeg', 'Pembiasaan sholat Dhuha dan Dhuhur berjamaah bersama seluruh dewan guru dan siswa.'),
(2, 'Halaqah Tahfidzul Qur''an & Talaqqi', 'tahfidz', 'assets/uploads/hero_bg_1795050753.jpeg', 'Siswa menyetorkan hafalan surat pilihan dan juz 30 dipandu ustadz pengampu tahfidz.'),
(3, 'Praktikum Sains & Pembelajaran Kelas Interaktif', 'belajar', 'assets/uploads/hero_bg_1795050753.jpeg', 'Eksplorasi konsep sains fisika dan biologi dengan metode praktikum aktif dan menyenangkan.'),
(4, 'Latihan Grup Rebana & Seni Hadrah Shalawat', 'ekskul', 'assets/uploads/hero_bg_1795050753.jpeg', 'Mengasah olah vokal religi dan ketukan terbang banjari untuk syiar shalawat nabi.'),
(5, 'Kegiatan Kepanduan Pramuka & Outbound', 'ekskul', 'assets/uploads/hero_bg_1795050753.jpeg', 'Melatih kemandirian, kekompakan tim, tali-temali, dan jiwa kepemimpinan generasi muda.'),
(6, 'Laboratorium Komputer & Literasi Digital', 'fasilitas', 'assets/uploads/hero_bg_1795050753.jpeg', 'Fasilitas komputer modern untuk simulasi ujian, pembelajaran TIK, dan riset ilmiah siswa.');

-- 5. Tabel Berita Sekolah
DROP TABLE IF EXISTS "berita";
CREATE TABLE "berita" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "judul" TEXT NOT NULL,
    "kategori" TEXT DEFAULT 'Berita',
    "tanggal" TEXT,
    "penulis" TEXT DEFAULT 'Humas Sekolah',
    "gambar" TEXT,
    "ringkasan" TEXT,
    "created_at" TEXT DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO "berita" ("id", "judul", "kategori", "tanggal", "penulis", "gambar", "ringkasan") VALUES
(1, 'Sosialisasi Pembukaan PPDB Tahun Ajaran 2026/2027 di SMP Islam Al-Kholili', 'Pengumuman PPDB', '15 November 2026', 'Humas Sekolah', '', 'Panitia Penerimaan Peserta Didik Baru (PPDB) resmi membuka pendaftaran gelombang pertama dengan potongan khusus biaya seragam dan formulir gratis.'),
(2, 'Peringatan Hari Santri Nasional: Santri Berdaya Menuju Masa Depan Gemilang', 'Kegiatan Islami', '22 Oktober 2026', 'Kesiswaan', '', 'Civitas akademika SMP Islam Al-Kholili menggelar upacara bendera bersarung dilanjutkan pawai ta''aruf dan lomba tartil Qur''an antar kelas.'),
(3, 'Ujian Munaqasyah Tahfidz Al-Qur''an Juz 30 dan Juz 1 Berjalan Khidmat', 'Tahfidz', '05 Oktober 2026', 'Tim Tahfidz', '', 'Sebanyak 45 santri mengikuti ujian munaqasyah di hadapan tim penguji dan disaksikan langsung oleh para orang tua santri di Masjid Baiturrohman.');

-- 6. Tabel PPDB (Pendaftaran Peserta Didik Baru)
DROP TABLE IF EXISTS "ppdb";
CREATE TABLE "ppdb" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "no_pendaftaran" TEXT UNIQUE,
    "nama_lengkap" TEXT NOT NULL,
    "nik" TEXT,
    "nisn" TEXT,
    "jenis_kelamin" TEXT,
    "tempat_lahir" TEXT,
    "tanggal_lahir" TEXT,
    "asal_sekolah" TEXT,
    "nama_ayah" TEXT,
    "nama_ibu" TEXT,
    "no_whatsapp" TEXT,
    "alamat" TEXT,
    "status" TEXT DEFAULT 'Menunggu',
    "created_at" TEXT DEFAULT CURRENT_TIMESTAMP
);

COMMIT;
