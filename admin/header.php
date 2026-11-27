<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../includes/settings.php';

/** @var array<string, mixed> $siteSettings */
global $siteSettings;
if (!isset($siteSettings) || !is_array($siteSettings)) {
    $siteSettings = get_all_settings();
}

$rawLogo = (string)($siteSettings['logo'] ?? 'assets/img/logo.png');
$logoUrl = (str_starts_with($rawLogo, 'data:') || str_starts_with($rawLogo, 'http')) 
    ? $rawLogo 
    : '../' . ltrim($rawLogo, '/');

if (!isset($admin_page)) {
    $admin_page = "dashboard";
}

$userRole = $_SESSION['admin_role'] ?? 'admin';

// Proteksi hak akses role Guru: Hanya boleh mengakses siswa, absen siswa, dan absen guru
$guruAllowedPages = ['siswa', 'absen_siswa', 'absen_guru'];
if ($userRole === 'guru' && !in_array($admin_page, $guruAllowedPages)) {
    header("Location: siswa.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($admin_title ?? 'Dasbor Admin') ?> - SMP Islam Al-Kholili</title>
    <link rel="icon" type="image/png" href="<?= htmlspecialchars($logoUrl) ?>">
    <!-- Google Fonts & Tailwind -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                            950: '#022c22',
                        },
                        gold: {
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        heading: ['"Outfit"', 'sans-serif']
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-100 font-sans text-slate-800 antialiased min-h-screen flex flex-col md:flex-row">

    <!-- Mobile Top Navigation Bar -->
    <div class="md:hidden bg-brand-950 text-white p-4 flex items-center justify-between border-b border-brand-800">
        <div class="flex items-center gap-2">
            <img src="<?= htmlspecialchars($logoUrl) ?>" alt="Logo" class="w-8 h-8 object-contain bg-white rounded p-0.5">
            <span class="font-heading font-bold text-sm">Admin Al-Kholili</span>
        </div>
        <button id="adminMobileBtn" class="text-white focus:outline-none">
            <i class="fa-solid fa-bars text-lg"></i>
        </button>
    </div>

    <!-- Sidebar Navigation -->
    <aside id="adminSidebar" class="hidden md:flex flex-col w-full md:w-64 bg-brand-950 text-slate-300 border-r border-brand-800/60 p-5 space-y-6 flex-shrink-0">
        
        <!-- Brand Logo & School Name -->
        <div class="flex items-center gap-3 pb-5 border-b border-brand-800/80">
            <div class="w-12 h-12 p-1 bg-white rounded-xl shadow flex items-center justify-center flex-shrink-0">
                <img src="<?= htmlspecialchars($logoUrl) ?>" alt="Logo" class="w-full h-full object-contain">
            </div>
            <div>
                <h2 class="text-white font-heading font-extrabold text-sm leading-tight">SMP ISLAM AL-KHOLILI</h2>
                <span class="text-[10px] <?= $userRole === 'guru' ? 'text-emerald-300' : 'text-gold-400' ?> font-bold uppercase tracking-wider">
                    <?= $userRole === 'guru' ? 'Portal Dewan Guru' : 'Panel Administrator' ?>
                </span>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="space-y-1.5 flex-1 text-sm font-medium">
            
            <?php if ($userRole !== 'guru'): ?>
            <a href="index.php" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition <?= $admin_page == 'dashboard' ? 'bg-brand-700 text-white shadow font-bold' : 'hover:bg-brand-900/80 hover:text-white' ?>">
                <i class="fa-solid fa-chart-pie w-5 text-center text-gold-400"></i>
                <span>Ringkasan Dasbor</span>
            </a>

            <!-- Highlighted: Pengaturan Website (Logo, Background, Teks, Link PPDB) -->
            <a href="pengaturan.php" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition <?= $admin_page == 'pengaturan' ? 'bg-brand-700 text-white shadow font-bold' : 'hover:bg-brand-900/80 hover:text-white' ?>">
                <i class="fa-solid fa-sliders w-5 text-center text-gold-400"></i>
                <div class="flex-1">
                    <span>Pengaturan Website</span>
                    <span class="block text-[10px] text-emerald-300 font-normal">Logo, Begrond & PPDB</span>
                </div>
            </a>

            <!-- Kelola Galeri Foto -->
            <a href="galeri.php" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition <?= $admin_page == 'galeri' ? 'bg-brand-700 text-white shadow font-bold' : 'hover:bg-brand-900/80 hover:text-white' ?>">
                <i class="fa-solid fa-images w-5 text-center text-gold-400"></i>
                <span>Upload & Kelola Galeri</span>
            </a>

            <!-- Kelola Guru -->
            <a href="guru.php" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition <?= $admin_page == 'guru' ? 'bg-brand-700 text-white shadow font-bold' : 'hover:bg-brand-900/80 hover:text-white' ?>">
                <i class="fa-solid fa-chalkboard-user w-5 text-center text-gold-400"></i>
                <span>Data & Foto Guru</span>
            </a>

            <!-- Kelola Berita -->
            <a href="berita.php" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition <?= $admin_page == 'berita' ? 'bg-brand-700 text-white shadow font-bold' : 'hover:bg-brand-900/80 hover:text-white' ?>">
                <i class="fa-solid fa-newspaper w-5 text-center text-gold-400"></i>
                <span>Berita Sekolah</span>
            </a>

            <!-- Data Pendaftar PPDB -->
            <a href="data_ppdb.php" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition <?= $admin_page == 'ppdb' ? 'bg-brand-700 text-white shadow font-bold' : 'hover:bg-brand-900/80 hover:text-white' ?>">
                <i class="fa-solid fa-user-graduate w-5 text-center text-gold-400"></i>
                <span>Data Masuk PPDB</span>
            </a>
            <?php endif; ?>

            <!-- Data Siswa Aktif (Tampil untuk Guru & Admin) -->
            <a href="siswa.php" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition <?= $admin_page == 'siswa' ? 'bg-brand-700 text-white shadow font-bold' : 'hover:bg-brand-900/80 hover:text-white' ?>">
                <i class="fa-solid fa-users w-5 text-center text-gold-400"></i>
                <span>Data Siswa</span>
            </a>

            <!-- Absen Siswa (Tampil untuk Guru & Admin) -->
            <a href="absen_siswa.php" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition <?= $admin_page == 'absen_siswa' ? 'bg-brand-700 text-white shadow font-bold' : 'hover:bg-brand-900/80 hover:text-white' ?>">
                <i class="fa-solid fa-clipboard-user w-5 text-center text-gold-400"></i>
                <span>Absen Siswa</span>
            </a>

            <!-- Absen Guru (Tampil untuk Guru & Admin) -->
            <a href="absen_guru.php" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition <?= $admin_page == 'absen_guru' ? 'bg-brand-700 text-white shadow font-bold' : 'hover:bg-brand-900/80 hover:text-white' ?>">
                <i class="fa-solid fa-user-check w-5 text-center text-gold-400"></i>
                <span>Absen Guru</span>
            </a>

        </nav>

        <!-- Quick External Link & Logout -->
        <div class="pt-5 border-t border-brand-800/80 space-y-2 text-xs">
            <a href="../index.php" target="_blank" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-emerald-200 transition">
                <span class="flex items-center gap-2"><i class="fa-solid fa-globe"></i> Lihat Website Utama</span>
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
            </a>

            <a href="logout.php" onclick="return confirm('Yakin ingin keluar dari dasbor admin?')" class="w-full flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-red-950/50 hover:bg-red-900 text-red-200 transition">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Keluar (Logout)</span>
            </a>
        </div>

    </aside>

    <!-- Main Admin Content Area -->
    <main class="flex-1 flex flex-col min-w-0">
        
        <!-- Admin Topbar -->
        <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between shadow-sm">
            <div>
                <h1 class="font-heading font-extrabold text-xl text-slate-800"><?= htmlspecialchars($admin_title ?? 'Dasbor Admin') ?></h1>
                <p class="text-xs text-slate-500">Kelola dan perbarui konten website SMP Islam Al-Kholili</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-xs font-semibold px-3 py-1 bg-emerald-50 text-brand-700 rounded-full border border-emerald-200">
                    <i class="fa-solid <?= $userRole === 'guru' ? 'fa-chalkboard-user' : 'fa-circle-user' ?> mr-1"></i> <?= $userRole === 'guru' ? 'Guru Aktif' : 'Admin Aktif' ?>
                </span>
                <a href="../index.php" target="_blank" class="text-xs font-bold text-brand-700 hover:text-brand-800 bg-brand-50 hover:bg-brand-100 px-3 py-1.5 rounded-lg transition inline-flex items-center gap-1.5">
                    <span>Buka Web</span> <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                </a>
            </div>
        </header>

        <!-- Dynamic Content Body -->
        <div class="p-6 md:p-8 flex-1 overflow-y-auto">
