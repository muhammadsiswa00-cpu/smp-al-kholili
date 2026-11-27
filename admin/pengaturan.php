<?php
$admin_page = "pengaturan";
$admin_title = "Pengaturan Website & Tampilan";
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../includes/settings.php';

/** @var array<string, mixed> $siteSettings */
global $siteSettings;
if (!isset($siteSettings) || !is_array($siteSettings)) {
    $siteSettings = get_all_settings();
}

$logoRaw = (string)($siteSettings['logo'] ?? 'assets/img/logo.png');
$previewLogo = (str_starts_with($logoRaw, 'data:') || str_starts_with($logoRaw, 'http')) ? $logoRaw : '../' . ltrim($logoRaw, '/');

$heroBgRaw = (string)($siteSettings['hero_bg'] ?? 'assets/img/hero-bg.svg');
$previewHeroBg = (str_starts_with($heroBgRaw, 'data:') || str_starts_with($heroBgRaw, 'http')) ? $heroBgRaw : '../' . ltrim($heroBgRaw, '/');

$kepsekRaw = (string)($siteSettings['kepala_sekolah_foto'] ?? 'assets/img/kepala-sekolah.svg');
$previewKepsek = (str_starts_with($kepsekRaw, 'data:') || str_starts_with($kepsekRaw, 'http')) ? $kepsekRaw : '../' . ltrim($kepsekRaw, '/');

$success = "";
$error = "";

// Handle Form Submission
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['save_settings'])) {
    
    $newData = [
        "site_name" => trim($_POST['site_name'] ?? $siteSettings['site_name']),
        "site_tagline" => trim($_POST['site_tagline'] ?? $siteSettings['site_tagline']),
        "hero_title" => trim($_POST['hero_title'] ?? $siteSettings['hero_title']),
        "hero_subtitle" => trim($_POST['hero_subtitle'] ?? $siteSettings['hero_subtitle']),
        "nama_kepala_sekolah" => trim($_POST['nama_kepala_sekolah'] ?? ($siteSettings['nama_kepala_sekolah'] ?? 'Nama Kepala Sekolah')),
        "ppdb_url" => trim($_POST['ppdb_url'] ?? $siteSettings['ppdb_url']),
        "whatsapp_number" => trim($_POST['whatsapp_number'] ?? $siteSettings['whatsapp_number']),
        "whatsapp_display" => trim($_POST['whatsapp_display'] ?? $siteSettings['whatsapp_display']),
        "email" => trim($_POST['email'] ?? $siteSettings['email']),
        "alamat" => trim($_POST['alamat'] ?? $siteSettings['alamat']),
        "jam_kerja" => trim($_POST['jam_kerja'] ?? $siteSettings['jam_kerja'])
    ];

    // 1. Process Logo Upload if provided
    if (isset($_FILES['logo_file']) && $_FILES['logo_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmp = $_FILES['logo_file']['tmp_name'];
        $fileName = $_FILES['logo_file']['name'];
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExts = ['png', 'jpg', 'jpeg', 'webp', 'svg'];

        if (in_array($ext, $allowedExts)) {
            $newLogoName = "logo_" . time() . "." . $ext;
            $destPath = __DIR__ . "/../assets/uploads/" . $newLogoName;
            if (move_uploaded_file($fileTmp, $destPath)) {
                $newData['logo'] = "assets/uploads/" . $newLogoName;
            } else {
                $error .= "Gagal memindahkan file logo yang diunggah. ";
            }
        } else {
            $error .= "Format file logo harus berupa PNG, JPG, WEBP, atau SVG. ";
        }
    }

    // 2. Process Hero Background Upload if provided
    if (isset($_FILES['hero_bg_file']) && $_FILES['hero_bg_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmp = $_FILES['hero_bg_file']['tmp_name'];
        $fileName = $_FILES['hero_bg_file']['name'];
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'svg'];

        if (in_array($ext, $allowedExts)) {
            $newBgName = "hero_bg_" . time() . "." . $ext;
            $destPath = __DIR__ . "/../assets/uploads/" . $newBgName;
            if (move_uploaded_file($fileTmp, $destPath)) {
                $newData['hero_bg'] = "assets/uploads/" . $newBgName;
            } else {
                $error .= "Gagal memindahkan file foto background yang diunggah. ";
            }
        } else {
            $error .= "Format file background harus berupa JPG, PNG, atau WEBP. ";
        }
    }

    // 3. Process Foto Kepala Sekolah Upload if provided
    if (isset($_FILES['kepsek_file']) && $_FILES['kepsek_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmp = $_FILES['kepsek_file']['tmp_name'];
        $fileName = $_FILES['kepsek_file']['name'];
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'svg'];

        if (in_array($ext, $allowedExts)) {
            $newKepsekName = "kepsek_" . time() . "." . $ext;
            $destPath = __DIR__ . "/../assets/uploads/" . $newKepsekName;
            if (move_uploaded_file($fileTmp, $destPath)) {
                $newData['kepala_sekolah_foto'] = "assets/uploads/" . $newKepsekName;
            } else {
                $error .= "Gagal memindahkan file foto kepala sekolah. ";
            }
        } else {
            $error .= "Format foto kepala sekolah harus JPG, PNG, atau WEBP. ";
        }
    }

    if (isset($_POST['sambutan_teks'])) {
        $newData['sambutan_teks'] = trim($_POST['sambutan_teks']);
    }

    // 4. Process Change Password if provided
    $newPass = trim($_POST['new_password'] ?? '');
    if (!empty($newPass)) {
        if (strlen($newPass) >= 6) {
            $newData['admin_password_hash'] = password_hash($newPass, PASSWORD_BCRYPT);
        } else {
            $error .= "Kata sandi baru minimal 6 karakter. ";
        }
    }

    // Save Settings
    if (empty($error)) {
        if (save_settings($newData)) {
            $success = "Pengaturan berhasil disimpan dan langsung diterapkan ke website!";
            // Reload updated settings
            $siteSettings = get_all_settings();
        } else {
            $error = "Gagal menyimpan ke file data/settings.json. Periksa izin akses folder data.";
        }
    }
}
?>

<div class="max-w-5xl mx-auto space-y-8">
    
    <!-- Success & Error Alerts -->
    <?php if (!empty($success)): ?>
    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-300 text-brand-900 flex items-center gap-3 shadow-sm animate-fade-in">
        <i class="fa-solid fa-circle-check text-2xl text-emerald-600 flex-shrink-0"></i>
        <div class="flex-1 text-sm font-semibold"><?= htmlspecialchars($success) ?></div>
        <a href="../index.php" target="_blank" class="px-3 py-1.5 rounded-lg bg-brand-700 text-white text-xs font-bold hover:bg-brand-800 transition">
            Lihat Hasil di Website &rarr;
        </a>
    </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
    <div class="p-4 rounded-2xl bg-red-50 border border-red-300 text-red-800 flex items-center gap-3 shadow-sm">
        <i class="fa-solid fa-triangle-exclamation text-2xl text-red-600 flex-shrink-0"></i>
        <div class="text-sm font-semibold"><?= htmlspecialchars($error) ?></div>
    </div>
    <?php endif; ?>

    <form action="pengaturan.php" method="POST" enctype="multipart/form-data" class="space-y-8">
        
        <!-- SECTION 1: UBAH LOGO & LATAR BELAKANG (BEGROND) -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-emerald-50 text-brand-700">
                    Tampilan Visual
                </span>
                <h2 class="text-xl font-heading font-extrabold text-slate-900 mt-1">
                    Ubah Logo Sekolah & Foto Begrond Hero
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Ganti file logo resmi dan latar belakang foto pada bagian selamat datang beranda.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- GANTI LOGO -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col justify-between">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            1. Logo Sekolah
                        </label>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-16 h-16 p-2 bg-white rounded-2xl shadow border border-slate-200 flex items-center justify-center flex-shrink-0">
                                <img src="<?= htmlspecialchars($previewLogo) ?>" alt="Logo Saat Ini" class="w-full h-full object-contain">
                            </div>
                            <div>
                                <span class="text-xs font-semibold text-slate-700 block">Logo Saat Ini</span>
                                <span class="text-[10px] text-slate-400 font-mono block break-all">Tersimpan di Database</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1" for="logo_file">
                            Upload Logo Baru:
                        </label>
                        <input type="file" id="logo_file" name="logo_file" accept="image/*" class="w-full text-xs text-slate-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-brand-700 file:text-white hover:file:bg-brand-800 cursor-pointer">
                    </div>
                </div>

                <!-- GANTI BEGROND FOTO -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col justify-between">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            2. Foto Begrond Hero
                        </label>
                        <div class="rounded-xl overflow-hidden h-16 relative mb-4 border border-slate-300 bg-slate-200">
                            <div class="w-full h-full" style="background: url('<?= htmlspecialchars($previewHeroBg) ?>') center/cover no-repeat;"></div>
                            <div class="absolute bottom-1 right-2 bg-black/70 text-white text-[9px] font-mono px-1.5 py-0.5 rounded">
                                Begrond
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1" for="hero_bg_file">
                            Upload Begrond Baru:
                        </label>
                        <input type="file" id="hero_bg_file" name="hero_bg_file" accept="image/*" class="w-full text-xs text-slate-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-brand-700 file:text-white hover:file:bg-brand-800 cursor-pointer">
                    </div>
                </div>

                <!-- GANTI FOTO KEPALA SEKOLAH -->
                <div class="p-5 rounded-2xl bg-emerald-50/60 border border-emerald-200 flex flex-col justify-between">
                    <div>
                        <label class="block text-xs font-bold text-brand-900 uppercase tracking-wider mb-2">
                            3. Foto Kepala Sekolah
                        </label>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-16 h-16 rounded-2xl shadow border border-emerald-300 overflow-hidden bg-white flex items-center justify-center flex-shrink-0">
                                <img src="<?= htmlspecialchars($previewKepsek) ?>" alt="Foto Kepala Sekolah" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <span class="text-xs font-semibold text-brand-900 block">Foto Kepala Sekolah</span>
                                <span class="text-[10px] text-slate-500 font-mono block break-all">Tersimpan di Database</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-brand-900 mb-1" for="kepsek_file">
                            Upload Foto Kepala Sekolah:
                        </label>
                        <input type="file" id="kepsek_file" name="kepsek_file" accept="image/*" class="w-full text-xs text-slate-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-emerald-700 file:text-white hover:file:bg-emerald-800 cursor-pointer">
                    </div>
                </div>

            </div>
        </div>

        <!-- SECTION: SAMBUTAN KEPALA SEKOLAH -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-emerald-100 text-brand-800">
                    Sambutan Pimpinan
                </span>
                <h2 class="text-xl font-heading font-extrabold text-slate-900 mt-1 flex items-center gap-2">
                    <i class="fa-solid fa-bullhorn text-emerald-600"></i> Profil & Sambutan Kepala Sekolah
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Nama dan teks ini tampil di halaman Profil dan Beranda website.
                </p>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="nama_kepala_sekolah">
                        Nama Lengkap & Gelar Kepala Sekolah <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nama_kepala_sekolah" name="nama_kepala_sekolah" required value="<?= htmlspecialchars($siteSettings['nama_kepala_sekolah'] ?? 'Nama Kepala Sekolah') ?>" placeholder="Contoh: H. Achmad Fauzi, S.Pd.I., M.Pd." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-600 text-sm font-bold text-slate-800">
                    <p class="text-[11px] text-slate-400 mt-1">Nama ini tampil pada lencana di bawah foto Kepala Sekolah.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="sambutan_teks">
                        Isi Sambutan Kepala Sekolah
                    </label>
                    <textarea id="sambutan_teks" name="sambutan_teks" rows="8" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-600 text-sm text-slate-800 leading-relaxed font-sans"><?= htmlspecialchars($siteSettings['sambutan_teks'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- SECTION 2: TEKS HERO (SELAMAT DATANG DI SMP ISLAM AL-KHOLILI) -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-gold-100 text-gold-800">
                    Teks Utama
                </span>
                <h2 class="text-xl font-heading font-extrabold text-slate-900 mt-1">
                    Teks Selamat Datang & Subtitle Hero
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Sesuaikan judul selamat datang dan kalimat pengantar di halaman depan.
                </p>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="hero_title">
                        Judul Utama Selamat Datang <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="hero_title" name="hero_title" required value="<?= htmlspecialchars($siteSettings['hero_title']) ?>" placeholder="Selamat Datang di SMP Islam Al-Kholili" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-600 text-sm font-semibold text-slate-800">
                    <p class="text-[11px] text-slate-400 mt-1">
                        Sesuai permintaan Anda, teks ini kini otomatis tampil sebagai: <strong>"Selamat Datang di SMP Islam Al-Kholili"</strong>.
                    </p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="hero_subtitle">
                        Deskripsi / Subtitle Kalimat Pengantar
                    </label>
                    <textarea id="hero_subtitle" name="hero_subtitle" rows="2" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-600 text-sm text-slate-800"><?= htmlspecialchars($siteSettings['hero_subtitle']) ?></textarea>
                </div>
            </div>
        </div>

        <!-- SECTION 3: CANTUMKAN LINK WEBSITE PPDB ANDA -->
        <div id="ppdb_section" class="bg-white rounded-3xl p-6 sm:p-8 border-2 border-amber-300 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-amber-100 text-amber-900">
                    Integrasi PPDB Eksternal
                </span>
                <h2 class="text-xl font-heading font-extrabold text-slate-900 mt-1 flex items-center gap-2">
                    <i class="fa-solid fa-graduation-cap text-gold-500"></i> Cantumkan Website PPDB Anda
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Masukkan alamat/URL website PPDB yang sudah Anda miliki agar semua tombol PPDB di website ini langsung mengarah ke sana.
                </p>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="ppdb_url">
                        Alamat URL Website PPDB Anda <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center gap-2">
                        <div class="relative flex-1">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                                <i class="fa-solid fa-link text-sm"></i>
                            </span>
                            <input type="url" id="ppdb_url" name="ppdb_url" required value="<?= htmlspecialchars($siteSettings['ppdb_url']) ?>" placeholder="https://..." class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-600 text-sm font-mono text-brand-900 font-semibold">
                        </div>
                        <?php if (!empty($siteSettings['ppdb_url'])): ?>
                        <a href="<?= htmlspecialchars($siteSettings['ppdb_url']) ?>" target="_blank" class="px-4 py-3 rounded-xl font-bold text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 transition flex items-center gap-1.5 flex-shrink-0" title="Buka tautan PPDB">
                            <span>Uji Link</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                    <p class="text-[11px] text-slate-500 mt-1">
                        Gunakan <code>ppdb.php</code> untuk formulir bawaan website, atau masukkan link eksternal (seperti Google Form).
                    </p>
                </div>
            </div>
        </div>

        <!-- SECTION 4: INFORMASI KONTAK & ALAMAT SEKOLAH -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-blue-100 text-blue-900">
                    Informasi & Kontak
                </span>
                <h2 class="text-xl font-heading font-extrabold text-slate-900 mt-1">
                    Kontak & Alamat Sekolah
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Informasi alamat Kedopok, nomor WhatsApp resmi, dan email sekolah.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1" for="site_name">
                        Nama Sekolah
                    </label>
                    <input type="text" id="site_name" name="site_name" value="<?= htmlspecialchars($siteSettings['site_name']) ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1" for="site_tagline">
                        Tagline / Sub Nama
                    </label>
                    <input type="text" id="site_tagline" name="site_tagline" value="<?= htmlspecialchars($siteSettings['site_tagline']) ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1" for="whatsapp_number">
                        Nomor WhatsApp (Format: 628xxx)
                    </label>
                    <input type="text" id="whatsapp_number" name="whatsapp_number" value="<?= htmlspecialchars($siteSettings['whatsapp_number']) ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1" for="whatsapp_display">
                        Teks Tampilan WhatsApp
                    </label>
                    <input type="text" id="whatsapp_display" name="whatsapp_display" value="<?= htmlspecialchars($siteSettings['whatsapp_display']) ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1" for="email">
                        Email Resmi Sekolah
                    </label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($siteSettings['email']) ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1" for="jam_kerja">
                        Jam Pelayanan / Kerja
                    </label>
                    <input type="text" id="jam_kerja" name="jam_kerja" value="<?= htmlspecialchars($siteSettings['jam_kerja']) ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1" for="alamat">
                        Alamat Lengkap Sekolah
                    </label>
                    <textarea id="alamat" name="alamat" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm"><?= htmlspecialchars($siteSettings['alamat']) ?></textarea>
                </div>
            </div>
        </div>

        <!-- SECTION 5: KATA SANDI ADMIN -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-4">
            <div class="border-b border-slate-100 pb-3">
                <h3 class="text-base font-heading font-bold text-slate-900">
                    Keamanan Akun Admin
                </h3>
                <p class="text-xs text-slate-500">Kosongkan jika tidak ingin mengubah kata sandi.</p>
            </div>
            <div class="max-w-md">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1" for="new_password">
                    Kata Sandi Baru (Minimal 6 Karakter)
                </label>
                <input type="password" id="new_password" name="new_password" placeholder="Biarkan kosong jika tidak diubah" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm">
            </div>
        </div>

        <!-- SAVE BUTTON BAR -->
        <div class="sticky bottom-4 z-20 bg-white/95 backdrop-blur-md p-4 rounded-2xl border border-slate-200 shadow-xl flex items-center justify-between gap-4">
            <span class="text-xs text-slate-500 hidden sm:inline">
                Pastikan data yang dimasukkan sudah benar sebelum menyimpan.
            </span>
            <button type="submit" name="save_settings" value="1" class="w-full sm:w-auto px-8 py-3.5 rounded-xl font-bold text-white bg-gradient-to-r from-brand-700 via-brand-600 to-emerald-600 hover:from-brand-800 hover:to-emerald-700 shadow-lg shadow-brand-700/30 transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2 text-sm">
                <i class="fa-solid fa-floppy-disk text-base"></i>
                <span>Simpan Semua Perubahan</span>
            </button>
        </div>

    </form>

</div>

<?php require_once __DIR__ . '/footer.php'; ?>
