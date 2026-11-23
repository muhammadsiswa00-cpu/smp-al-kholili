<?php
require_once __DIR__ . '/settings.php';
$ppdbUrl = !empty($siteSettings['ppdb_url']) ? $siteSettings['ppdb_url'] : 'ppdb.php';
$isPpdbExt = !empty($siteSettings['ppdb_url']) && str_starts_with($siteSettings['ppdb_url'], 'http');
?>
    <!-- Footer Section -->
    <footer class="bg-gradient-to-b from-brand-950 to-slate-950 text-slate-300 pt-16 pb-8 border-t-4 border-gold-500 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 pb-12 border-b border-slate-800">
                
                <!-- School Info Column -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 p-1 bg-white rounded-xl shadow">
                            <img src="<?= htmlspecialchars($siteSettings['logo']) ?>" alt="Logo SMP Islam Al-Kholili" class="w-full h-full object-contain">
                        </div>
                        <div>
                            <h3 class="text-white font-heading font-extrabold text-lg leading-tight"><?= htmlspecialchars($siteSettings['site_name']) ?></h3>
                            <p class="text-xs text-gold-400 font-semibold tracking-wider"><?= htmlspecialchars($siteSettings['site_tagline']) ?></p>
                        </div>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed">
                        Lembaga Pendidikan Menengah Pertama Islam di Kota Probolinggo yang berdedikasi mencetak generasi beriman, bertaqwa, berakhlak mulia, cerdas, dan mandiri berlandaskan ajaran Islam.
                    </p>
                    <div class="pt-2">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Ikuti Media Sosial Kami:</h4>
                        <div class="flex items-center gap-2.5">
                            <a href="#" class="w-9 h-9 rounded-lg bg-slate-800/80 hover:bg-brand-600 text-slate-300 hover:text-white flex items-center justify-center transition" title="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                            <a href="#" class="w-9 h-9 rounded-lg bg-slate-800/80 hover:bg-brand-600 text-slate-300 hover:text-white flex items-center justify-center transition" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
                            <a href="#" class="w-9 h-9 rounded-lg bg-slate-800/80 hover:bg-brand-600 text-slate-300 hover:text-white flex items-center justify-center transition" title="YouTube"><i class="fa-brands fa-youtube"></i></a>
                            <a href="#" class="w-9 h-9 rounded-lg bg-slate-800/80 hover:bg-brand-600 text-slate-300 hover:text-white flex items-center justify-center transition" title="TikTok"><i class="fa-brands fa-tiktok"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Quick Navigation Links -->
                <div>
                    <h3 class="text-white font-heading font-bold text-base border-l-4 border-gold-400 pl-3 mb-5 uppercase tracking-wider">
                        Tautan Cepat
                    </h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="index.php" class="hover:text-emerald-400 transition flex items-center gap-2"><i class="fa-solid fa-chevron-right text-xs text-gold-400"></i> Beranda</a></li>
                        <li><a href="profil.php" class="hover:text-emerald-400 transition flex items-center gap-2"><i class="fa-solid fa-chevron-right text-xs text-gold-400"></i> Profil & Visi Misi</a></li>
                        <li><a href="prestasi.php" class="hover:text-emerald-400 transition flex items-center gap-2"><i class="fa-solid fa-chevron-right text-xs text-gold-400"></i> Prestasi Siswa</a></li>
                        <li><a href="galeri.php" class="hover:text-emerald-400 transition flex items-center gap-2"><i class="fa-solid fa-chevron-right text-xs text-gold-400"></i> Galeri Dokumentasi</a></li>
                        <li><a href="guru.php" class="hover:text-emerald-400 transition flex items-center gap-2"><i class="fa-solid fa-chevron-right text-xs text-gold-400"></i> Guru</a></li>
                        <li><a href="berita.php" class="hover:text-emerald-400 transition flex items-center gap-2"><i class="fa-solid fa-chevron-right text-xs text-gold-400"></i> Berita</a></li>
                        <li><a href="<?= htmlspecialchars($ppdbUrl) ?>" <?= $isPpdbExt ? 'target="_blank" rel="noopener noreferrer"' : '' ?> class="text-gold-400 hover:text-gold-300 transition font-semibold flex items-center gap-2"><i class="fa-solid fa-graduation-cap text-xs"></i> Buka Web PPDB Online</a></li>
                        <li class="pt-2"><a href="admin/login.php" class="text-xs text-slate-500 hover:text-brand-400 transition flex items-center gap-1.5"><i class="fa-solid fa-lock text-[10px]"></i> Login Admin Sekolah</a></li>
                    </ul>
                </div>

                <!-- Program Unggulan -->
                <div>
                    <h3 class="text-white font-heading font-bold text-base border-l-4 border-gold-400 pl-3 mb-5 uppercase tracking-wider">
                        Program Unggulan
                    </h3>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-book-quran text-gold-400 mt-1"></i>
                            <div>
                                <span class="font-semibold text-white block">Tahfidzul Qur'an</span>
                                <span class="text-xs text-slate-400">Bimbingan hafalan Al-Qur'an tartil & mutqin.</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-mosque text-gold-400 mt-1"></i>
                            <div>
                                <span class="font-semibold text-white block">Pendidikan Akhlak & Karakter</span>
                                <span class="text-xs text-slate-400">Pembiasaan sholat berjamaah di Baiturrohman.</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-laptop-code text-gold-400 mt-1"></i>
                            <div>
                                <span class="font-semibold text-white block">Literasi Digital & Komputer</span>
                                <span class="text-xs text-slate-400">Penguasaan teknologi modern sejak dini.</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-star-and-crescent text-gold-400 mt-1"></i>
                            <div>
                                <span class="font-semibold text-white block">Seni Hadrah & Kepanduan</span>
                                <span class="text-xs text-slate-400">Pengembangan bakat seni islami & kepemimpinan.</span>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Contact & Location Column -->
                <div>
                    <h3 class="text-white font-heading font-bold text-base border-l-4 border-gold-400 pl-3 mb-5 uppercase tracking-wider">
                        Hubungi Kami
                    </h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-start gap-3">
                            <i class="fa-solid fa-location-dot text-gold-400 mt-1 text-base"></i>
                            <span class="text-slate-300">
                                <strong>Kampus SMP Islam Al-Kholili</strong><br>
                                <?= htmlspecialchars($siteSettings['alamat']) ?>
                            </span>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-phone text-gold-400"></i>
                            <a href="tel:<?= preg_replace('/[^0-9]/', '', $siteSettings['phone']) ?>" class="hover:text-emerald-400 transition"><?= htmlspecialchars($siteSettings['phone']) ?></a>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fa-brands fa-whatsapp text-green-400 text-base flex-shrink-0"></i>
                            <a href="https://wa.me/<?= htmlspecialchars($siteSettings['whatsapp_number']) ?>?text=Halo%20Admin%20SMP%20Islam%20Al-Kholili,%20saya%20ingin%20informasi%20pendaftaran" target="_blank" class="text-green-400 hover:underline font-medium whitespace-nowrap">WhatsApp: <?= htmlspecialchars($siteSettings['whatsapp_display']) ?></a>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fa-regular fa-envelope text-gold-400"></i>
                            <a href="mailto:<?= htmlspecialchars($siteSettings['email']) ?>" class="hover:text-emerald-400 transition"><?= htmlspecialchars($siteSettings['email']) ?></a>
                        </div>
                        <div class="pt-2">
                            <a href="kontak.php" class="inline-flex items-center gap-2 text-xs font-bold text-white bg-brand-700 hover:bg-brand-600 px-4 py-2 rounded-lg transition">
                                <i class="fa-solid fa-map-location-dot"></i> Lihat Peta Lokasi
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Bottom Copyright & Credit -->
            <div class="pt-8 flex flex-col sm:flex-row justify-between items-center text-xs text-slate-500 gap-3">
                <p>&copy; <?= date('Y') ?> <strong>SMP Islam Al-Kholili Kota Probolinggo</strong>. Hak Cipta Dilindungi Undang-Undang.</p>
                <p class="flex items-center gap-1">
                    <span>Mencetak Generasi Unggul & Berakhlakul Karimah</span>
                </p>
            </div>
        </div>
    </footer>

    <!-- Floating WhatsApp Action Button -->
    <a href="https://wa.me/<?= htmlspecialchars($siteSettings['whatsapp_number']) ?>?text=Assalamu'alaikum%20Admin%20SMP%20Islam%20Al-Kholili,%20saya%20ingin%20konsultasi%20mengenai%20sekolah" target="_blank" class="fixed bottom-6 right-6 z-40 group flex items-center bg-green-500 hover:bg-green-600 text-white p-3.5 sm:px-4 sm:py-3 rounded-full shadow-2xl hover:shadow-green-500/50 transition-all duration-300 transform hover:scale-105" title="Chat WhatsApp Customer Service">
        <i class="fa-brands fa-whatsapp text-2xl sm:text-2xl animate-pulse"></i>
        <span class="hidden sm:inline-block ml-2.5 font-bold text-sm tracking-wide">Tanya Kami (WA)</span>
    </a>

    <!-- Back to Top Button -->
    <button id="backToTop" class="hidden fixed bottom-6 left-6 z-30 p-3 bg-brand-800/90 text-white rounded-full shadow-lg hover:bg-brand-700 transition" aria-label="Kembali ke atas">
        <i class="fa-solid fa-arrow-up"></i>
    </button>

    <!-- Custom JavaScript -->
    <script src="assets/js/main.js"></script>
</body>
</html>
