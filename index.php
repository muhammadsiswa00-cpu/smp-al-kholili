<?php
require_once __DIR__ . '/includes/settings.php';
$siteSettings = get_all_settings();

$page_title = "SMP Islam Al-Kholili Kota Probolinggo - Sekolah Qur'ani";
$page_desc = "Website Resmi SMP Islam Al-Kholili Kota Probolinggo. Sekolah Menengah Pertama Islam berkarakter Qur'ani & teknologi di Kedopok. Info PPDB lengkap.";
$current_page = "beranda";
include 'includes/header.php';

$heroBg = $siteSettings['hero_bg'] ?? 'assets/img/hero-bg.svg';
$heroTitle = $siteSettings['hero_title'] ?? 'Selamat Datang di SMP Islam Al-Kholili';
$heroSubtitle = $siteSettings['hero_subtitle'] ?? 'Mewujudkan generasi Muslim yang kokoh dalam akidah, unggul dalam prestasi akademik, santun dalam budi pekerti, serta terampil dalam penguasaan teknologi.';
$logoImg = $siteSettings['logo'] ?? 'assets/img/logo.png';
$ppdbLink = (!empty($siteSettings['ppdb_url']) && !str_contains($siteSettings['ppdb_url'], 'ppdb.smpislam-alkholili.sch.id')) ? $siteSettings['ppdb_url'] : 'ppdb.php';
$isPpdbExt = !empty($siteSettings['ppdb_url']) && str_starts_with($siteSettings['ppdb_url'], 'http') && !str_contains($siteSettings['ppdb_url'], 'ppdb.smpislam-alkholili.sch.id');
?>

<!-- ================= HERO SECTION WITH PHOTO BACKGROUND ================= -->
<section class="relative text-white overflow-hidden py-16 lg:py-24 bg-[#07484b]" 
         style="background: linear-gradient(135deg, rgba(3, 42, 44, 0.96) 0%, rgba(7, 72, 75, 0.90) 50%, rgba(15, 95, 102, 0.85) 100%), url('<?= htmlspecialchars($heroBg) ?>') center/cover no-repeat;">
    
    <!-- Decorative Lighting Circles -->
    <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-[#1da2ad]/20 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full bg-gold-500/20 blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <!-- Left Column: Headline & CTA -->
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                
                <!-- Main Headline: Highly Aesthetic Islamic & Modern Luxury Typography -->
                <div class="space-y-3">
                    <!-- Elegant Kicker / Eyebrow -->
                    <div class="inline-flex items-center gap-2 text-xs uppercase tracking-[0.22em] font-semibold text-[#9be4e9] justify-center lg:justify-start">
                        <span class="w-8 h-[1.5px] bg-gradient-to-r from-transparent to-gold-400"></span>
                        <i class="fa-solid fa-star-of-life text-[10px] text-gold-400 animate-spin-slow"></i>
                        <span>Website Resmi &bull; Berkarakter Qur'ani</span>
                        <span class="w-8 h-[1.5px] bg-gradient-to-l from-transparent to-gold-400"></span>
                    </div>

                    <h1 class="leading-[1.2] sm:leading-[1.15]">
                        <!-- Baris 1: Selamat Datang (Times New Roman - Animated Crystal White) -->
                        <span class="block">
                            <span class="font-times font-bold text-2xl sm:text-3xl lg:text-4xl text-white animate-moving-silver">
                                Selamat Datang
                            </span>
                        </span>
                        
                        <!-- Baris 2: DI SMP ISLAM AL-KHOLILI (Times New Roman - Satu Baris Lurus / No-Wrap) -->
                        <span class="block mt-1 sm:mt-2">
                            <span class="font-times font-bold text-xl sm:text-2xl md:text-3xl lg:text-[36px] xl:text-[44px] animate-moving-gold uppercase whitespace-nowrap inline-block">
                                DI SMP ISLAM AL-KHOLILI
                            </span>
                        </span>
                    </h1>

                    <!-- Aesthetic Golden Accent Line -->
                    <div class="flex items-center justify-center lg:justify-start gap-2.5 pt-1">
                        <div class="h-[2px] w-16 sm:w-24 bg-gradient-to-r from-gold-400 to-[#1da2ad] rounded-full"></div>
                        <div class="w-2 h-2 rotate-45 bg-gold-400 shadow-sm shadow-gold-400"></div>
                        <div class="h-[2px] w-8 sm:w-14 bg-gradient-to-r from-gold-400 to-transparent rounded-full"></div>
                    </div>
                </div>

                <!-- Subheadline -->
                <p class="text-base sm:text-lg text-[#e0f7f8]/90 max-w-2xl mx-auto lg:mx-0 font-light leading-relaxed">
                    <?= htmlspecialchars($heroSubtitle) ?>
                </p>

                <!-- Location Pin Tagline -->
                <div class="flex items-center justify-center lg:justify-start gap-2 text-xs sm:text-sm text-[#b2ecf0]">
                    <i class="fa-solid fa-map-pin text-gold-400"></i>
                    <span><?= htmlspecialchars($siteSettings['alamat']) ?></span>
                </div>

                <!-- Action Buttons -->
                <div class="pt-4 flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                    <!-- PPDB External/Internal Link -->
                    <a href="<?= htmlspecialchars($ppdbLink) ?>" <?= $isPpdbExt ? 'target="_blank" rel="noopener noreferrer"' : '' ?> class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-xl font-bold text-brand-950 bg-gradient-to-r from-gold-400 to-amber-300 hover:from-gold-300 hover:to-amber-200 shadow-lg shadow-gold-500/20 hover:shadow-gold-500/30 transition transform hover:-translate-y-0.5">
                        <i class="fa-solid fa-user-graduate"></i>
                        <span>Daftar PPDB Online</span>
                        <?php if ($isPpdbExt): ?><i class="fa-solid fa-arrow-up-right-from-square text-xs"></i><?php endif; ?>
                    </a>

                    <a href="profil.php" class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-xl font-semibold text-white bg-[#07484b]/70 hover:bg-[#07484b] border border-[#3fc1cc]/30 backdrop-blur-sm transition">
                        <i class="fa-solid fa-circle-info text-[#7ddbe3]"></i>
                        <span>Profil Madrasah / Sekolah</span>
                    </a>

                    <a href="https://wa.me/<?= htmlspecialchars($siteSettings['whatsapp_number']) ?>?text=Halo%20SMP%20Islam%20Al-Kholili,%20saya%20ingin%20konsultasi%20pendidikan" target="_blank" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3.5 rounded-xl font-semibold text-[#b2ecf0] hover:text-white hover:bg-[#032a2c]/60 transition">
                        <i class="fa-brands fa-whatsapp text-lg text-green-400"></i>
                        <span>Konsultasi WA</span>
                    </a>
                </div>

            </div>

            <!-- Right Column: Hero Graphic Card with Official Logo -->
            <div class="lg:col-span-5 flex justify-center relative">
                
                <!-- Animated aura circle -->
                <div class="absolute inset-0 bg-gradient-to-tr from-[#15818b] to-gold-400 rounded-3xl blur-2xl opacity-30 transform -rotate-6"></div>

                <div class="relative w-full max-w-md bg-gradient-to-b from-[#07484b]/95 to-[#032a2c]/98 border border-[#1da2ad]/35 rounded-3xl p-6 sm:p-8 backdrop-blur-xl shadow-2xl text-center">
                    
                    <!-- School Logo Showcase -->
                    <div class="relative mx-auto w-36 h-36 sm:w-44 sm:h-44 p-3 bg-white rounded-3xl shadow-xl border-4 border-gold-400/80 mb-6 flex items-center justify-center group">
                        <img src="<?= htmlspecialchars($logoImg) ?>" alt="Logo SMP Islam Al-Kholili" class="w-full h-full object-contain filter drop-shadow group-hover:scale-105 transition-transform duration-300">
                    </div>

                    <!-- Motto / School Brand Name -->
                    <h3 class="text-2xl font-heading font-extrabold text-white tracking-wide">
                        <?= htmlspecialchars($siteSettings['site_name']) ?>
                    </h3>
                    <p class="text-xs uppercase tracking-widest text-gold-400 font-bold mt-1">
                        <?= htmlspecialchars($siteSettings['site_tagline']) ?>
                    </p>

                    <div class="mt-4 p-3 rounded-xl bg-[#032a2c]/60 border border-[#11656d]/30 text-xs text-[#e0f7f8] italic leading-relaxed">
                        "Membentuk Siswa Cerdas Intelektual, Santun Berakhlakul Karimah, dan Siap Menyongsong Masa Depan Gemilang."
                    </div>

                    <!-- Floating Badges / Keypoints -->
                    <div class="mt-6 space-y-2.5 text-left text-xs">
                        <div class="flex items-center gap-3 p-2.5 rounded-lg bg-[#032a2c]/75 border border-[#11656d]/50">
                            <div class="w-7 h-7 rounded-full bg-gold-400/20 text-gold-400 flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-check text-xs"></i>
                            </div>
                            <span class="text-slate-200">Terpadu dengan <strong>Masjid Baiturrohman</strong> Kedopok</span>
                        </div>
                        <div class="flex items-center gap-3 p-2.5 rounded-lg bg-[#032a2c]/75 border border-[#11656d]/50">
                            <div class="w-7 h-7 rounded-full bg-gold-400/20 text-gold-400 flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-book-open-reader text-xs"></i>
                            </div>
                            <span class="text-slate-200">Kurikulum Nasional + Nilai-Nilai Salafiyah & Diniyah</span>
                        </div>
                        <div class="flex items-center gap-3 p-2.5 rounded-lg bg-[#032a2c]/75 border border-[#11656d]/50">
                            <div class="w-7 h-7 rounded-full bg-gold-400/20 text-gold-400 flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-heart text-xs"></i>
                            </div>
                            <span class="text-slate-200">Lingkungan Belajar Asri, Aman, dan Nyaman</span>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</section>

<!-- ================= SAMBUTAN KEPALA SEKOLAH ================= -->
<section class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl p-6 sm:p-10 lg:p-12 shadow-sm border border-slate-200">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                
                <!-- Photo Kepala Sekolah -->
                <div class="lg:col-span-4 text-center">
                    <div class="relative inline-block mt-4">
                        
                        <!-- Lencana di ATAS Foto: Kepala Sekolah -->
                        <div class="absolute -top-3.5 left-1/2 transform -translate-x-1/2 bg-gradient-to-r from-brand-900 to-emerald-800 text-gold-300 text-[11px] font-bold py-1 px-4 rounded-full shadow-md uppercase tracking-wider whitespace-nowrap border border-gold-400/50 z-10 flex items-center gap-1.5">
                            <i class="fa-solid fa-user-tie text-[10px] text-gold-400"></i>
                            <span>Kepala Sekolah</span>
                        </div>

                        <div class="w-48 h-48 sm:w-56 sm:h-56 mx-auto rounded-3xl overflow-hidden bg-gradient-to-tr from-brand-700 to-emerald-500 p-1.5 shadow-xl">
                            <?php 
                            $fotoKepsek = $siteSettings['kepala_sekolah_foto'] ?? 'assets/img/kepala-sekolah.jpg';
                            $namaKepsek = !empty($siteSettings['nama_kepala_sekolah']) ? $siteSettings['nama_kepala_sekolah'] : 'Nama Kepala Sekolah';
                            ?>
                            <img src="<?= htmlspecialchars($fotoKepsek) ?>" alt="<?= htmlspecialchars($namaKepsek) ?>" class="w-full h-full object-cover rounded-2xl bg-white">
                        </div>

                        <!-- Lencana di BAWAH Foto: NAMA KEPALA SEKOLAH -->
                        <div class="absolute -bottom-3.5 left-1/2 transform -translate-x-1/2 bg-brand-950 text-white text-xs font-bold py-1.5 px-5 rounded-xl shadow-lg whitespace-nowrap border border-gold-400/50 z-10 flex items-center gap-1.5">
                            <i class="fa-solid fa-id-badge text-gold-400 text-xs"></i>
                            <span><?= htmlspecialchars($namaKepsek) ?></span>
                        </div>

                    </div>
                    <div class="mt-7">
                        <h4 class="font-heading font-extrabold text-lg text-slate-800"><?= htmlspecialchars($namaKepsek) ?></h4>
                        <p class="text-xs text-brand-700 font-bold uppercase tracking-wider mt-0.5">Kepala SMP Islam Al-Kholili</p>
                    </div>
                </div>

                <!-- Speech Content -->
                <div class="lg:col-span-8 space-y-4">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-emerald-50 text-brand-800 text-xs font-bold uppercase tracking-wider">
                        <i class="fa-solid fa-bullhorn text-emerald-600"></i> Sambutan Hangat
                    </div>
                    
                    <h2 class="font-heading font-extrabold text-2xl sm:text-3xl text-slate-900 leading-tight">
                        Assalamu’alaikum Warahmatullahi Wabarakaatuh
                    </h2>

                    <div class="text-slate-600 text-sm sm:text-base leading-relaxed space-y-3 text-justify">
                        <p>
                            Salam sejahtera untuk kita semua. Selamat datang di website SMP Islam Al-Kholili. Website ini dibangun sebagai sarana atau media informasi dan komunikasi sekolah, karena sejalan dengan perkembangan teknologi industri 4.0 yang berguna untuk memudahkan mencari informasi tentang SMP Islam Al-Kholili. Kualitas layanan menjadi salah satu misi sekolah dan kaitannya dengan transparansi dan akuntabilitas sekolah.
                        </p>
                        <p>
                            Menjalankan sebagai pimpinan di SMP Islam Al-Kholili, harapan tentu untuk mengembangkan sekolah besar ini menjadi lebih baik lagi, bertabur bintang baik akademik maupun non akademik. Sekolah yang akan melahirkan generasi yang inovatif, kreatif, religius, dan akhlakul karimah yang berwawasan luas serta mengabdi kepada agama, bangsa, dan negara.
                        </p>
                    </div>

                    <div class="pt-2 flex items-center justify-between flex-wrap gap-4 border-t border-slate-100">
                        <div class="text-xs text-slate-500 font-semibold italic">
                            "Wassalamu’alaikum Warohmatullahi Wabarokatuh."
                        </div>
                        <a href="profil.php" class="inline-flex items-center gap-2 text-sm font-bold text-brand-700 hover:text-brand-800">
                            <span>Baca Sambutan Lengkap & Profil</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- ================= PROGRAM UNGGULAN ================= -->
<section class="py-16 bg-white border-y border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="inline-block text-xs font-bold uppercase tracking-widest text-brand-700 bg-brand-50 px-3.5 py-1.5 rounded-full mb-3">
                Karakter & Keunggulan
            </span>
            <h2 class="font-heading font-extrabold text-3xl sm:text-4xl text-slate-900 tracking-tight">
                Program Unggulan Sekolah Kami
            </h2>
            <p class="mt-3 text-slate-600 text-sm sm:text-base">
                Dirancang secara terintegrasi untuk melatih kecerdasan intelektual, kematangan spiritual, dan kecakapan sosial siswa.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Program 1 -->
            <div class="p-6 rounded-2xl bg-gradient-to-b from-emerald-50/50 to-white border border-emerald-100 hover-card group">
                <div class="w-14 h-14 rounded-xl bg-brand-700 text-white flex items-center justify-center text-2xl shadow-md shadow-brand-700/20 mb-5 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-book-quran"></i>
                </div>
                <h3 class="font-heading font-bold text-lg text-slate-900 mb-2 group-hover:text-brand-700 transition">
                    Tahfidzul Qur'an
                </h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Pembelajaran tahsin dan tahfidz dengan metode talaqqi terarah untuk hafalan juz 30 dan surat-surat pilihan secara mutqin dan tartil.
                </p>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center gap-1.5 text-xs font-semibold text-brand-700">
                    <i class="fa-solid fa-circle-check text-emerald-500"></i> Bimbingan Intensif Setiap Hari
                </div>
            </div>

            <!-- Program 2 -->
            <div class="p-6 rounded-2xl bg-gradient-to-b from-amber-50/50 to-white border border-amber-100 hover-card group">
                <div class="w-14 h-14 rounded-xl bg-gold-500 text-white flex items-center justify-center text-2xl shadow-md shadow-gold-500/20 mb-5 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-mosque"></i>
                </div>
                <h3 class="font-heading font-bold text-lg text-slate-900 mb-2 group-hover:text-gold-600 transition">
                    Kajian Adab & Diniyah
                </h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Penguatan akidah akhlak, fiqih ibadah praktis, pembiasaan sholat Dhuha dan Dhuhur berjamaah di Masjid Baiturrohman.
                </p>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center gap-1.5 text-xs font-semibold text-gold-600">
                    <i class="fa-solid fa-circle-check text-amber-500"></i> Berbasis Pembiasaan Ibadah
                </div>
            </div>

            <!-- Program 3 -->
            <div class="p-6 rounded-2xl bg-gradient-to-b from-blue-50/50 to-white border border-blue-100 hover-card group">
                <div class="w-14 h-14 rounded-xl bg-blue-600 text-white flex items-center justify-center text-2xl shadow-md shadow-blue-600/20 mb-5 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-laptop-code"></i>
                </div>
                <h3 class="font-heading font-bold text-lg text-slate-900 mb-2 group-hover:text-blue-600 transition">
                    Literasi Digital & IT
                </h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Pengenalan aplikasi perkantoran, desain grafis dasar, internet sehat, dan kemampuan komputasi menghadapi kurikulum abad 21.
                </p>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center gap-1.5 text-xs font-semibold text-blue-600">
                    <i class="fa-solid fa-circle-check text-blue-500"></i> Fasilitas Lab Komputer
                </div>
            </div>

            <!-- Program 4 -->
            <div class="p-6 rounded-2xl bg-gradient-to-b from-purple-50/50 to-white border border-purple-100 hover-card group">
                <div class="w-14 h-14 rounded-xl bg-purple-600 text-white flex items-center justify-center text-2xl shadow-md shadow-purple-600/20 mb-5 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-drum"></i>
                </div>
                <h3 class="font-heading font-bold text-lg text-slate-900 mb-2 group-hover:text-purple-600 transition">
                    Seni Hadrah & Kepanduan
                </h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Penyaluran bakat melalui grup Hadrah/Banjari shalawat, Kepanduan/Pramuka, kepemimpinan, dan olahraga prestasi.
                </p>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center gap-1.5 text-xs font-semibold text-purple-600">
                    <i class="fa-solid fa-circle-check text-purple-500"></i> Ragam Ekstrakurikuler Pilihan
                </div>
            </div>

        </div>

    </div>
</section>

<!-- ================= PRESTASI CUPLIKAN ================= -->
<section class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10 gap-4">
            <div>
                <span class="inline-block text-xs font-bold uppercase tracking-widest text-brand-700 bg-brand-50 px-3.5 py-1.5 rounded-full mb-2">
                    Jejak Prestasi
                </span>
                <h2 class="font-heading font-extrabold text-2xl sm:text-3xl text-slate-900">
                    Prestasi & Kebanggaan Siswa
                </h2>
                <p class="text-slate-500 text-sm mt-1">Bukti dedikasi peserta didik dan bimbingan guru di berbagai bidang kompetisi.</p>
            </div>
            <a href="prestasi.php" class="inline-flex items-center gap-2 text-sm font-bold text-brand-700 hover:text-brand-800 self-start sm:self-auto">
                <span>Lihat Semua Prestasi</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover-card">
                <div class="flex items-center justify-between mb-4">
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                        <i class="fa-solid fa-trophy mr-1 text-gold-500"></i> Juara 1
                    </span>
                    <span class="text-xs text-slate-400">Tingkat Kota</span>
                </div>
                <h3 class="font-heading font-bold text-base text-slate-900 mb-2">
                    Lomba Musabaqah Hifdzil Qur'an (MHQ) SMP/MTs
                </h3>
                <p class="text-xs sm:text-sm text-slate-600 mb-4">
                    Meraih Juara 1 kategori Tahfidz Juz 30 Tingkat Kota Probolinggo dengan nilai tajwid & kelancaran terbaik.
                </p>
                <div class="text-xs text-brand-700 font-semibold flex items-center gap-1.5 pt-3 border-t border-slate-100">
                    <i class="fa-solid fa-calendar-days"></i> Kegiatan Semester Genap
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover-card">
                <div class="flex items-center justify-between mb-4">
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                        <i class="fa-solid fa-medal mr-1 text-emerald-600"></i> Juara 2
                    </span>
                    <span class="text-xs text-slate-400">Tingkat Wilayah</span>
                </div>
                <h3 class="font-heading font-bold text-base text-slate-900 mb-2">
                    Festival Seni Hadrah & Banjari Islami Pelajar
                </h3>
                <p class="text-xs sm:text-sm text-slate-600 mb-4">
                    Tim Rebana Hadrah SMP Islam Al-Kholili berhasil menampilkan aransemen shalawat yang memukau dewan juri.
                </p>
                <div class="text-xs text-brand-700 font-semibold flex items-center gap-1.5 pt-3 border-t border-slate-100">
                    <i class="fa-solid fa-calendar-days"></i> Peringatan Hari Santri
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover-card">
                <div class="flex items-center justify-between mb-4">
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                        <i class="fa-solid fa-award mr-1 text-blue-600"></i> Juara 3
                    </span>
                    <span class="text-xs text-slate-400">Tingkat Kecamatan</span>
                </div>
                <h3 class="font-heading font-bold text-base text-slate-900 mb-2">
                    Olimpiade Sains & Matematika Madrasah/SMP
                </h3>
                <p class="text-xs sm:text-sm text-slate-600 mb-4">
                    Siswa SMP Islam Al-Kholili menunjukkan kompetensi prima dalam pemecahan soal logika Matematika & IPA.
                </p>
                <div class="text-xs text-brand-700 font-semibold flex items-center gap-1.5 pt-3 border-t border-slate-100">
                    <i class="fa-solid fa-calendar-days"></i> Kompetisi Sains Pelajar
                </div>
            </div>

        </div>

    </div>
</section>

<!-- ================= CUPLIKAN GALERI KEGIATAN ================= -->
<section class="py-16 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10 gap-4">
            <div>
                <span class="inline-block text-xs font-bold uppercase tracking-widest text-brand-700 bg-brand-50 px-3.5 py-1.5 rounded-full mb-2">
                    Dokumentasi Aktivitas
                </span>
                <h2 class="font-heading font-extrabold text-2xl sm:text-3xl text-slate-900">
                    Galeri Suasana Sekolah
                </h2>
                <p class="text-slate-500 text-sm mt-1">Potret kegiatan belajar mengajar, ibadah, dan ekstrakurikuler di SMP Islam Al-Kholili.</p>
            </div>
            <a href="galeri.php" class="inline-flex items-center gap-2 text-sm font-bold text-brand-700 hover:text-brand-800 self-start sm:self-auto">
                <span>Buka Galeri Lengkap</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            
            <div class="relative group overflow-hidden rounded-2xl bg-brand-900 aspect-square">
                <div class="w-full h-full flex flex-col items-center justify-center p-4 text-center bg-gradient-to-t from-black/80 via-brand-900/60 to-brand-800 text-white">
                    <i class="fa-solid fa-person-praying text-3xl text-gold-400 mb-2"></i>
                    <h4 class="font-bold text-sm">Sholat Berjamaah</h4>
                    <p class="text-[11px] text-emerald-200 mt-1">Masjid Baiturrohman</p>
                </div>
            </div>

            <div class="relative group overflow-hidden rounded-2xl bg-brand-900 aspect-square">
                <div class="w-full h-full flex flex-col items-center justify-center p-4 text-center bg-gradient-to-t from-black/80 via-emerald-900/60 to-emerald-800 text-white">
                    <i class="fa-solid fa-chalkboard-user text-3xl text-gold-400 mb-2"></i>
                    <h4 class="font-bold text-sm">Belajar Interaktif</h4>
                    <p class="text-[11px] text-emerald-200 mt-1">Ruang Kelas Nyaman</p>
                </div>
            </div>

            <div class="relative group overflow-hidden rounded-2xl bg-brand-900 aspect-square">
                <div class="w-full h-full flex flex-col items-center justify-center p-4 text-center bg-gradient-to-t from-black/80 via-brand-900/60 to-brand-800 text-white">
                    <i class="fa-solid fa-book-quran text-3xl text-gold-400 mb-2"></i>
                    <h4 class="font-bold text-sm">Halaqah Tahfidz</h4>
                    <p class="text-[11px] text-emerald-200 mt-1">Setoran Hafalan Qur'an</p>
                </div>
            </div>

            <div class="relative group overflow-hidden rounded-2xl bg-brand-900 aspect-square">
                <div class="w-full h-full flex flex-col items-center justify-center p-4 text-center bg-gradient-to-t from-black/80 via-teal-900/60 to-teal-800 text-white">
                    <i class="fa-solid fa-campground text-3xl text-gold-400 mb-2"></i>
                    <h4 class="font-bold text-sm">Pramuka & Outbound</h4>
                    <p class="text-[11px] text-emerald-200 mt-1">Pembinaan Karakter</p>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- ================= FAQ SECTION ================= -->
<section id="faq" class="py-16 bg-slate-50 scroll-mt-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-12">
            <span class="inline-block text-xs font-bold uppercase tracking-widest text-brand-700 bg-brand-50 px-3.5 py-1.5 rounded-full mb-2">
                Tanya Jawab
            </span>
            <h2 class="font-heading font-extrabold text-2xl sm:text-3xl text-slate-900">
                Pertanyaan yang Sering Diajukan (FAQ)
            </h2>
            <p class="text-slate-500 text-sm mt-1">Informasi penting seputar proses pendaftaran, kurikulum, dan kegiatan sekolah.</p>
        </div>

        <div class="space-y-4">
            
            <!-- FAQ 1 -->
            <div class="faq-item bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm transition">
                <button type="button" class="faq-btn w-full px-6 py-4 text-left flex items-center justify-between gap-4 font-heading font-bold text-slate-800 hover:text-brand-700 focus:outline-none">
                    <span>Apakah pendaftaran siswa baru (PPDB) dapat dilakukan secara online?</span>
                    <i class="fa-solid fa-chevron-down faq-icon text-slate-400 text-sm"></i>
                </button>
                <div class="faq-content px-6 pb-5 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                    Ya, pendaftaran siswa baru dapat dilakukan secara online melalui website resmi PPDB kami pada tombol <a href="<?= htmlspecialchars($ppdbLink) ?>" <?= $isPpdbExt ? 'target="_blank"' : '' ?> class="text-brand-700 font-bold underline">PPDB Online</a> atau datang langsung ke ruang sekretariat panitia PPDB di Kampus SMP Islam Al-Kholili (Jl. KUA Blok Masjid Baiturrohman Kedopok).
                </div>
            </div>

            <!-- FAQ 2 -->
            <div class="faq-item bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm transition">
                <button type="button" class="faq-btn w-full px-6 py-4 text-left flex items-center justify-between gap-4 font-heading font-bold text-slate-800 hover:text-brand-700 focus:outline-none">
                    <span>Apa saja program unggulan di SMP Islam Al-Kholili?</span>
                    <i class="fa-solid fa-chevron-down faq-icon text-slate-400 text-sm"></i>
                </button>
                <div class="faq-content px-6 pb-5 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                    Program unggulan kami meliputi Tahfidzul Qur'an (Juz 30 & pilihan), pembiasaan sholat berjamaah & sholat Dhuha di Masjid Baiturrohman, Kajian Kitab Dasar Akhlak, Pembelajaran Berbasis IT, serta ekstrakurikuler seni Hadrah, Pramuka, dan Olahraga.
                </div>
            </div>

            <!-- FAQ 3 -->
            <div class="faq-item bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm transition">
                <button type="button" class="faq-btn w-full px-6 py-4 text-left flex items-center justify-between gap-4 font-heading font-bold text-slate-800 hover:text-brand-700 focus:outline-none">
                    <span>Apakah ada beasiswa bagi siswa berprestasi atau kurang mampu?</span>
                    <i class="fa-solid fa-chevron-down faq-icon text-slate-400 text-sm"></i>
                </button>
                <div class="faq-content px-6 pb-5 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                    Ya, SMP Islam Al-Kholili menyediakan jalur beasiswa prestasi akademik/non-akademik, beasiswa tahfidz Qur'an, serta program bantuan pendidikan bagi anak yatim/piatu dan siswa dari keluarga pra-sejahtera.
                </div>
            </div>

            <!-- FAQ 4 -->
            <div class="faq-item bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm transition">
                <button type="button" class="faq-btn w-full px-6 py-4 text-left flex items-center justify-between gap-4 font-heading font-bold text-slate-800 hover:text-brand-700 focus:outline-none">
                    <span>Di mana lokasi tepat kampus SMP Islam Al-Kholili?</span>
                    <i class="fa-solid fa-chevron-down faq-icon text-slate-400 text-sm"></i>
                </button>
                <div class="faq-content px-6 pb-5 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                    Sekolah kami berlokasi strategis di Jl. KUA Blok Masjid Baiturrohman, RT 03 / RW 04, Kelurahan Kedopok, Kecamatan Kedopok, Kota Probolinggo. Sangat mudah dijangkau dan berdampingan dengan Masjid Baiturrohman yang asri.
                </div>
            </div>

        </div>

    </div>
</section>

<!-- ================= PPDB CTA BANNER ================= -->
<section class="py-14 bg-gradient-to-r from-brand-900 via-brand-800 to-emerald-900 text-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-8">
            <div class="space-y-3 text-center lg:text-left">
                <div class="inline-block px-3 py-1 rounded-full bg-gold-400 text-brand-950 font-bold text-xs uppercase tracking-wider">
                    Penerimaan Siswa Baru
                </div>
                <h2 class="font-heading font-extrabold text-2xl sm:text-3xl lg:text-4xl">
                    Siap Menjadi Bagian Dari Keluarga Besar Al-Kholili?
                </h2>
                <p class="text-sm sm:text-base text-emerald-100/90 max-w-2xl">
                    Daftarkan putra-putri Anda sekarang di Web Resmi PPDB kami atau hubungi panitia melalui WhatsApp.
                </p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                <a href="<?= htmlspecialchars($ppdbLink) ?>" <?= $isPpdbExt ? 'target="_blank" rel="noopener noreferrer"' : '' ?> class="px-7 py-3.5 rounded-xl font-bold text-brand-950 bg-gradient-to-r from-gold-400 to-amber-300 hover:from-gold-300 hover:to-amber-200 text-center shadow-lg transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-graduation-cap"></i>
                    <span>Buka Web PPDB Online</span>
                    <?php if ($isPpdbExt): ?><i class="fa-solid fa-arrow-up-right-from-square text-xs"></i><?php endif; ?>
                </a>
                <a href="https://wa.me/<?= htmlspecialchars($siteSettings['whatsapp_number']) ?>?text=Assalamu'alaikum%20Panitia%20PPDB%20SMP%20Islam%20Al-Kholili,%20mohon%20info%20pendaftaran%20siswa%20baru" target="_blank" class="px-7 py-3.5 rounded-xl font-bold text-white bg-white/10 hover:bg-white/20 border border-white/20 text-center transition flex items-center justify-center gap-2">
                    <i class="fa-brands fa-whatsapp text-green-400 text-lg"></i> Hubungi Panitia
                </a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
