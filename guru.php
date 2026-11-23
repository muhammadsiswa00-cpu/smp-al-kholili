<?php
require_once __DIR__ . '/includes/settings.php';
$siteSettings = get_all_settings();

$page_title = "Dewan Guru - SMP Islam Al-Kholili Kota Probolinggo";
$page_desc = "Daftar dewan guru dan tenaga pendidik SMP Islam Al-Kholili Kota Probolinggo yang profesional, berdedikasi, berakhlak mulia, dan siap membimbing siswa.";
$current_page = "guru";
include 'includes/header.php';

$teachersFile = __DIR__ . '/data/teachers.json';
$teachers = [];
if (file_exists($teachersFile)) {
    $teachers = json_decode(file_get_contents($teachersFile), true) ?: [];
}
?>

<!-- Page Header Banner -->
<section class="bg-gradient-to-r from-brand-950 via-brand-900 to-emerald-900 text-white py-14 border-b border-brand-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:text-left flex flex-col sm:flex-row items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 text-xs font-semibold text-gold-400 uppercase tracking-widest mb-2">
                <i class="fa-solid fa-chalkboard-user"></i> Tenaga Pengajar
            </div>
            <h1 class="text-3xl sm:text-4xl font-heading font-extrabold tracking-tight">Dewan Guru Sekolah</h1>
            <p class="text-emerald-200 text-sm mt-2 max-w-xl">
                Didukung oleh para pendidik profesional, berdedikasi tinggi, berjiwa sabar, dan memiliki komitmen mencetak generasi qur'ani.
            </p>
        </div>
        <div class="flex items-center gap-2 text-xs text-slate-300 bg-white/10 px-4 py-2 rounded-lg backdrop-blur-sm">
            <a href="index.php" class="hover:text-white transition">Beranda</a>
            <span>/</span>
            <span class="text-gold-400 font-semibold">Guru</span>
        </div>
    </div>
</section>

<!-- Teachers Grid Section -->
<section class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="text-xs font-bold uppercase tracking-wider text-brand-700 bg-brand-50 px-3.5 py-1.5 rounded-full">
                Keluarga Besar Pendidik
            </span>
            <h2 class="font-heading font-extrabold text-2xl sm:text-3xl text-slate-900 mt-2">
                Pendidik Berkompeten & Berkarakter
            </h2>
            <p class="text-slate-500 text-sm mt-1">Mengajar dengan hati, mendidik dengan keteladanan, membimbing dengan kasih sayang.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-7">
            <?php foreach ($teachers as $t): 
                $fotoGuru = !empty($t['foto']) ? $t['foto'] : 'assets/img/teachers/male-1.svg';
                // If it's kepala sekolah, check if custom upload exists
                if (($t['jabatan'] ?? '') === 'Kepala Sekolah' && !empty($siteSettings['kepala_sekolah_foto'])) {
                    $fotoGuru = $siteSettings['kepala_sekolah_foto'];
                }
                $namaGuru = ($t['jabatan'] ?? '') === 'Kepala Sekolah' && !empty($siteSettings['nama_kepala_sekolah']) ? $siteSettings['nama_kepala_sekolah'] : ($t['nama'] ?? 'Guru');
            ?>
            <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm hover-card group flex flex-col justify-between transition-all duration-300 hover:border-emerald-300 hover:shadow-xl">
                
                <!-- Card Header Visual Gradient -->
                <div>
                    <div class="h-32 bg-gradient-to-r <?= $t['color'] ?? 'from-brand-700 to-emerald-600' ?> relative flex items-center justify-center">
                        <!-- Frame Foto Guru -->
                        <div class="absolute -bottom-10 w-24 h-24 sm:w-28 sm:h-28 rounded-2xl bg-white p-1.5 shadow-xl border-2 border-white/90 overflow-hidden flex items-center justify-center">
                            <img src="<?= htmlspecialchars($fotoGuru) ?>" alt="<?= htmlspecialchars($namaGuru) ?>" class="w-full h-full object-cover rounded-xl bg-slate-100 group-hover:scale-105 transition-transform duration-300">
                        </div>
                    </div>

                    <div class="pt-14 p-6 text-center">
                        <span class="inline-block px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-50 text-brand-800 border border-emerald-100 mb-2">
                            <?= htmlspecialchars($t['jabatan'] ?? 'Guru') ?>
                        </span>
                        
                        <h3 class="font-heading font-extrabold text-base text-slate-900 leading-snug">
                            <?= htmlspecialchars($namaGuru) ?>
                        </h3>
                        
                        <p class="text-xs font-bold text-gold-600 mt-1">
                            <?= htmlspecialchars($t['mapel'] ?? '-') ?>
                        </p>
                    </div>
                </div>

                <div class="px-6 pb-6 pt-0 text-center">
                    <div class="pt-3 border-t border-slate-100 text-xs text-slate-500 font-medium flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-graduation-cap text-slate-400"></i>
                        <span><?= htmlspecialchars($t['pendidikan'] ?? '-') ?></span>
                    </div>
                </div>

            </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<?php include 'includes/footer.php'; ?>
