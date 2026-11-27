<?php
require_once __DIR__ . '/includes/settings.php';
$siteSettings = get_all_settings();

$page_title = "PPDB Online - SMP Islam Al-Kholili Probolinggo";
$page_desc = "Informasi pendaftaran peserta didik baru (PPDB) SMP Islam Al-Kholili Kota Probolinggo. Syarat mudah, kuota beasiswa, dan pendaftaran online resmi.";
$current_page = "ppdb";

$success_message = "";
$registration_id = "";
$submitted_data = null;

if (($_SERVER["REQUEST_METHOD"] ?? '') === "POST" && isset($_POST['submit_ppdb'])) {
    $nama = trim($_POST['nama_lengkap'] ?? $_POST['nama_siswa'] ?? '');
    $nisn = trim($_POST['nisn'] ?? '');
    $jk = trim($_POST['jenis_kelamin'] ?? '');
    $tempat_lahir = trim($_POST['tempat_lahir'] ?? '');
    $tanggal_lahir = trim($_POST['tanggal_lahir'] ?? '');
    $alamat_lengkap = trim($_POST['alamat_lengkap'] ?? $_POST['alamat'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $asal_sekolah = trim($_POST['asal_sekolah'] ?? '');
    $alamat_sekolah_asal = trim($_POST['alamat_sekolah_asal'] ?? '');
    $jalur = trim($_POST['jalur'] ?? 'Reguler');
    $no_wa = trim($_POST['no_wa'] ?? '');

    // Ortu
    $nama_ayah = trim($_POST['nama_ayah'] ?? '');
    $tempat_lahir_ayah = trim($_POST['tempat_lahir_ayah'] ?? '');
    $tanggal_lahir_ayah = trim($_POST['tanggal_lahir_ayah'] ?? '');
    $pekerjaan_ayah = trim($_POST['pekerjaan_ayah'] ?? '');
    $pendidikan_ayah = trim($_POST['pendidikan_ayah'] ?? '');
    $telepon_ayah = trim($_POST['telepon_ayah'] ?? '');

    $nama_ibu = trim($_POST['nama_ibu'] ?? '');
    $tempat_lahir_ibu = trim($_POST['tempat_lahir_ibu'] ?? '');
    $tanggal_lahir_ibu = trim($_POST['tanggal_lahir_ibu'] ?? '');
    $pekerjaan_ibu = trim($_POST['pekerjaan_ibu'] ?? '');
    $pendidikan_ibu = trim($_POST['pendidikan_ibu'] ?? '');
    $telepon_ibu = trim($_POST['telepon_ibu'] ?? '');

    $berkas_akta = trim($_POST['berkas_akta'] ?? '');
    $berkas_ktp = trim($_POST['berkas_ktp'] ?? '');
    $berkas_foto = trim($_POST['berkas_foto'] ?? '');
    $berkas_rapor = trim($_POST['berkas_rapor'] ?? '');
    $berkas_skl = trim($_POST['berkas_skl'] ?? '');

    if (!empty($nama) && !empty($no_wa)) {
        $registration_id = "AK-" . date('y') . "-" . rand(1000, 9999);
        $submission = [
            "id" => $registration_id,
            "no_pendaftaran" => $registration_id,
            "waktu" => date('Y-m-d H:i:s'),
            "nama" => $nama,
            "nama_lengkap" => $nama,
            "nisn" => $nisn,
            "jenis_kelamin" => $jk,
            "tempat_lahir" => $tempat_lahir,
            "tanggal_lahir" => $tanggal_lahir,
            "alamat_lengkap" => $alamat_lengkap,
            "email" => $email,
            "asal_sekolah" => $asal_sekolah,
            "alamat_sekolah_asal" => $alamat_sekolah_asal,
            "jalur" => $jalur,
            "no_wa" => $no_wa,
            "nama_ayah" => $nama_ayah,
            "tempat_lahir_ayah" => $tempat_lahir_ayah,
            "tanggal_lahir_ayah" => $tanggal_lahir_ayah,
            "pekerjaan_ayah" => $pekerjaan_ayah,
            "pendidikan_ayah" => $pendidikan_ayah,
            "telepon_ayah" => $telepon_ayah,
            "nama_ibu" => $nama_ibu,
            "tempat_lahir_ibu" => $tempat_lahir_ibu,
            "tanggal_lahir_ibu" => $tanggal_lahir_ibu,
            "pekerjaan_ibu" => $pekerjaan_ibu,
            "pendidikan_ibu" => $pendidikan_ibu,
            "telepon_ibu" => $telepon_ibu,
            "berkas_akta" => $berkas_akta,
            "berkas_ktp" => $berkas_ktp,
            "berkas_foto" => $berkas_foto,
            "berkas_rapor" => $berkas_rapor,
            "berkas_skl" => $berkas_skl,
            "status" => "Menunggu"
        ];

        $dataFile = __DIR__ . '/data/ppdb_submissions.json';
        $currentList = [];
        if (file_exists($dataFile)) {
            $jsonContent = file_get_contents($dataFile);
            $currentList = json_decode($jsonContent, true) ?: [];
        }
        array_unshift($currentList, $submission);
        @file_put_contents($dataFile, json_encode($currentList, JSON_PRETTY_PRINT));

        $submitted_data = $submission;
        $success_message = "Alhamdulillah! Data formulir pendaftaran berhasil tersimpan. Silakan simpan Nomor Registrasi Anda dan konfirmasikan ke Panitia PPDB melalui WhatsApp.";
    }
}

include 'includes/header.php';
?>

<!-- Page Header Banner -->
<section class="bg-gradient-to-r from-brand-950 via-brand-900 to-emerald-900 text-white py-14 border-b border-brand-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:text-left flex flex-col sm:flex-row items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 text-xs font-semibold text-gold-400 uppercase tracking-widest mb-2">
                <i class="fa-solid fa-graduation-cap"></i> Penerimaan Siswa Baru
            </div>
            <h1 class="text-3xl sm:text-4xl font-heading font-extrabold tracking-tight">PPDB Online Tahun Ajaran 2026/2027</h1>
            <p class="text-emerald-200 text-sm mt-2 max-w-xl">
                Bergabunglah bersama SMP Islam Al-Kholili Kedopok Probolinggo. Pendaftaran mudah, transparan, dan dapat dilakukan secara daring maupun luring.
            </p>
        </div>
        <div class="flex items-center gap-2 text-xs text-slate-300 bg-white/10 px-4 py-2 rounded-lg backdrop-blur-sm">
            <a href="index.php" class="hover:text-white transition">Beranda</a>
            <span>/</span>
            <span class="text-gold-400 font-semibold">PPDB</span>
        </div>
    </div>
</section>

<!-- Main PPDB Content -->
<section class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Registration Confirmation Alert if submitted -->
        <?php if (!empty($success_message) && $submitted_data): ?>
        <div class="mb-12 bg-white rounded-3xl p-8 border-2 border-emerald-500 shadow-xl relative overflow-hidden animate-fade-in">
            <div class="flex items-start gap-5">
                <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-brand-700 flex items-center justify-center text-3xl flex-shrink-0">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div class="flex-1 space-y-3">
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 inline-block">
                        Pendaftaran Daring Berhasil
                    </span>
                    <h3 class="text-2xl font-heading font-extrabold text-slate-900">
                        <?= $success_message ?>
                    </h3>
                    
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 max-w-xl">
                        <div class="grid grid-cols-2 gap-3 text-xs sm:text-sm">
                            <div>
                                <span class="text-slate-400 block">No. Registrasi:</span>
                                <strong class="text-lg text-brand-800 font-mono"><?= $submitted_data['id'] ?></strong>
                            </div>
                            <div>
                                <span class="text-slate-400 block">Jalur:</span>
                                <strong class="text-slate-800"><?= htmlspecialchars($submitted_data['jalur']) ?></strong>
                            </div>
                            <div>
                                <span class="text-slate-400 block">Nama Siswa:</span>
                                <strong class="text-slate-800"><?= htmlspecialchars($submitted_data['nama']) ?></strong>
                            </div>
                            <div>
                                <span class="text-slate-400 block">Asal Sekolah:</span>
                                <strong class="text-slate-800"><?= htmlspecialchars($submitted_data['asal_sekolah']) ?></strong>
                            </div>
                        </div>
                    </div>

                    <?php
                    $waText = "Assalamu'alaikum Panitia PPDB SMP Islam Al-Kholili, saya telah mendaftar online:\n"
                            . "• No Registrasi: " . $submitted_data['id'] . "\n"
                            . "• Nama Calon Siswa: " . $submitted_data['nama'] . "\n"
                            . "• Asal Sekolah: " . $submitted_data['asal_sekolah'] . "\n"
                            . "• Jalur: " . $submitted_data['jalur'] . "\n"
                            . "• No WhatsApp: " . $submitted_data['no_wa'] . "\n"
                            . "Mohon informasi verifikasi berkas selanjutnya. Terima kasih.";
                    $waUrl = "https://wa.me/6285234567890?text=" . rawurlencode($waText);
                    ?>

                    <div class="pt-2 flex flex-wrap items-center gap-4">
                        <a href="<?= $waUrl ?>" target="_blank" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl font-bold text-white bg-green-600 hover:bg-green-700 shadow-lg shadow-green-600/30 transition">
                            <i class="fa-brands fa-whatsapp text-xl"></i>
                            <span>Kirim Konfirmasi ke WhatsApp Panitia</span>
                        </a>
                        <button onclick="window.print()" class="inline-flex items-center gap-2 px-5 py-3.5 rounded-xl font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 transition">
                            <i class="fa-solid fa-print"></i> Cetak Bukti Pendaftaran
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- External PPDB Website Highlight Banner -->
<div class="mb-12 bg-gradient-to-r from-brand-900 via-emerald-800 to-brand-950 rounded-3xl p-8 sm:p-10 text-white shadow-xl border-2 border-gold-400 relative overflow-hidden">
    <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-gold-500/10 rounded-full blur-2xl"></div>
    <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6 text-center md:text-left">
        <div class="space-y-2">
            <span class="inline-block px-3 py-1 rounded-full bg-gold-400 text-brand-950 text-xs font-extrabold uppercase tracking-wider">
                Portal Utama PPDB
            </span>
            <h2 class="text-2xl sm:text-3xl font-heading font-extrabold text-white">
                Website Resmi Pendaftaran Siswa Baru (PPDB)
            </h2>
            <p class="text-emerald-100 text-sm max-w-xl">
                Silakan akses sistem informasi dan pendaftaran online terpadu melalui website resmi PPDB SMP Islam Al-Kholili.
            </p>
        </div>
        <div>
            <?php 
            $extPpdb = !empty($siteSettings['ppdb_url']) ? $siteSettings['ppdb_url'] : '#';
            $isExt = str_starts_with($extPpdb, 'http');
            ?>
            <a href="<?= htmlspecialchars($extPpdb) ?>" <?= $isExt ? 'target="_blank" rel="noopener noreferrer"' : '' ?> class="inline-flex items-center gap-2.5 px-8 py-4 rounded-2xl font-extrabold text-base text-brand-950 bg-gradient-to-r from-gold-400 via-yellow-300 to-amber-300 hover:from-gold-300 hover:to-yellow-200 shadow-2xl shadow-gold-500/40 hover:scale-105 transition transform">
                <i class="fa-solid fa-graduation-cap text-xl"></i>
                <span>Buka Website PPDB Resmi</span>
                <i class="fa-solid fa-arrow-up-right-from-square text-sm"></i>
            </a>
        </div>
    </div>
</div>
        <div class="mb-14">
            <div class="text-center max-w-2xl mx-auto mb-10">
                <span class="text-xs font-bold uppercase tracking-wider text-brand-700 bg-brand-50 px-3.5 py-1.5 rounded-full">
                    Tata Cara PPDB
                </span>
                <h2 class="font-heading font-extrabold text-2xl sm:text-3xl text-slate-900 mt-2">
                    4 Langkah Mudah Pendaftaran
                </h2>
                <p class="text-slate-500 text-sm mt-1">Alur penerimaan peserta didik baru SMP Islam Al-Kholili Kota Probolinggo.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm relative">
                    <span class="absolute -top-3.5 left-6 px-3 py-0.5 rounded-full text-xs font-extrabold bg-brand-700 text-white">Langkah 1</span>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-brand-700 flex items-center justify-center text-xl mt-2 mb-4">
                        <i class="fa-solid fa-file-signature"></i>
                    </div>
                    <h3 class="font-heading font-bold text-base text-slate-900 mb-1">Pengisian Formulir</h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Mengisi formulir secara daring pada laman ini atau langsung di kantor sekretariat SMP Islam Al-Kholili.
                    </p>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm relative">
                    <span class="absolute -top-3.5 left-6 px-3 py-0.5 rounded-full text-xs font-extrabold bg-gold-500 text-white">Langkah 2</span>
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-gold-600 flex items-center justify-center text-xl mt-2 mb-4">
                        <i class="fa-solid fa-folder-check"></i>
                    </div>
                    <h3 class="font-heading font-bold text-base text-slate-900 mb-1">Verifikasi Berkas</h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Menyerahkan fotokopi Ijazah/SKL, Kartu Keluarga (KK), Akta Kelahiran, dan pas foto 3x4 (3 lembar).
                    </p>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm relative">
                    <span class="absolute -top-3.5 left-6 px-3 py-0.5 rounded-full text-xs font-extrabold bg-blue-600 text-white">Langkah 3</span>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl mt-2 mb-4">
                        <i class="fa-solid fa-comments"></i>
                    </div>
                    <h3 class="font-heading font-bold text-base text-slate-900 mb-1">Observasi & Baca Qur'an</h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Pemetaan kemampuan membaca Al-Qur'an (tahsin dasar) dan wawancara komitmen minat bakat peserta didik.
                    </p>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm relative">
                    <span class="absolute -top-3.5 left-6 px-3 py-0.5 rounded-full text-xs font-extrabold bg-purple-600 text-white">Langkah 4</span>
                    <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl mt-2 mb-4">
                        <i class="fa-solid fa-award"></i>
                    </div>
                    <h3 class="font-heading font-bold text-base text-slate-900 mb-1">Daftar Ulang & Seragam</h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Pengambilan atribut seragam sekolah dan siap mengikuti kegiatan Masa Pengenalan Lingkungan Sekolah (MPLS).
                    </p>
                </div>

            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
            
            <!-- Left: Jalur & Syarat -->
            <div class="lg:col-span-5 space-y-6">
                
                <!-- Jalur Pendaftaran Card -->
                <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200 shadow-sm">
                    <h3 class="font-heading font-extrabold text-lg text-slate-900 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-route text-brand-700"></i> Pilihan Jalur Masuk
                    </h3>
                    <div class="space-y-3.5 text-xs sm:text-sm">
                        <div class="p-3.5 rounded-xl bg-emerald-50/70 border border-emerald-200/60">
                            <strong class="text-brand-900 block font-heading font-bold">1. Jalur Tahfidz Qur'an</strong>
                            <p class="text-slate-600 mt-1">Khusus calon siswa yang memiliki hafalan minimal 1-3 juz Al-Qur'an (beasiswa pembebasan uang gedung / SPP).</p>
                        </div>
                        <div class="p-3.5 rounded-xl bg-amber-50/70 border border-amber-200/60">
                            <strong class="text-amber-900 block font-heading font-bold">2. Jalur Prestasi (Akademik & Seni/Olahraga)</strong>
                            <p class="text-slate-600 mt-1">Bagi peraih peringkat kelas atau juara perlombaan tingkat kecamatan/kota.</p>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                            <strong class="text-slate-800 block font-heading font-bold">3. Jalur Reguler</strong>
                            <p class="text-slate-600 mt-1">Terbuka bagi seluruh lulusan SD/MI negeri maupun swasta yang ingin belajar dan bertumbuh di SMP Islam Al-Kholili.</p>
                        </div>
                    </div>
                </div>

                <!-- Persyaratan Berkas -->
                <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200 shadow-sm">
                    <h3 class="font-heading font-extrabold text-lg text-slate-900 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-clipboard-list text-gold-500"></i> Berkas Persyaratan
                    </h3>
                    <ul class="space-y-2.5 text-xs sm:text-sm text-slate-600">
                        <li class="flex items-center gap-2.5"><i class="fa-solid fa-check text-brand-600"></i> Mengisi formulir pendaftaran secara lengkap.</li>
                        <li class="flex items-center gap-2.5"><i class="fa-solid fa-check text-brand-600"></i> FC Kartu Keluarga (KK) & KTP Orang Tua (2 lembar).</li>
                        <li class="flex items-center gap-2.5"><i class="fa-solid fa-check text-brand-600"></i> FC Akta Kelahiran Siswa (2 lembar).</li>
                        <li class="flex items-center gap-2.5"><i class="fa-solid fa-check text-brand-600"></i> FC Ijazah SD/MI atau Surat Keterangan Lulus (SKL).</li>
                        <li class="flex items-center gap-2.5"><i class="fa-solid fa-check text-brand-600"></i> Pas foto berseragam ukuran 3x4 (3 lembar).</li>
                        <li class="flex items-center gap-2.5"><i class="fa-solid fa-check text-brand-600"></i> FC Piagam/Sertifikat prestasi (bila ada).</li>
                    </ul>

                    <div class="mt-6 pt-4 border-t border-slate-100">
                        <div class="text-xs text-slate-500 mb-2">Butuh bantuan atau informasi formulir offline?</div>
                        <a href="https://wa.me/6285234567890?text=Halo%20Panitia%20PPDB%20SMP%20Islam%20Al-Kholili,%20saya%20ingin%20tanya%20syarat%20pendaftaran" target="_blank" class="inline-flex items-center gap-2 text-xs font-bold text-brand-700 hover:underline">
                            <i class="fa-brands fa-whatsapp text-green-500 text-sm"></i> Chat Panitia PPDB: 0852-3456-7890
                        </a>
                    </div>
                </div>

            </div>

            <!-- Right: Interactive Online Form -->
            <div class="lg:col-span-7">
                <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 shadow-md">
                    <div class="mb-6">
                        <span class="text-xs font-bold uppercase tracking-wider text-brand-700 bg-brand-50 px-3 py-1 rounded-md">
                            Formulir Online
                        </span>
                        <h3 class="text-2xl font-heading font-extrabold text-slate-900 mt-2">
                            Pendaftaran Calon Peserta Didik Baru
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1">
                            Lengkapi data di bawah ini dengan benar. Panitia akan segera menghubungi nomor WhatsApp yang terdaftar.
                        </p>
                    </div>

                    <form action="ppdb.php" method="POST" class="space-y-5">
                        
                        <!-- Nama Siswa -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="nama_siswa">
                                Nama Lengkap Calon Siswa <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nama_siswa" name="nama_siswa" required placeholder="Contoh: Muhammad Farhan Al-Ghifari" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-600 text-sm">
                        </div>

                        <!-- NISN & Jenis Kelamin -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="nisn">
                                    NISN (Nomor Induk Siswa Nasional)
                                </label>
                                <input type="text" id="nisn" name="nisn" placeholder="10 digit nomor NISN" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-600 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="jenis_kelamin">
                                    Jenis Kelamin <span class="text-red-500">*</span>
                                </label>
                                <select id="jenis_kelamin" name="jenis_kelamin" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-600 text-sm bg-white">
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <!-- Tempat & Tanggal Lahir -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="tempat_lahir">
                                    Tempat Lahir
                                </label>
                                <input type="text" id="tempat_lahir" name="tempat_lahir" placeholder="Contoh: Probolinggo" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-600 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="tanggal_lahir">
                                    Tanggal Lahir
                                </label>
                                <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-600 text-sm">
                            </div>
                        </div>

                        <!-- Asal Sekolah & Jalur -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="asal_sekolah">
                                    Asal Sekolah (SD/MI) <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="asal_sekolah" name="asal_sekolah" required placeholder="Contoh: SDN Kedopok 1 / MI Baiturrahman" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-600 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="jalur">
                                    Pilihan Jalur Masuk <span class="text-red-500">*</span>
                                </label>
                                <select id="jalur" name="jalur" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-600 text-sm bg-white">
                                    <option value="Jalur Reguler">Jalur Reguler</option>
                                    <option value="Jalur Tahfidz Qur'an">Jalur Tahfidz Qur'an (Beasiswa)</option>
                                    <option value="Jalur Prestasi Akademik / Non-Akademik">Jalur Prestasi</option>
                                    <option value="Jalur Afirmasi / Bantuan Pendidikan">Jalur Afirmasi / Bantuan</option>
                                </select>
                            </div>
                        </div>

                        <!-- Nama Orang Tua & No WA -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="nama_ortu">
                                    Nama Orang Tua / Wali <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="nama_ortu" name="nama_ortu" required placeholder="Nama Ayah / Ibu" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-600 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="no_wa">
                                    Nomor WhatsApp Aktif <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" id="no_wa" name="no_wa" required placeholder="08xxxxxxxxxx" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-600 text-sm">
                            </div>
                        </div>

                        <!-- Alamat Tempat Tinggal -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="alamat">
                                Alamat Domisili Siswa <span class="text-red-500">*</span>
                            </label>
                            <textarea id="alamat" name="alamat" required rows="2" placeholder="Nama Jalan, RT/RW, Kelurahan, Kecamatan, Kota" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-600 text-sm"></textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-3">
                            <button type="submit" name="submit_ppdb" value="1" class="w-full py-4 rounded-xl font-bold text-white bg-gradient-to-r from-brand-700 via-brand-600 to-emerald-600 hover:from-brand-800 hover:to-emerald-700 shadow-lg shadow-brand-700/30 transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2 text-base">
                                <i class="fa-solid fa-paper-plane"></i>
                                <span>Kirim Formulir Pendaftaran Sekarang</span>
                            </button>
                            <p class="text-[11px] text-slate-400 text-center mt-2">
                                <i class="fa-solid fa-shield-halved text-emerald-600"></i> Data pribadi calon siswa terlindungi dengan aman.
                            </p>
                        </div>

                    </form>
                </div>
            </div>

        </div>

    </div>
</section>

<?php include 'includes/footer.php'; ?>
