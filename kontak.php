<?php
require_once __DIR__ . '/includes/settings.php';
$siteSettings = get_all_settings();

$page_title = "Kontak & Alamat - SMP Islam Al-Kholili Probolinggo";
$page_desc = "Hubungi SMP Islam Al-Kholili Kota Probolinggo. Alamat: Jl. KUA Blok Masjid Baiturrohman RT03 RW04 Kel. Kedopok. Layanan informasi WhatsApp & Maps.";
$current_page = "kontak";

$contact_success = "";
if (($_SERVER["REQUEST_METHOD"] ?? '') === "POST" && isset($_POST['send_message'])) {
    $nama = htmlspecialchars(trim($_POST['nama'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $telepon = htmlspecialchars(trim($_POST['telepon'] ?? ''));
    $pesan = htmlspecialchars(trim($_POST['pesan'] ?? ''));

    if (!empty($nama) && !empty($pesan)) {
        $contact_success = "Terima kasih, Bapak/Ibu $nama! Pesan Anda telah kami terima dan tim humas kami akan segera merespons.";
    }
}

include 'includes/header.php';
?>

<!-- Page Header Banner -->
<section class="bg-gradient-to-r from-brand-950 via-brand-900 to-emerald-900 text-white py-14 border-b border-brand-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:text-left flex flex-col sm:flex-row items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 text-xs font-semibold text-gold-400 uppercase tracking-widest mb-2">
                <i class="fa-solid fa-address-book"></i> Hubungi Kami
            </div>
            <h1 class="text-3xl sm:text-4xl font-heading font-extrabold tracking-tight">Kontak & Lokasi Sekolah</h1>
            <p class="text-emerald-200 text-sm mt-2 max-w-xl">
                Kami siap melayani pertanyaan seputar PPDB, informasi kurikulum, kerjasama, dan kunjungan silaturahmi.
            </p>
        </div>
        <div class="flex items-center gap-2 text-xs text-slate-300 bg-white/10 px-4 py-2 rounded-lg backdrop-blur-sm">
            <a href="index.php" class="hover:text-white transition">Beranda</a>
            <span>/</span>
            <span class="text-gold-400 font-semibold">Kontak</span>
        </div>
    </div>
</section>

<!-- Contact & Map Section -->
<section class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <?php if (!empty($contact_success)): ?>
        <div class="mb-10 p-5 rounded-2xl bg-emerald-50 border border-emerald-300 text-brand-900 flex items-center gap-3 shadow-sm">
            <i class="fa-solid fa-circle-check text-2xl text-emerald-600 flex-shrink-0"></i>
            <div>
                <strong class="font-bold block">Pesan Berhasil Terkirim!</strong>
                <p class="text-xs sm:text-sm text-slate-600"><?= $contact_success ?></p>
            </div>
        </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            <!-- Contact Cards Left -->
            <div class="lg:col-span-5 space-y-6">
                
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
                    <h3 class="font-heading font-extrabold text-xl text-slate-900 border-b border-slate-100 pb-4">
                        Informasi Sekolah
                    </h3>

                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-brand-700 flex items-center justify-center text-xl flex-shrink-0">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Alamat Kampus</h4>
                            <p class="text-sm font-semibold text-slate-800 leading-relaxed">
                                <?= htmlspecialchars($siteSettings['alamat']) ?>
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-gold-100 text-gold-600 flex items-center justify-center text-xl flex-shrink-0">
                            <i class="fa-brands fa-whatsapp"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Layanan WhatsApp</h4>
                            <p class="text-sm font-semibold text-slate-800">
                                <a href="https://wa.me/<?= htmlspecialchars($siteSettings['whatsapp_number']) ?>?text=Halo%20Admin%20SMP%20Islam%20Al-Kholili" target="_blank" class="text-brand-700 hover:underline">
                                    <?= htmlspecialchars($siteSettings['whatsapp_display']) ?>
                                </a>
                            </p>
                            <span class="text-xs text-slate-500">Respon cepat jam kerja operasional</span>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-xl flex-shrink-0">
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Email Resmi</h4>
                            <p class="text-sm font-semibold text-slate-800">
                                <a href="mailto:<?= htmlspecialchars($siteSettings['email']) ?>" class="text-brand-700 hover:underline">
                                    <?= htmlspecialchars($siteSettings['email']) ?>
                                </a>
                            </p>
                            <span class="text-xs text-slate-500">Kirim surat dinas & konfirmasi berkas</span>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-purple-100 text-purple-600 flex items-center justify-center text-xl flex-shrink-0">
                            <i class="fa-regular fa-clock"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Jam Operasional Kantor</h4>
                            <p class="text-sm font-semibold text-slate-800">
                                <?= htmlspecialchars($siteSettings['jam_kerja']) ?>
                            </p>
                        </div>
                    </div>

                </div>

                <!-- Google Map Preview Card -->
                <div class="bg-white rounded-3xl p-4 border border-slate-200 shadow-sm overflow-hidden">
                    <div class="rounded-2xl overflow-hidden aspect-video relative bg-slate-100">
                        <iframe 
                            title="Peta SMP Islam Al-Kholili Kedopok Probolinggo"
                            src="https://maps.google.com/maps?q=Kedopok,+Kota+Probolinggo,+Jawa+Timur&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                            class="w-full h-full border-0" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                    <div class="p-3 text-center">
                        <a href="https://maps.google.com/?q=Kedopok,+Kota+Probolinggo" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-brand-700 hover:underline">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i> Buka Rute di Google Maps
                        </a>
                    </div>
                </div>

            </div>

            <!-- Contact Message Form Right -->
            <div class="lg:col-span-7">
                <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 shadow-sm">
                    <div class="mb-6">
                        <span class="text-xs font-bold uppercase tracking-wider text-brand-700 bg-brand-50 px-3 py-1 rounded-md">
                            Kirim Pesan
                        </span>
                        <h3 class="text-2xl font-heading font-extrabold text-slate-900 mt-2">
                            Formulir Pesan & Pertanyaan
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1">
                            Sampaikan saran, pertanyaan, atau konsultasi pendaftaran melalui form ini.
                        </p>
                    </div>

                    <form action="kontak.php" method="POST" class="space-y-5">
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="nama">
                                Nama Lengkap Anda <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nama" name="nama" required placeholder="Bapak / Ibu / Calon Siswa" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-600 text-sm">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="telepon">
                                    Nomor Telepon / WhatsApp <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" id="telepon" name="telepon" required placeholder="08xxxxxxxxxx" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-600 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="email">
                                    Alamat Email
                                </label>
                                <input type="email" id="email" name="email" placeholder="nama@email.com" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-600 text-sm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="pesan">
                                Isi Pesan / Pertanyaan <span class="text-red-500">*</span>
                            </label>
                            <textarea id="pesan" name="pesan" required rows="5" placeholder="Tuliskan pesan atau pertanyaan Anda di sini..." class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-600 text-sm"></textarea>
                        </div>

                        <button type="submit" name="send_message" value="1" class="w-full py-4 rounded-xl font-bold text-white bg-gradient-to-r from-brand-700 to-emerald-600 hover:from-brand-800 hover:to-emerald-700 shadow-lg shadow-brand-700/20 transition flex items-center justify-center gap-2 text-base">
                            <i class="fa-regular fa-paper-plane"></i>
                            <span>Kirim Pesan Sekarang</span>
                        </button>

                    </form>
                </div>
            </div>

        </div>

    </div>
</section>

<?php include 'includes/footer.php'; ?>
