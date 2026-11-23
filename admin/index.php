<?php
$admin_page = "dashboard";
$admin_title = "Ringkasan Dasbor";
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../includes/settings.php';

/** @var array<string, mixed> $siteSettings */
global $siteSettings;
if (!isset($siteSettings) || !is_array($siteSettings)) {
    $siteSettings = get_all_settings();
}

$ppdbUrl = isset($siteSettings['ppdb_url']) ? (string)$siteSettings['ppdb_url'] : '';
$ppdbHost = (string)(parse_url($ppdbUrl, PHP_URL_HOST) ?: $ppdbUrl);

// Count galleries
$galleryFile = __DIR__ . '/../data/gallery.json';
$galleryCount = 0;
if (file_exists($galleryFile)) {
    $galData = json_decode(file_get_contents($galleryFile), true);
    $galleryCount = is_array($galData) ? count($galData) : 0;
}

// Count news
$newsFile = __DIR__ . '/../data/news.json';
$newsCount = 0;
if (file_exists($newsFile)) {
    $newsData = json_decode(file_get_contents($newsFile), true);
    $newsCount = is_array($newsData) ? count($newsData) : 0;
}

// Count PPDB
$ppdbFile = __DIR__ . '/../data/ppdb_submissions.json';
$ppdbCount = 0;
$recentPpdb = [];
if (file_exists($ppdbFile)) {
    $ppdbData = json_decode(file_get_contents($ppdbFile), true);
    if (is_array($ppdbData)) {
        $ppdbCount = count($ppdbData);
        $recentPpdb = array_slice(array_reverse($ppdbData), 0, 5);
    }
}
?>

<div class="space-y-8">
    
    <!-- Welcome Greeting Card -->
    <div class="bg-gradient-to-r from-brand-900 via-brand-800 to-emerald-900 rounded-3xl p-6 sm:p-8 text-white shadow-lg relative overflow-hidden">
        <div class="absolute -right-8 -bottom-8 w-60 h-60 bg-gold-400/10 rounded-full blur-2xl"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="space-y-2">
                <span class="inline-block px-3 py-1 rounded-full bg-gold-400 text-brand-950 text-xs font-bold uppercase tracking-wider">
                    Selamat Datang, Administrator
                </span>
                <h2 class="text-2xl sm:text-3xl font-heading font-extrabold">
                    Panel Kendali Website SMP Islam Al-Kholili
                </h2>
                <p class="text-emerald-100 text-xs sm:text-sm max-w-xl">
                    Melalui halaman ini Anda dapat mengubah logo, mengganti foto latar belakang (begrond) hero, mengubah teks selamat datang, mengunggah foto ke galeri, dan mengatur tautan web PPDB.
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="pengaturan.php" class="px-5 py-3 rounded-xl font-bold text-brand-950 bg-gradient-to-r from-gold-400 to-amber-300 hover:from-gold-300 shadow-md text-xs sm:text-sm flex items-center gap-2 transition">
                    <i class="fa-solid fa-sliders"></i>
                    <span>Pengaturan Website</span>
                </a>
                <a href="galeri.php" class="px-5 py-3 rounded-xl font-semibold text-white bg-white/10 hover:bg-white/20 border border-white/20 text-xs sm:text-sm flex items-center gap-2 transition">
                    <i class="fa-solid fa-cloud-arrow-up text-gold-300"></i>
                    <span>Upload Foto</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Metric Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Logo & Begrond Status -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-brand-700 flex items-center justify-center text-2xl flex-shrink-0">
                <i class="fa-solid fa-image"></i>
            </div>
            <div>
                <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider block">Logo & Begrond</span>
                <strong class="text-base text-slate-800 font-heading block truncate max-w-[150px]">Kustom Aktif</strong>
                <a href="pengaturan.php" class="text-xs text-brand-700 hover:underline font-bold mt-1 inline-block">Ganti di sini &rarr;</a>
            </div>
        </div>

        <!-- PPDB Web Link Status -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-amber-50 text-gold-600 flex items-center justify-center text-2xl flex-shrink-0">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <div>
                <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider block">Web PPDB Anda</span>
                <strong class="text-base text-slate-800 font-heading block truncate max-w-[150px]" title="<?= htmlspecialchars($ppdbUrl) ?>">
                    <?= htmlspecialchars($ppdbHost) ?>
                </strong>
                <a href="pengaturan.php" class="text-xs text-gold-600 hover:underline font-bold mt-1 inline-block">Ubah tautan &rarr;</a>
            </div>
        </div>

        <!-- Gallery Photos Count -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl flex-shrink-0">
                <i class="fa-solid fa-images"></i>
            </div>
            <div>
                <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider block">Foto Galeri</span>
                <strong class="text-2xl text-slate-800 font-extrabold font-heading block"><?= $galleryCount ?></strong>
                <a href="galeri.php" class="text-xs text-blue-600 hover:underline font-bold mt-1 inline-block">Kelola foto &rarr;</a>
            </div>
        </div>

        <!-- News Articles Count -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-2xl flex-shrink-0">
                <i class="fa-solid fa-newspaper"></i>
            </div>
            <div>
                <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider block">Kabar Berita</span>
                <strong class="text-2xl text-slate-800 font-extrabold font-heading block"><?= $newsCount ?></strong>
                <a href="berita.php" class="text-xs text-purple-600 hover:underline font-bold mt-1 inline-block">Kelola berita &rarr;</a>
            </div>
        </div>

    </div>

    <!-- Quick Action Guidance -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-brand-100 text-brand-700 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-image"></i>
                </div>
                <div>
                    <h3 class="font-heading font-bold text-base text-slate-900">Ubah Logo & Begrond</h3>
                    <p class="text-xs text-slate-500">Sesuaikan tampilan visual beranda</p>
                </div>
            </div>
            <p class="text-xs text-slate-600 leading-relaxed">
                Anda dapat mengunggah file foto beresolusi tinggi langsung dari HP/laptop untuk menjadi gambar latar (begrond) hero beranda dan mengganti file logo resmi sekolah.
            </p>
            <a href="pengaturan.php" class="w-full py-2.5 px-4 rounded-xl text-xs font-bold text-center block text-brand-700 bg-brand-50 hover:bg-brand-100 transition">
                Buka Pengaturan Tampilan &rarr;
            </a>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 text-gold-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-link"></i>
                </div>
                <div>
                    <h3 class="font-heading font-bold text-base text-slate-900">Hubungkan Web PPDB</h3>
                    <p class="text-xs text-slate-500">Tautkan ke website PPDB yang sudah Anda miliki</p>
                </div>
            </div>
            <p class="text-xs text-slate-600 leading-relaxed">
                Sudah memiliki web formulir PPDB (Google Form, portal PPDB, atau domain khusus)? Cukup cantumkan URL-nya di sini agar semua tombol PPDB langsung mengarah ke web tersebut.
            </p>
            <a href="pengaturan.php#ppdb_section" class="w-full py-2.5 px-4 rounded-xl text-xs font-bold text-center block text-gold-700 bg-amber-50 hover:bg-amber-100 transition">
                Atur Tautan Web PPDB &rarr;
            </a>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                </div>
                <div>
                    <h3 class="font-heading font-bold text-base text-slate-900">Upload Foto Galeri</h3>
                    <p class="text-xs text-slate-500">Dokumentasi kegiatan siswa</p>
                </div>
            </div>
            <p class="text-xs text-slate-600 leading-relaxed">
                Tambahkan foto-foto kegiatan belajar, sholat di Masjid Baiturrohman, halaqah tahfidz, ekstrakurikuler hadrah dan kepanduan agar tampil menarik di galeri publik.
            </p>
            <a href="galeri.php" class="w-full py-2.5 px-4 rounded-xl text-xs font-bold text-center block text-blue-700 bg-blue-50 hover:bg-blue-100 transition">
                Kelola & Upload Foto &rarr;
            </a>
        </div>

    </div>

    <!-- Recent PPDB Submissions (if any) -->
    <?php if (!empty($recentPpdb)): ?>
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-heading font-bold text-base text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-user-graduate text-brand-700"></i> Pendaftar PPDB Masuk Terbaru
            </h3>
            <a href="data_ppdb.php" class="text-xs text-brand-700 font-bold hover:underline">Lihat Semua Data (<?= $ppdbCount ?>) &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-bold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3">No. Registrasi</th>
                        <th class="px-4 py-3">Nama Siswa</th>
                        <th class="px-4 py-3">Asal Sekolah</th>
                        <th class="px-4 py-3">Jalur</th>
                        <th class="px-4 py-3">No. WhatsApp</th>
                        <th class="px-4 py-3">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($recentPpdb as $row): ?>
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 font-mono font-bold text-brand-800"><?= htmlspecialchars($row['id'] ?? '-') ?></td>
                        <td class="px-4 py-3 font-semibold text-slate-800"><?= htmlspecialchars($row['nama'] ?? '-') ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($row['asal_sekolah'] ?? '-') ?></td>
                        <td class="px-4 py-3"><span class="px-2 py-0.5 rounded bg-emerald-100 text-brand-800 font-bold"><?= htmlspecialchars($row['jalur'] ?? '-') ?></span></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($row['no_wa'] ?? '-') ?></td>
                        <td class="px-4 py-3 text-slate-400"><?= htmlspecialchars($row['waktu'] ?? '-') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/footer.php'; ?>
