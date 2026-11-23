<?php
require_once __DIR__ . '/includes/settings.php';
$siteSettings = get_all_settings();

$page_title = "Galeri Foto Kegiatan - SMP Islam Al-Kholili";
$page_desc = "Dokumentasi foto kegiatan santri, fasilitas sekolah, sholat berjamaah di Masjid Baiturrohman, dan ekstrakurikuler SMP Islam Al-Kholili Probolinggo.";
$current_page = "galeri";
include 'includes/header.php';

$galleryFile = __DIR__ . '/data/gallery.json';
$galleries = [];
if (file_exists($galleryFile)) {
    $galleries = json_decode(file_get_contents($galleryFile), true) ?: [];
}
?>

<!-- Page Header Banner -->
<section class="bg-gradient-to-r from-brand-950 via-brand-900 to-emerald-900 text-white py-14 border-b border-brand-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:text-left flex flex-col sm:flex-row items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 text-xs font-semibold text-gold-400 uppercase tracking-widest mb-2">
                <i class="fa-solid fa-images"></i> Dokumentasi Sekolah
            </div>
            <h1 class="text-3xl sm:text-4xl font-heading font-extrabold tracking-tight">Galeri Kegiatan & Fasilitas</h1>
            <p class="text-emerald-200 text-sm mt-2 max-w-xl">
                Koleksi foto ragam aktivitas keseharian santri, pembelajaran di kelas, ibadah berjamaah, dan kegiatan ekstrakurikuler.
            </p>
        </div>
        <div class="flex items-center gap-2 text-xs text-slate-300 bg-white/10 px-4 py-2 rounded-lg backdrop-blur-sm">
            <a href="index.php" class="hover:text-white transition">Beranda</a>
            <span>/</span>
            <span class="text-gold-400 font-semibold">Galeri</span>
        </div>
    </div>
</section>

<!-- Gallery Filter & Grid -->
<section class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Filter Tabs -->
        <div class="flex items-center justify-center flex-wrap gap-2 mb-12">
            <button class="gallery-filter-btn px-4 py-2 rounded-xl text-xs sm:text-sm font-bold bg-brand-700 text-white shadow transition" data-filter="all">
                Semua Foto
            </button>
            <button class="gallery-filter-btn px-4 py-2 rounded-xl text-xs sm:text-sm font-bold bg-white text-slate-700 hover:bg-slate-100 shadow-sm border border-slate-200 transition" data-filter="ibadah">
                Keagamaan & Ibadah
            </button>
            <button class="gallery-filter-btn px-4 py-2 rounded-xl text-xs sm:text-sm font-bold bg-white text-slate-700 hover:bg-slate-100 shadow-sm border border-slate-200 transition" data-filter="tahfidz">
                Tahfidz Qur'an
            </button>
            <button class="gallery-filter-btn px-4 py-2 rounded-xl text-xs sm:text-sm font-bold bg-white text-slate-700 hover:bg-slate-100 shadow-sm border border-slate-200 transition" data-filter="belajar">
                Belajar & Kelas
            </button>
            <button class="gallery-filter-btn px-4 py-2 rounded-xl text-xs sm:text-sm font-bold bg-white text-slate-700 hover:bg-slate-100 shadow-sm border border-slate-200 transition" data-filter="ekskul">
                Ekstrakurikuler
            </button>
            <button class="gallery-filter-btn px-4 py-2 rounded-xl text-xs sm:text-sm font-bold bg-white text-slate-700 hover:bg-slate-100 shadow-sm border border-slate-200 transition" data-filter="fasilitas">
                Fasilitas
            </button>
        </div>

        <!-- Gallery Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" id="galleryContainer">
            <?php foreach ($galleries as $g): ?>
            <div class="gallery-item bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover-card flex flex-col" data-category="<?= htmlspecialchars($g['kategori'] ?? 'belajar') ?>">
                
                <!-- Visual Display Frame -->
                <div class="aspect-[4/3] bg-gradient-to-br <?= $g['gradient'] ?? 'from-brand-900 to-emerald-950' ?> relative flex flex-col justify-between text-white overflow-hidden group">
                    
                    <?php if (!empty($g['foto']) && file_exists(__DIR__ . '/' . $g['foto'])): ?>
                        <img src="<?= htmlspecialchars($g['foto']) ?>" alt="<?= htmlspecialchars($g['judul']) ?>" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    <?php else: ?>
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>
                        <div class="relative z-10 text-center py-8">
                            <i class="fa-solid <?= $g['icon'] ?? 'fa-image' ?> text-4xl text-gold-400/80 mb-2 group-hover:scale-110 transition-transform"></i>
                        </div>
                    <?php endif; ?>

                    <div class="relative z-10 p-4 flex items-center justify-between">
                        <span class="text-[10px] font-bold uppercase tracking-wider bg-black/60 backdrop-blur-md px-2.5 py-1 rounded-full text-emerald-100">
                            <?= htmlspecialchars($g['kategori_label'] ?? $g['kategori']) ?>
                        </span>
                        <div class="w-8 h-8 rounded-full bg-black/40 backdrop-blur-md flex items-center justify-center text-gold-300">
                            <i class="fa-solid <?= $g['icon'] ?? 'fa-camera' ?> text-xs"></i>
                        </div>
                    </div>

                    <div class="relative z-10 p-4 pt-0 text-[11px] text-emerald-200 flex items-center gap-1 font-medium">
                        <i class="fa-regular fa-calendar-check"></i>
                        <span><?= htmlspecialchars($g['tanggal'] ?? '') ?></span>
                    </div>
                </div>

                <!-- Text Detail -->
                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="font-heading font-bold text-base text-slate-900 leading-snug mb-2">
                            <?= htmlspecialchars($g['judul']) ?>
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            <?= htmlspecialchars($g['deskripsi']) ?>
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] font-semibold text-brand-700 flex items-center gap-1">
                        <i class="fa-solid fa-location-dot text-gold-500"></i> Lingkungan SMP Islam Al-Kholili
                    </div>
                </div>

            </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<?php include 'includes/footer.php'; ?>
