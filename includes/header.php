<?php
require_once __DIR__ . '/settings.php';

// Inisialisasi Session Aman (Hardened Session for Hosting)
if (session_status() === PHP_SESSION_NONE) {
    @ini_set('session.cookie_httponly', '1');
    @ini_set('session.use_only_cookies', '1');
    $sessDir = __DIR__ . '/../data/sessions';
    if (!is_dir($sessDir)) {
        @mkdir($sessDir, 0700, true);
    }
    if (is_dir($sessDir) && is_writable($sessDir)) {
        @session_save_path($sessDir);
    }
    @session_start();
}
$isAdminLoggedIn = !empty($_SESSION['admin_logged_in']);

// Resolusi URL Dinamis untuk Hosting & Canonical (Technical SEO)
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') 
    || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) 
    || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
$protocol = $isHttps ? 'https://' : 'http://';
$httpHost = $_SERVER['HTTP_HOST'] ?? 'localhost';
$reqUri = $_SERVER['REQUEST_URI'] ?? '/';
$reqPath = parse_url($reqUri, PHP_URL_PATH) ?: '/';
$canonicalUrl = $protocol . $httpHost . $reqPath;
$siteBaseUrl = $protocol . $httpHost . rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\');

// Fallback Metadata (SEO & AI Search)
if (!isset($page_title)) {
    $page_title = "SMP Islam Al-Kholili Kota Probolinggo - Sekolah Qur'ani";
}
if (!isset($page_desc)) {
    $page_desc = "Website Resmi SMP Islam Al-Kholili Kota Probolinggo. Sekolah Menengah Pertama Islam berkarakter Qur'ani & teknologi di Kedopok. Info PPDB lengkap.";
}
if (!isset($current_page)) {
    $current_page = "beranda";
}

$logoUrl = $siteSettings['logo'] ?? 'assets/img/logo.png';
$ogImageUrl = (str_starts_with($logoUrl, 'http')) ? $logoUrl : ($siteBaseUrl . '/' . ltrim($logoUrl, '/'));
$ppdbUrl = (!empty($siteSettings['ppdb_url']) && !str_contains($siteSettings['ppdb_url'], 'ppdb.smpislam-alkholili.sch.id')) ? $siteSettings['ppdb_url'] : 'ppdb.php';
$isPpdbExternal = !empty($siteSettings['ppdb_url']) && str_starts_with($siteSettings['ppdb_url'], 'http') && !str_contains($siteSettings['ppdb_url'], 'ppdb.smpislam-alkholili.sch.id');
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>
    <link rel="icon" type="image/png" href="<?= htmlspecialchars($logoUrl) ?>">
    
    <!-- Canonical URL (Technical SEO - Anti Duplikasi URL) -->
    <link rel="canonical" href="<?= htmlspecialchars($canonicalUrl) ?>">
    
    <!-- Meta SEO & Search Engine Indexing -->
    <meta name="description" content="<?= htmlspecialchars($page_desc) ?>">
    <meta name="keywords" content="SMP Islam Al-Kholili, SMP Islam Al-Kholili Kota Probolinggo, SMP Al-Kholili Kedopok, PPDB SMP Islam Al-Kholili, Sekolah Islam Probolinggo, Pondok Pesantren Al-Kholili Kedopok">
    <meta name="author" content="SMP Islam Al-Kholili">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">

    <!-- Open Graph Protocol / Facebook / WhatsApp Preview -->
    <meta property="og:locale" content="id_ID">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="SMP Islam Al-Kholili Kota Probolinggo">
    <meta property="og:title" content="<?= htmlspecialchars($page_title) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($page_desc) ?>">
    <meta property="og:url" content="<?= htmlspecialchars($canonicalUrl) ?>">
    <meta property="og:image" content="<?= htmlspecialchars($ogImageUrl) ?>">
    <meta property="og:image:alt" content="Logo SMP Islam Al-Kholili Kota Probolinggo">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($page_title) ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($page_desc) ?>">
    <meta name="twitter:image" content="<?= htmlspecialchars($ogImageUrl) ?>">

    <!-- Local SEO & Geographic Tags (AI Citations & Google Maps) -->
    <meta name="geo.region" content="ID-JI">
    <meta name="geo.placename" content="Probolinggo, Jawa Timur">
    <meta name="geo.position" content="-7.7667;113.2167">
    <meta name="ICBM" content="-7.7667, 113.2167">

    <!-- Preconnect Fonts for Core Web Vitals (LCP Optimization) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- ========================================================================= -->
    <!-- STRUCTURED DATA (JSON-LD) - TECHNICAL SEO, AI OVERVIEWS & GOOGLE RICH SNIPPET -->
    <!-- ========================================================================= -->
    <!-- 1. School & EducationalOrganization Entity -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "School",
        "@id": "<?= htmlspecialchars($siteBaseUrl) ?>/#school",
        "name": "SMP Islam Al-Kholili",
        "alternateName": ["SMP Islam Al-Kholili Kota Probolinggo", "SMP Al-Kholili Kedopok"],
        "url": "<?= htmlspecialchars($siteBaseUrl) ?>",
        "logo": "<?= htmlspecialchars($ogImageUrl) ?>",
        "image": "<?= htmlspecialchars($ogImageUrl) ?>",
        "description": "Sekolah Menengah Pertama Islam berlandaskan nilai-nilai Qur'ani dan kurikulum nasional di Kecamatan Kedopok, Kota Probolinggo, Jawa Timur.",
        "telephone": "<?= htmlspecialchars($siteSettings['whatsapp_number']) ?>",
        "email": "<?= htmlspecialchars($siteSettings['email']) ?>",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Jl. KUA Blok Masjid Baiturrohman RT 03 / RW 04",
            "addressLocality": "Kedopok",
            "addressRegion": "Kota Probolinggo",
            "postalCode": "67228",
            "addressCountry": "ID"
        },
        "geo": {
            "@type": "GeoCoordinates",
            "latitude": -7.7667,
            "longitude": 113.2167
        },
        "openingHoursSpecification": [
            {
                "@type": "OpeningHoursSpecification",
                "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
                "opens": "07:00",
                "closes": "14:00"
            }
        ]
    }
    </script>

    <!-- 2. WebSite Entity -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "@id": "<?= htmlspecialchars($siteBaseUrl) ?>/#website",
        "url": "<?= htmlspecialchars($siteBaseUrl) ?>",
        "name": "SMP Islam Al-Kholili Kota Probolinggo"
    }
    </script>

    <?php if ($current_page === 'beranda'): ?>
    <!-- 3. FAQPage Schema (Khusus AI Overviews, Gemini, Perplexity & Google Snippets) -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "FAQPage",
        "mainEntity": [
            {
                "@type": "Question",
                "name": "Apakah pendaftaran siswa baru (PPDB) SMP Islam Al-Kholili dapat dilakukan secara online?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Ya, pendaftaran siswa baru (PPDB) SMP Islam Al-Kholili dapat dilakukan secara online melalui website resmi PPDB atau datang langsung ke ruang sekretariat panitia PPDB di Kampus SMP Islam Al-Kholili (Jl. KUA Blok Masjid Baiturrohman Kedopok, Kota Probolinggo)."
                }
            },
            {
                "@type": "Question",
                "name": "Apa saja program unggulan di SMP Islam Al-Kholili?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Program unggulan SMP Islam Al-Kholili meliputi Tahfidzul Qur'an (Juz 30 & pilihan), pembiasaan sholat berjamaah & sholat Dhuha di Masjid Baiturrohman, Kajian Kitab Dasar Akhlak, Pembelajaran Berbasis IT, serta ekstrakurikuler seni Hadrah, Pramuka, dan Olahraga."
                }
            },
            {
                "@type": "Question",
                "name": "Apakah ada beasiswa di SMP Islam Al-Kholili?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Ya, SMP Islam Al-Kholili menyediakan jalur beasiswa prestasi akademik dan non-akademik, beasiswa tahfidz Qur'an, serta program bantuan pendidikan bagi anak yatim/piatu dan siswa dari keluarga pra-sejahtera."
                }
            },
            {
                "@type": "Question",
                "name": "Di mana lokasi tepat kampus SMP Islam Al-Kholili?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "SMP Islam Al-Kholili berlokasi strategis di Jl. KUA Blok Masjid Baiturrohman, RT 03 / RW 04, Kelurahan Kedopok, Kecamatan Kedopok, Kota Probolinggo, Jawa Timur."
                }
            }
        ]
    }
    </script>
    <?php endif; ?>

    <?php if ($current_page !== 'beranda'): ?>
    <!-- 4. BreadcrumbList Schema (Technical SEO Breadcrumb Navigation) -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [
            {
                "@type": "ListItem",
                "position": 1,
                "name": "Beranda",
                "item": "<?= htmlspecialchars($siteBaseUrl) ?>/"
            },
            {
                "@type": "ListItem",
                "position": 2,
                "name": "<?= htmlspecialchars($page_title) ?>",
                "item": "<?= htmlspecialchars($canonicalUrl) ?>"
            }
        ]
    }
    </script>
    <?php endif; ?>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#effbfc',
                            100: '#d7f6f8',
                            200: '#b2ecf0',
                            300: '#7ddbe3',
                            400: '#3fc1cc',
                            500: '#1da2ad',
                            600: '#15818b',
                            700: '#11656d',
                            800: '#0c5258',
                            900: '#07484b',
                            950: '#032a2c',
                        },
                        gold: {
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        heading: ['"Poppins"', '"Plus Jakarta Sans"', 'sans-serif'],
                        poppins: ['"Poppins"', 'sans-serif'],
                        jakarta: ['"Plus Jakarta Sans"', 'sans-serif'],
                        times: ['"Times New Roman"', 'Times', 'serif']
                    }
                }
            }
        }
    </script>
    
    <!-- Custom CSS (with cache buster) -->
    <link rel="stylesheet" href="assets/css/style.css?v=2.5">
</head>
<body class="font-sans bg-slate-50 text-slate-800 antialiased selection:bg-brand-600 selection:text-white flex flex-col min-h-screen">

    <!-- Topbar Info -->
    <div class="bg-gradient-to-r from-brand-950 via-brand-900 to-brand-800 text-slate-200 text-xs py-2 px-3 sm:px-4 border-b border-brand-700/50">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-2">
            
            <!-- Lokasi & Jam Kerja -->
            <div class="flex items-center gap-4 flex-wrap justify-center md:justify-start text-center md:text-left text-[11px] sm:text-xs">
                <span class="inline-flex items-center gap-1.5 text-brand-100">
                    <i class="fa-solid fa-location-dot text-gold-400 flex-shrink-0"></i>
                    <span><?= htmlspecialchars($siteSettings['alamat']) ?></span>
                </span>
                <span class="hidden lg:inline-flex items-center gap-1.5 text-slate-300">
                    <i class="fa-regular fa-clock text-gold-400"></i>
                    <?= htmlspecialchars($siteSettings['jam_kerja']) ?>
                </span>
            </div>

            <!-- Kontak WhatsApp & Medsos -->
            <div class="flex items-center justify-center gap-2.5 sm:gap-3 flex-shrink-0">
                <!-- Tombol WhatsApp: whitespace-nowrap agar nomor HP tidak terpotong ke bawah -->
                <a href="https://wa.me/<?= htmlspecialchars($siteSettings['whatsapp_number']) ?>?text=Halo%20Admin%20SMP%20Islam%20Al-Kholili,%20saya%20ingin%20bertanya%20informasi%20sekolah" 
                   target="_blank" 
                   class="hover:text-gold-300 transition inline-flex items-center gap-1.5 font-semibold text-brand-100 whitespace-nowrap bg-brand-950/60 hover:bg-brand-900/80 px-2.5 py-1 rounded-full border border-brand-500/30 text-[11px] sm:text-xs">
                    <i class="fa-brands fa-whatsapp text-sm text-green-400 flex-shrink-0"></i>
                    <span class="tracking-wide whitespace-nowrap"><?= htmlspecialchars($siteSettings['whatsapp_display']) ?></span>
                </a>

                <span class="text-brand-600">|</span>

                <div class="flex items-center gap-2 flex-shrink-0">
                    <a href="#" class="w-6 h-6 rounded-full bg-white/10 flex items-center justify-center hover:bg-gold-500 hover:text-brand-950 transition" title="Facebook"><i class="fa-brands fa-facebook-f text-[11px]"></i></a>
                    <a href="#" class="w-6 h-6 rounded-full bg-white/10 flex items-center justify-center hover:bg-gold-500 hover:text-brand-950 transition" title="Instagram"><i class="fa-brands fa-instagram text-[11px]"></i></a>
                    <a href="#" class="w-6 h-6 rounded-full bg-white/10 flex items-center justify-center hover:bg-gold-500 hover:text-brand-950 transition" title="YouTube"><i class="fa-brands fa-youtube text-[11px]"></i></a>
                    <a href="admin/login.php" class="w-6 h-6 rounded-full bg-white/10 flex items-center justify-center hover:bg-brand-500 text-slate-300 hover:text-white transition ml-0.5" title="Login Dashboard Admin"><i class="fa-solid fa-lock text-[10px]"></i></a>
                </div>
            </div>

        </div>
    </div>

    <!-- Main Navigation Bar -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md shadow-sm border-b border-slate-100 transition-all duration-300" id="mainHeader">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Brand Logo & Name -->
                <a href="index.php" class="flex items-center gap-3.5 group">
                    <div class="relative w-12 h-12 sm:w-14 sm:h-14 p-0.5 bg-gradient-to-tr from-brand-600 to-gold-400 rounded-xl shadow-md group-hover:scale-105 transition-transform duration-300">
                        <img src="<?= htmlspecialchars($logoUrl) ?>" alt="Logo SMP Islam Al-Kholili" class="w-full h-full object-contain rounded-[10px] bg-white p-1">
                    </div>
                    <div>
                        <div class="font-heading font-extrabold text-brand-900 tracking-tight text-lg sm:text-xl leading-none flex items-center gap-1.5">
                            <?= htmlspecialchars($siteSettings['site_name']) ?>
                        </div>
                        <p class="text-[11px] sm:text-xs font-semibold text-emerald-700 tracking-wide mt-1 uppercase">
                            <?= htmlspecialchars($siteSettings['site_tagline']) ?>
                        </p>
                    </div>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden lg:flex items-center gap-1 xl:gap-2">
                    <a href="index.php" class="px-3.5 py-2 rounded-lg text-sm font-semibold transition <?= $current_page == 'beranda' ? 'text-brand-700 bg-brand-50' : 'text-slate-700 hover:text-brand-700 hover:bg-slate-50' ?>">
                        Beranda
                    </a>
                    
                    <a href="profil.php" class="px-3.5 py-2 rounded-lg text-sm font-semibold transition <?= $current_page == 'profil' ? 'text-brand-700 bg-brand-50' : 'text-slate-700 hover:text-brand-700 hover:bg-slate-50' ?>">
                        Profil
                    </a>

                    <a href="prestasi.php" class="px-3.5 py-2 rounded-lg text-sm font-semibold transition <?= $current_page == 'prestasi' ? 'text-brand-700 bg-brand-50' : 'text-slate-700 hover:text-brand-700 hover:bg-slate-50' ?>">
                        Prestasi
                    </a>

                    <a href="galeri.php" class="px-3.5 py-2 rounded-lg text-sm font-semibold transition <?= $current_page == 'galeri' ? 'text-brand-700 bg-brand-50' : 'text-slate-700 hover:text-brand-700 hover:bg-slate-50' ?>">
                        Galeri
                    </a>

                    <a href="guru.php" class="px-3.5 py-2 rounded-lg text-sm font-semibold transition <?= $current_page == 'guru' ? 'text-brand-700 bg-brand-50' : 'text-slate-700 hover:text-brand-700 hover:bg-slate-50' ?>">
                        Guru
                    </a>

                    <a href="berita.php" class="px-3.5 py-2 rounded-lg text-sm font-semibold transition <?= $current_page == 'berita' ? 'text-brand-700 bg-brand-50' : 'text-slate-700 hover:text-brand-700 hover:bg-slate-50' ?>">
                        Berita
                    </a>

                    <a href="index.php#faq" class="px-3.5 py-2 rounded-lg text-sm font-semibold text-slate-700 hover:text-brand-700 hover:bg-slate-50 transition">
                        FAQ
                    </a>

                    <a href="kontak.php" class="px-3 py-2 rounded-lg text-sm font-semibold transition <?= $current_page == 'kontak' ? 'text-brand-700 bg-brand-50' : 'text-slate-700 hover:text-brand-700 hover:bg-slate-50' ?>">
                        Kontak
                    </a>

                    <!-- Fitur Admin di Navigasi Utama: Selalu Membuka Halaman Login -->
                    <a href="admin/login.php" class="px-3 py-2 rounded-lg text-sm font-bold text-slate-700 hover:text-brand-700 hover:bg-slate-50 border border-slate-200 hover:border-brand-300 transition flex items-center gap-1.5" title="Login Administrator">
                        <i class="fa-solid fa-lock text-xs text-brand-600"></i>
                        <span>Admin</span>
                    </a>

                    <!-- PPDB Button CTA (Direct Link to PPDB Web) -->
                    <a href="<?= htmlspecialchars($ppdbUrl) ?>" <?= $isPpdbExternal ? 'target="_blank" rel="noopener noreferrer"' : '' ?> class="ml-1 inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-brand-700 via-brand-600 to-emerald-600 hover:from-brand-800 hover:to-emerald-700 shadow-md shadow-brand-700/20 hover:shadow-lg hover:shadow-brand-700/30 transition transform hover:-translate-y-0.5" title="Buka Website PPDB Online">
                        <i class="fa-solid fa-graduation-cap"></i>
                        <span>PPDB Online</span>
                        <span class="flex h-2 w-2 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-gold-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-gold-400"></span>
                        </span>
                        <?php if ($isPpdbExternal): ?>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-emerald-200"></i>
                        <?php endif; ?>
                    </a>
                </nav>

                <!-- Mobile Menu Button -->
                <div class="flex items-center gap-2 lg:hidden">
                    <a href="admin/login.php" class="px-2.5 py-1.5 rounded-lg text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-300 flex items-center gap-1" title="Dasbor Admin">
                        <i class="fa-solid fa-lock text-[10px] text-brand-700"></i>
                        <span>Admin</span>
                    </a>
                    <a href="<?= htmlspecialchars($ppdbUrl) ?>" <?= $isPpdbExternal ? 'target="_blank" rel="noopener noreferrer"' : '' ?> class="px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-brand-700 hover:bg-brand-800 shadow-sm flex items-center gap-1">
                        <span>PPDB</span>
                        <?php if ($isPpdbExternal): ?><i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i><?php endif; ?>
                    </a>
                    <button id="mobileMenuBtn" type="button" class="p-2 rounded-lg text-slate-600 hover:text-brand-700 hover:bg-slate-100 focus:outline-none" aria-label="Toggle Navigation">
                        <i class="fa-solid fa-bars text-xl" id="menuIcon"></i>
                    </button>
                </div>

            </div>
        </div>

        <!-- Mobile Navigation Menu Dropdown -->
        <div id="mobileMenu" class="hidden lg:hidden bg-white border-b border-slate-200 px-4 pt-3 pb-6 space-y-2 shadow-xl">
            <a href="index.php" class="block px-3 py-2.5 rounded-lg text-base font-semibold <?= $current_page == 'beranda' ? 'bg-brand-50 text-brand-700' : 'text-slate-700 hover:bg-slate-50' ?>">
                <i class="fa-solid fa-house-chimney w-6 text-brand-600"></i> Beranda
            </a>
            <a href="profil.php" class="block px-3 py-2.5 rounded-lg text-base font-semibold <?= $current_page == 'profil' ? 'bg-brand-50 text-brand-700' : 'text-slate-700 hover:bg-slate-50' ?>">
                <i class="fa-solid fa-school w-6 text-brand-600"></i> Profil Sekolah
            </a>
            <a href="prestasi.php" class="block px-3 py-2.5 rounded-lg text-base font-semibold <?= $current_page == 'prestasi' ? 'bg-brand-50 text-brand-700' : 'text-slate-700 hover:bg-slate-50' ?>">
                <i class="fa-solid fa-trophy w-6 text-brand-600"></i> Prestasi
            </a>
            <a href="galeri.php" class="block px-3 py-2.5 rounded-lg text-base font-semibold <?= $current_page == 'galeri' ? 'bg-brand-50 text-brand-700' : 'text-slate-700 hover:bg-slate-50' ?>">
                <i class="fa-solid fa-images w-6 text-brand-600"></i> Galeri Kegiatan
            </a>
            <a href="guru.php" class="block px-3 py-2.5 rounded-lg text-base font-semibold <?= $current_page == 'guru' ? 'bg-brand-50 text-brand-700' : 'text-slate-700 hover:bg-slate-50' ?>">
                <i class="fa-solid fa-chalkboard-user w-6 text-brand-600"></i> Guru
            </a>
            <a href="berita.php" class="block px-3 py-2.5 rounded-lg text-base font-semibold <?= $current_page == 'berita' ? 'bg-brand-50 text-brand-700' : 'text-slate-700 hover:bg-slate-50' ?>">
                <i class="fa-solid fa-newspaper w-6 text-brand-600"></i> Berita
            </a>
            <a href="index.php#faq" class="block px-3 py-2.5 rounded-lg text-base font-semibold text-slate-700 hover:bg-slate-50">
                <i class="fa-solid fa-circle-question w-6 text-brand-600"></i> FAQ (Tanya Jawab)
            </a>
            <a href="kontak.php" class="block px-3 py-2.5 rounded-lg text-base font-semibold <?= $current_page == 'kontak' ? 'bg-brand-50 text-brand-700' : 'text-slate-700 hover:bg-slate-50' ?>">
                <i class="fa-solid fa-address-book w-6 text-brand-600"></i> Kontak Kami
            </a>
            <div class="pt-2">
                <a href="<?= htmlspecialchars($ppdbUrl) ?>" <?= $isPpdbExternal ? 'target="_blank" rel="noopener noreferrer"' : '' ?> class="w-full text-center flex items-center justify-center gap-2 px-4 py-3 rounded-xl text-base font-bold text-white bg-gradient-to-r from-brand-700 to-emerald-600 shadow-md">
                    <i class="fa-solid fa-graduation-cap"></i> Buka Website PPDB Online
                    <?php if ($isPpdbExternal): ?><i class="fa-solid fa-arrow-up-right-from-square text-xs"></i><?php endif; ?>
                </a>
            </div>
            <div class="pt-2 border-t border-slate-100">
                <a href="<?= $isAdminLoggedIn ? 'admin/index.php' : 'admin/login.php' ?>" class="w-full text-center flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl text-sm font-bold text-slate-800 bg-slate-100 hover:bg-slate-200 border border-slate-300 transition">
                    <i class="fa-solid fa-shield-halved text-brand-700"></i>
                    <span><?= $isAdminLoggedIn ? 'Masuk ke Dasbor Admin (Aktif)' : 'Login Admin Sekolah' ?></span>
                </a>
            </div>
        </div>
    </header>
