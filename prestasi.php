<?php
require_once __DIR__ . '/includes/settings.php';
$siteSettings = get_all_settings();

$page_title = "Prestasi Siswa - SMP Islam Al-Kholili Probolinggo";
$page_desc = "Kumpulan prestasi akademik, tahfidzul qur'an, seni hadrah, dan kejuaraan non-akademik siswa-siswi SMP Islam Al-Kholili Kota Probolinggo berprestasi.";
$current_page = "prestasi";
include 'includes/header.php';

$achievements = [
    [
        "juara" => "Juara 1",
        "tingkat" => "Tingkat Kota Probolinggo",
        "lomba" => "Musabaqah Hifdzil Qur'an (MHQ) 3 Juz Pelajar",
        "kategori" => "Keagamaan & Qur'an",
        "tahun" => "2026",
        "peserta" => "M. Rizky Ramadhan (Kelas IX)",
        "badge" => "bg-amber-100 text-amber-800 border-amber-300",
        "icon" => "fa-book-quran"
    ],
    [
        "juara" => "Juara 1",
        "tingkat" => "Tingkat Kota Probolinggo",
        "lomba" => "Festival Seni Hadrah Shalawat Tingkat SMP/MTs",
        "kategori" => "Seni & Budaya Islam",
        "tahun" => "2025",
        "peserta" => "Grup Hadrah Shalawat Al-Kholili",
        "badge" => "bg-amber-100 text-amber-800 border-amber-300",
        "icon" => "fa-drum"
    ],
    [
        "juara" => "Juara 2",
        "tingkat" => "Tingkat Kota Probolinggo",
        "lomba" => "Lomba Pidato Bahasa Arab & Pildacil Tingkat SMP",
        "kategori" => "Bahasa & Literasi",
        "tahun" => "2025",
        "peserta" => "Fatimah Az-Zahra (Kelas VIII)",
        "badge" => "bg-emerald-100 text-emerald-800 border-emerald-300",
        "icon" => "fa-microphone"
    ],
    [
        "juara" => "Juara 2",
        "tingkat" => "Kecamatan Kedopok",
        "lomba" => "Lomba Cerdas Cermat PAI & Pengetahuan Umum",
        "kategori" => "Akademik",
        "tahun" => "2025",
        "peserta" => "Tim LCC SMP Islam Al-Kholili",
        "badge" => "bg-emerald-100 text-emerald-800 border-emerald-300",
        "icon" => "fa-brain"
    ],
    [
        "juara" => "Juara 3",
        "tingkat" => "Tingkat Kota Probolinggo",
        "lomba" => "Olimpiade Matematika & Sains Terpadu",
        "kategori" => "Akademik",
        "tahun" => "2025",
        "peserta" => "Ahmad Danial (Kelas VIII)",
        "badge" => "bg-blue-100 text-blue-800 border-blue-300",
        "icon" => "fa-calculator"
    ],
    [
        "juara" => "Juara 2",
        "tingkat" => "Tingkat Kwarcab Probolinggo",
        "lomba" => "Pioneering & Sandi Morse Jambore Pramuka",
        "kategori" => "Kepanduan / Pramuka",
        "tahun" => "2024",
        "peserta" => "Regu Rajawali Pramuka Al-Kholili",
        "badge" => "bg-emerald-100 text-emerald-800 border-emerald-300",
        "icon" => "fa-campground"
    ],
    [
        "juara" => "Juara 3",
        "tingkat" => "Tingkat Kota Probolinggo",
        "lomba" => "Turnamen Futsal Pelajar Antar SMP/MTs",
        "kategori" => "Olahraga",
        "tahun" => "2024",
        "peserta" => "Tim Futsal Al-Kholili FC",
        "badge" => "bg-blue-100 text-blue-800 border-blue-300",
        "icon" => "fa-futbol"
    ],
    [
        "juara" => "Juara Harapan 1",
        "tingkat" => "Tingkat Wilayah Tapal Kuda",
        "lomba" => "Musabaqah Tilawatil Qur'an (MTQ) Mujawwad",
        "kategori" => "Keagamaan & Qur'an",
        "tahun" => "2024",
        "peserta" => "Siti Nur Haliza (Kelas IX)",
        "badge" => "bg-purple-100 text-purple-800 border-purple-300",
        "icon" => "fa-quran"
    ]
];
?>

<!-- Page Header Banner -->
<section class="bg-gradient-to-r from-brand-950 via-brand-900 to-emerald-900 text-white py-14 border-b border-brand-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:text-left flex flex-col sm:flex-row items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 text-xs font-semibold text-gold-400 uppercase tracking-widest mb-2">
                <i class="fa-solid fa-trophy"></i> Prestasi Sekolah
            </div>
            <h1 class="text-3xl sm:text-4xl font-heading font-extrabold tracking-tight">Prestasi & Penghargaan Siswa</h1>
            <p class="text-emerald-200 text-sm mt-2 max-w-xl">
                Daftar pencapaian gemilang siswa-siswi SMP Islam Al-Kholili di bidang akademik, keagamaan, seni, kepanduan, dan olahraga.
            </p>
        </div>
        <div class="flex items-center gap-2 text-xs text-slate-300 bg-white/10 px-4 py-2 rounded-lg backdrop-blur-sm">
            <a href="index.php" class="hover:text-white transition">Beranda</a>
            <span>/</span>
            <span class="text-gold-400 font-semibold">Prestasi</span>
        </div>
    </div>
</section>

<!-- Prestasi Grid -->
<section class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($achievements as $ach): ?>
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm hover-card flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 rounded-full text-xs font-bold border <?= $ach['badge'] ?>">
                            <i class="fa-solid fa-medal mr-1"></i> <?= $ach['juara'] ?>
                        </span>
                        <span class="text-xs font-semibold text-slate-400">
                            <?= $ach['tahun'] ?>
                        </span>
                    </div>

                    <div class="flex items-start gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-700 flex items-center justify-center flex-shrink-0 text-lg">
                            <i class="fa-solid <?= $ach['icon'] ?>"></i>
                        </div>
                        <div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-700 block">
                                <?= $ach['kategori'] ?>
                            </span>
                            <h3 class="font-heading font-bold text-base text-slate-900 leading-snug mt-0.5">
                                <?= $ach['lomba'] ?>
                            </h3>
                        </div>
                    </div>

                    <p class="text-xs text-slate-600 bg-slate-50 p-3 rounded-xl border border-slate-100 mb-4">
                        <span class="text-slate-400 block mb-0.5">Peserta / Peraih:</span>
                        <strong class="text-slate-800"><?= $ach['peserta'] ?></strong>
                    </p>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span class="font-medium text-brand-800 flex items-center gap-1.5">
                        <i class="fa-solid fa-award text-gold-500"></i> <?= $ach['tingkat'] ?>
                    </span>
                    <span class="text-emerald-600 font-semibold">Tervalidasi</span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Banner Motivation -->
        <div class="mt-14 bg-gradient-to-r from-brand-900 to-brand-950 rounded-3xl p-8 sm:p-10 text-white text-center relative overflow-hidden border border-emerald-600/30">
            <h3 class="text-xl sm:text-2xl font-heading font-bold mb-3">
                Ingin Mengembangkan Bakat & Meraih Prestasi Bersama Kami?
            </h3>
            <p class="text-sm text-emerald-200 max-w-2xl mx-auto mb-6">
                SMP Islam Al-Kholili memberikan ruang seluas-luasnya bagi setiap siswa untuk mengeksplorasi potensi diri dengan bimbingan intensif dari para guru pembina berpengalaman.
            </p>
            <a href="ppdb.php" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-bold text-brand-950 bg-gradient-to-r from-gold-400 to-amber-300 hover:from-gold-300 transition shadow-lg">
                <i class="fa-solid fa-graduation-cap"></i> Bergabung Sekarang Melalui PPDB
            </a>
        </div>

    </div>
</section>

<?php include 'includes/footer.php'; ?>
