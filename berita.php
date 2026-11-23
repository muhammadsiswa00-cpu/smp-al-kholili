<?php
require_once __DIR__ . '/includes/settings.php';
$siteSettings = get_all_settings();

$page_title = "Berita & Agenda - SMP Islam Al-Kholili Probolinggo";
$page_desc = "Kumpulan berita terkini, agenda sekolah, kegiatan santri, dan pengumuman resmi SMP Islam Al-Kholili Kota Probolinggo. Update berita terpercaya.";
$current_page = "berita";
include 'includes/header.php';

$newsFile = __DIR__ . '/data/news.json';
$news = [];
if (file_exists($newsFile)) {
    $news = json_decode(file_get_contents($newsFile), true) ?: [];
}
?>

<!-- Page Header Banner -->
<section class="bg-gradient-to-r from-brand-950 via-brand-900 to-emerald-900 text-white py-14 border-b border-brand-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:text-left flex flex-col sm:flex-row items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 text-xs font-semibold text-gold-400 uppercase tracking-widest mb-2">
                <i class="fa-solid fa-newspaper"></i> Kabar Terkini
            </div>
            <h1 class="text-3xl sm:text-4xl font-heading font-extrabold tracking-tight">Berita Sekolah</h1>
            <p class="text-emerald-200 text-sm mt-2 max-w-xl">
                Ikuti liputan berita, agenda kegiatan kesiswaan, informasi akademik, dan pengumuman resmi SMP Islam Al-Kholili.
            </p>
        </div>
        <div class="flex items-center gap-2 text-xs text-slate-300 bg-white/10 px-4 py-2 rounded-lg backdrop-blur-sm">
            <a href="index.php" class="hover:text-white transition">Beranda</a>
            <span>/</span>
            <span class="text-gold-400 font-semibold">Berita</span>
        </div>
    </div>
</section>

<!-- News Grid -->
<section class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($news as $item): ?>
            <article class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover-card flex flex-col justify-between">
                <div>
                    <!-- Header Visual -->
                    <div class="h-44 bg-gradient-to-tr from-brand-900 to-emerald-800 relative p-6 flex flex-col justify-between text-white">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold px-3 py-1 rounded-full <?= $item['badge'] ?? 'bg-emerald-100 text-emerald-800' ?>">
                                <?= htmlspecialchars($item['kategori'] ?? 'Berita') ?>
                            </span>
                            <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-gold-300">
                                <i class="fa-solid <?= $item['icon'] ?? 'fa-newspaper' ?>"></i>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-emerald-200">
                            <i class="fa-regular fa-calendar"></i>
                            <span><?= htmlspecialchars($item['tanggal'] ?? '') ?></span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6">
                        <h2 class="font-heading font-bold text-lg text-slate-900 leading-snug mb-3 hover:text-brand-700 transition cursor-pointer">
                            <?= htmlspecialchars($item['judul'] ?? '') ?>
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            <?= htmlspecialchars($item['ringkasan'] ?? '') ?>
                        </p>
                    </div>
                </div>

                <div class="px-6 pb-6 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span class="flex items-center gap-1.5">
                        <i class="fa-regular fa-user text-brand-600"></i> Oleh: <strong><?= htmlspecialchars($item['penulis'] ?? 'Humas') ?></strong>
                    </span>
                    <a href="https://wa.me/<?= htmlspecialchars($siteSettings['whatsapp_number'] ?? '6285234567890') ?>?text=Halo%20SMP%20Islam%20Al-Kholili,%20saya%20ingin%20tanya%20mengenai%20berita:%20<?= urlencode($item['judul'] ?? '') ?>" target="_blank" class="text-brand-700 font-bold hover:underline flex items-center gap-1">
                        <span>Bagikan</span> <i class="fa-solid fa-share-nodes"></i>
                    </a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<?php include 'includes/footer.php'; ?>
