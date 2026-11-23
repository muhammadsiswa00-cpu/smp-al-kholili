<?php
$admin_page = "galeri";
$admin_title = "Upload & Kelola Foto Galeri";
require_once __DIR__ . '/header.php';

$galleryFile = __DIR__ . '/../data/gallery.json';
$galleries = [];
if (file_exists($galleryFile)) {
    $galleries = json_decode(file_get_contents($galleryFile), true) ?: [];
}

$success = "";
$error = "";

// 1. Handle Upload Photo
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['upload_photo'])) {
    $judul = trim($_POST['judul'] ?? '');
    $kategori = trim($_POST['kategori'] ?? 'belajar');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $tanggal = trim($_POST['tanggal'] ?? date('d F Y'));

    $kategori_labels = [
        "ibadah" => "Keagamaan & Ibadah",
        "tahfidz" => "Tahfidz & Qur'an",
        "belajar" => "Belajar Mengajar",
        "ekskul" => "Ekstrakurikuler",
        "fasilitas" => "Fasilitas Kampus"
    ];

    $kategori_icons = [
        "ibadah" => "fa-mosque",
        "tahfidz" => "fa-book-quran",
        "belajar" => "fa-chalkboard-user",
        "ekskul" => "fa-campground",
        "fasilitas" => "fa-building-columns"
    ];

    if (empty($judul)) {
        $error = "Judul foto wajib diisi!";
    } elseif (isset($_FILES['photo_file']) && $_FILES['photo_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmp = $_FILES['photo_file']['tmp_name'];
        $fileName = $_FILES['photo_file']['name'];
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($ext, $allowed)) {
            $newPhotoName = "gal_" . time() . "_" . rand(100, 999) . "." . $ext;
            $dest = __DIR__ . '/../assets/uploads/gallery/' . $newPhotoName;

            if (move_uploaded_file($fileTmp, $dest)) {
                $newEntry = [
                    "id" => "gal-" . time(),
                    "judul" => $judul,
                    "kategori" => $kategori,
                    "kategori_label" => $kategori_labels[$kategori] ?? "Kegiatan",
                    "tanggal" => $tanggal,
                    "deskripsi" => $deskripsi,
                    "icon" => $kategori_icons[$kategori] ?? "fa-image",
                    "foto" => "assets/uploads/gallery/" . $newPhotoName,
                    "gradient" => "from-brand-900 to-emerald-950"
                ];

                array_unshift($galleries, $newEntry);
                file_put_contents($galleryFile, json_encode($galleries, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                $success = "Foto berhasil diunggah dan langsung tampil di galeri website!";
            } else {
                $error = "Gagal memindahkan file foto ke direktori tujuan.";
            }
        } else {
            $error = "Format file foto harus berupa JPG, PNG, atau WEBP.";
        }
    } else {
        $error = "Silakan pilih file foto untuk diunggah.";
    }
}

// 2. Handle Delete Photo
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['delete_photo'])) {
    $delId = $_POST['photo_id'] ?? '';
    $updated = [];
    foreach ($galleries as $item) {
        if ($item['id'] === $delId) {
            // Delete file from disk if exists
            if (!empty($item['foto']) && file_exists(__DIR__ . '/../' . $item['foto'])) {
                @unlink(__DIR__ . '/../' . $item['foto']);
            }
        } else {
            $updated[] = $item;
        }
    }
    $galleries = $updated;
    file_put_contents($galleryFile, json_encode($galleries, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    $success = "Foto berhasil dihapus dari galeri.";
}
?>

<div class="space-y-8 max-w-6xl mx-auto">
    
    <!-- Success & Error Alerts -->
    <?php if (!empty($success)): ?>
    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-300 text-brand-900 flex items-center gap-3 shadow-sm">
        <i class="fa-solid fa-circle-check text-2xl text-emerald-600 flex-shrink-0"></i>
        <div class="flex-1 text-sm font-semibold"><?= htmlspecialchars($success) ?></div>
        <a href="../galeri.php" target="_blank" class="px-3 py-1.5 rounded-lg bg-brand-700 text-white text-xs font-bold hover:bg-brand-800 transition">
            Lihat di Galeri &rarr;
        </a>
    </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
    <div class="p-4 rounded-2xl bg-red-50 border border-red-300 text-red-800 flex items-center gap-3 shadow-sm">
        <i class="fa-solid fa-triangle-exclamation text-2xl text-red-600 flex-shrink-0"></i>
        <div class="text-sm font-semibold"><?= htmlspecialchars($error) ?></div>
    </div>
    <?php endif; ?>

    <!-- UPLOAD FORM ACCORDION/CARD -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
        <div class="border-b border-slate-100 pb-4">
            <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-blue-50 text-blue-800">
                Formulir Upload
            </span>
            <h2 class="text-xl font-heading font-extrabold text-slate-900 mt-1 flex items-center gap-2">
                <i class="fa-solid fa-cloud-arrow-up text-brand-700"></i> Upload Foto Kegiatan Baru
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">
                Unggah dokumentasi foto kegiatan siswa, sholat berjamaah, hafalan Al-Qur'an, dan fasilitas sekolah.
            </p>
        </div>

        <form action="galeri.php" method="POST" enctype="multipart/form-data" class="space-y-5">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="judul">
                        Judul Kegiatan Foto <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="judul" name="judul" required placeholder="Contoh: Sholat Dhuha Berjamaah Siswa Kelas VII" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-600 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="kategori">
                        Kategori Kegiatan <span class="text-red-500">*</span>
                    </label>
                    <select id="kategori" name="kategori" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-600 text-sm bg-white">
                        <option value="ibadah">Keagamaan & Ibadah (Masjid Baiturrohman)</option>
                        <option value="tahfidz">Tahfidz & Kajian Qur'an</option>
                        <option value="belajar" selected>Belajar Mengajar & Kelas</option>
                        <option value="ekskul">Ekstrakurikuler (Hadrah / Pramuka / Futsal)</option>
                        <option value="fasilitas">Fasilitas Kampus & Laboratorium</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="tanggal">
                        Keterangan Waktu / Tanggal
                    </label>
                    <input type="text" id="tanggal" name="tanggal" value="<?= date('d F Y') ?>" placeholder="Contoh: Setiap Senin Pagi" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-600 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="photo_file">
                        Pilih File Foto (JPG/PNG/WEBP) <span class="text-red-500">*</span>
                    </label>
                    <input type="file" id="photo_file" name="photo_file" required accept="image/*" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brand-700 file:text-white hover:file:bg-brand-800 cursor-pointer">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="deskripsi">
                    Deskripsi Singkat Foto
                </label>
                <textarea id="deskripsi" name="deskripsi" rows="2" placeholder="Jelaskan secara singkat suasana atau tujuan kegiatan ini..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-600 text-sm"></textarea>
            </div>

            <button type="submit" name="upload_photo" value="1" class="px-6 py-3 rounded-xl font-bold text-white bg-gradient-to-r from-brand-700 to-emerald-600 hover:from-brand-800 transition shadow-md flex items-center gap-2 text-sm">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                <span>Unggah Foto Sekarang</span>
            </button>

        </form>
    </div>

    <!-- LIST OF UPLOADED PHOTOS -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <h3 class="text-lg font-heading font-extrabold text-slate-900">
                    Daftar Foto Galeri Saat Ini (<?= count($galleries) ?>)
                </h3>
                <p class="text-xs text-slate-500">Foto yang aktif ditampilkan kepada pengunjung website.</p>
            </div>
            <a href="../galeri.php" target="_blank" class="text-xs font-bold text-brand-700 hover:underline flex items-center gap-1">
                <span>Lihat Tampilan Publik</span> <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
            </a>
        </div>

        <?php if (empty($galleries)): ?>
        <div class="text-center py-12 text-slate-400">
            <i class="fa-regular fa-images text-5xl mb-3"></i>
            <p class="text-sm">Belum ada foto galeri yang diunggah.</p>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($galleries as $g): ?>
            <div class="bg-slate-50 rounded-2xl border border-slate-200 overflow-hidden flex flex-col justify-between hover:shadow-md transition">
                <div>
                    <!-- Visual Frame -->
                    <div class="aspect-[4/3] bg-gradient-to-br <?= $g['gradient'] ?? 'from-brand-900 to-emerald-950' ?> relative overflow-hidden flex items-center justify-center">
                        <?php if (!empty($g['foto']) && file_exists(__DIR__ . '/../' . $g['foto'])): ?>
                            <img src="../<?= htmlspecialchars($g['foto']) ?>" alt="<?= htmlspecialchars($g['judul']) ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <i class="fa-solid <?= $g['icon'] ?? 'fa-image' ?> text-4xl text-gold-400/80"></i>
                        <?php endif; ?>

                        <span class="absolute top-2 left-2 text-[10px] font-bold uppercase tracking-wider bg-black/60 text-white px-2 py-0.5 rounded-full">
                            <?= htmlspecialchars($g['kategori_label'] ?? $g['kategori']) ?>
                        </span>
                    </div>

                    <div class="p-4">
                        <h4 class="font-heading font-bold text-sm text-slate-900 leading-snug line-clamp-2 mb-1">
                            <?= htmlspecialchars($g['judul']) ?>
                        </h4>
                        <p class="text-[11px] text-slate-500 line-clamp-2">
                            <?= htmlspecialchars($g['deskripsi']) ?>
                        </p>
                    </div>
                </div>

                <div class="p-3 pt-0 border-t border-slate-200/60 mt-2 flex items-center justify-between">
                    <span class="text-[10px] text-slate-400"><?= htmlspecialchars($g['tanggal']) ?></span>
                    <form action="galeri.php" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto ini?')">
                        <input type="hidden" name="photo_id" value="<?= htmlspecialchars($g['id']) ?>">
                        <button type="submit" name="delete_photo" value="1" class="text-xs font-bold text-red-600 hover:text-red-800 p-1 transition" title="Hapus Foto">
                            <i class="fa-solid fa-trash-can mr-1"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

    </div>

</div>

<?php require_once __DIR__ . '/footer.php'; ?>
