<?php
$admin_page = "berita";
$admin_title = "Kelola Berita Sekolah";
require_once __DIR__ . '/header.php';

$newsFile = __DIR__ . '/../data/news.json';
$newsList = [];
if (file_exists($newsFile)) {
    $newsList = json_decode(file_get_contents($newsFile), true) ?: [];
}

$success = "";
$error = "";

// 1. Tambah Berita
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['add_news'])) {
    $judul = trim($_POST['judul'] ?? '');
    $kategori = trim($_POST['kategori'] ?? 'Kegiatan');
    $ringkasan = trim($_POST['ringkasan'] ?? '');
    $penulis = trim($_POST['penulis'] ?? 'Humas Sekolah');
    $tanggal = trim($_POST['tanggal'] ?? date('d F Y'));

    if (empty($judul) || empty($ringkasan)) {
        $error = "Judul dan ringkasan berita wajib diisi!";
    } else {
        $newItem = [
            "id" => "news-" . time(),
            "judul" => $judul,
            "kategori" => $kategori,
            "tanggal" => $tanggal,
            "penulis" => $penulis,
            "ringkasan" => $ringkasan,
            "icon" => "fa-newspaper",
            "badge" => "bg-emerald-100 text-emerald-800"
        ];

        array_unshift($newsList, $newItem);
        file_put_contents($newsFile, json_encode($newsList, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        $success = "Berita baru berhasil ditambahkan dan langsung terbit di website!";
    }
}

// 2. Hapus Berita
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['delete_news'])) {
    $delId = $_POST['news_id'] ?? '';
    $updated = [];
    foreach ($newsList as $item) {
        if ($item['id'] !== $delId) {
            $updated[] = $item;
        }
    }
    $newsList = $updated;
    file_put_contents($newsFile, json_encode($newsList, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    $success = "Berita berhasil dihapus.";
}
?>

<div class="space-y-8 max-w-6xl mx-auto">
    
    <?php if (!empty($success)): ?>
    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-300 text-brand-900 flex items-center gap-3 shadow-sm">
        <i class="fa-solid fa-circle-check text-2xl text-emerald-600 flex-shrink-0"></i>
        <div class="flex-1 text-sm font-semibold"><?= htmlspecialchars($success) ?></div>
        <a href="../berita.php" target="_blank" class="px-3 py-1.5 rounded-lg bg-brand-700 text-white text-xs font-bold hover:bg-brand-800 transition">
            Lihat Berita &rarr;
        </a>
    </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
    <div class="p-4 rounded-2xl bg-red-50 border border-red-300 text-red-800 flex items-center gap-3 shadow-sm">
        <i class="fa-solid fa-triangle-exclamation text-2xl text-red-600 flex-shrink-0"></i>
        <div class="text-sm font-semibold"><?= htmlspecialchars($error) ?></div>
    </div>
    <?php endif; ?>

    <!-- FORM TAMBAH BERITA -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
        <div class="border-b border-slate-100 pb-4">
            <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-purple-50 text-purple-800">
                Publikasi Informasi
            </span>
            <h2 class="text-xl font-heading font-extrabold text-slate-900 mt-1 flex items-center gap-2">
                <i class="fa-solid fa-pen-nib text-brand-700"></i> Tambah Berita / Pengumuman Baru
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">
                Bagikan informasi kegiatan sekolah, prestasi terbaru, atau pengumuman penting kepada publik.
            </p>
        </div>

        <form action="berita.php" method="POST" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="judul">
                    Judul Berita <span class="text-red-500">*</span>
                </label>
                <input type="text" id="judul" name="judul" required placeholder="Contoh: Peringatan Maulid Nabi Muhammad SAW di SMP Islam Al-Kholili" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-600 text-sm">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="kategori">
                        Kategori Berita
                    </label>
                    <input type="text" id="kategori" name="kategori" value="Kegiatan Sekolah" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="penulis">
                        Penulis / Sumber
                    </label>
                    <input type="text" id="penulis" name="penulis" value="Humas Sekolah" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="tanggal">
                        Tanggal Berita
                    </label>
                    <input type="text" id="tanggal" name="tanggal" value="<?= date('d F Y') ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="ringkasan">
                    Isi Ringkasan Berita <span class="text-red-500">*</span>
                </label>
                <textarea id="ringkasan" name="ringkasan" rows="3" required placeholder="Tuliskan intisari kegiatan atau pengumuman secara padat dan jelas..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-600 text-sm"></textarea>
            </div>

            <button type="submit" name="add_news" value="1" class="px-6 py-3 rounded-xl font-bold text-white bg-gradient-to-r from-brand-700 to-emerald-600 hover:from-brand-800 transition shadow-md flex items-center gap-2 text-sm">
                <i class="fa-solid fa-paper-plane"></i>
                <span>Terbitkan Berita</span>
            </button>
        </form>
    </div>

    <!-- DAFTAR BERITA -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-4">
        <h3 class="text-lg font-heading font-extrabold text-slate-900 border-b border-slate-100 pb-3">
            Daftar Berita Terbit (<?= count($newsList) ?>)
        </h3>

        <div class="space-y-3">
            <?php foreach ($newsList as $n): ?>
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-emerald-100 text-brand-800">
                        <?= htmlspecialchars($n['kategori']) ?>
                    </span>
                    <h4 class="font-heading font-bold text-base text-slate-900">
                        <?= htmlspecialchars($n['judul']) ?>
                    </h4>
                    <p class="text-xs text-slate-600 line-clamp-1">
                        <?= htmlspecialchars($n['ringkasan']) ?>
                    </p>
                    <span class="text-[11px] text-slate-400 block">
                        <?= htmlspecialchars($n['tanggal']) ?> &bull; Oleh <?= htmlspecialchars($n['penulis']) ?>
                    </span>
                </div>
                <div>
                    <form action="berita.php" method="POST" onsubmit="return confirm('Hapus berita ini?')">
                        <input type="hidden" name="news_id" value="<?= htmlspecialchars($n['id']) ?>">
                        <button type="submit" name="delete_news" value="1" class="px-3 py-1.5 rounded-lg text-xs font-bold text-red-600 hover:bg-red-50 border border-red-200 transition">
                            <i class="fa-solid fa-trash-can mr-1"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/footer.php'; ?>
