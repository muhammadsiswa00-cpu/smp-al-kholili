<?php
$admin_page = "ppdb";
$admin_title = "Data Pendaftar PPDB Online";
require_once __DIR__ . '/header.php';

$ppdbFile = __DIR__ . '/../data/ppdb_submissions.json';
$ppdbList = [];
if (file_exists($ppdbFile)) {
    $ppdbList = json_decode(file_get_contents($ppdbFile), true) ?: [];
}

// Handle Delete Entry
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['delete_ppdb'])) {
    $delId = $_POST['reg_id'] ?? '';
    $updated = [];
    foreach ($ppdbList as $item) {
        if (($item['id'] ?? '') !== $delId) {
            $updated[] = $item;
        }
    }
    $ppdbList = $updated;
    file_put_contents($ppdbFile, json_encode($ppdbList, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}
?>

<div class="space-y-6 max-w-6xl mx-auto">
    
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5 mb-6">
            <div>
                <h2 class="text-xl font-heading font-extrabold text-slate-900">
                    Data Pendaftaran Siswa Baru (PPDB) Masuk
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Total pendaftar tercatat: <strong><?= count($ppdbList) ?> siswa</strong>. Tautan website PPDB eksternal Anda saat ini: 
                    <a href="<?= htmlspecialchars($siteSettings['ppdb_url']) ?>" target="_blank" class="text-brand-700 font-bold hover:underline">
                        <?= htmlspecialchars($siteSettings['ppdb_url']) ?>
                    </a>
                </p>
            </div>
            <div>
                <a href="pengaturan.php#ppdb_section" class="px-4 py-2 rounded-xl text-xs font-bold text-amber-900 bg-amber-100 hover:bg-amber-200 transition inline-flex items-center gap-1.5">
                    <i class="fa-solid fa-link"></i> Ubah Tautan Web PPDB
                </a>
            </div>
        </div>

        <?php if (empty($ppdbList)): ?>
        <div class="text-center py-12 text-slate-400">
            <i class="fa-solid fa-user-graduate text-5xl mb-3 text-slate-300"></i>
            <p class="text-sm font-medium">Belum ada data pendaftar yang masuk dari form website.</p>
            <p class="text-xs text-slate-400 mt-1">Jika pendaftaran dialihkan ke portal PPDB eksternal Anda, data akan masuk langsung ke portal tersebut.</p>
        </div>
        <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-bold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3">No. Registrasi</th>
                        <th class="px-4 py-3">Nama Siswa</th>
                        <th class="px-4 py-3">Asal Sekolah</th>
                        <th class="px-4 py-3">Jalur</th>
                        <th class="px-4 py-3">Orang Tua / Wali</th>
                        <th class="px-4 py-3">Kontak WhatsApp</th>
                        <th class="px-4 py-3">Alamat</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach (array_reverse($ppdbList) as $row): ?>
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 font-mono font-bold text-brand-800"><?= htmlspecialchars($row['id'] ?? '-') ?></td>
                        <td class="px-4 py-3 font-semibold text-slate-800"><?= htmlspecialchars($row['nama'] ?? '-') ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($row['asal_sekolah'] ?? '-') ?></td>
                        <td class="px-4 py-3"><span class="px-2 py-0.5 rounded bg-emerald-100 text-brand-800 font-bold"><?= htmlspecialchars($row['jalur'] ?? '-') ?></span></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($row['nama_ortu'] ?? '-') ?></td>
                        <td class="px-4 py-3">
                            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $row['no_wa'] ?? '') ?>" target="_blank" class="text-green-600 font-bold hover:underline inline-flex items-center gap-1">
                                <i class="fa-brands fa-whatsapp"></i> <?= htmlspecialchars($row['no_wa'] ?? '-') ?>
                            </a>
                        </td>
                        <td class="px-4 py-3 max-w-xs truncate" title="<?= htmlspecialchars($row['alamat'] ?? '') ?>"><?= htmlspecialchars($row['alamat'] ?? '-') ?></td>
                        <td class="px-4 py-3 text-center">
                            <form action="data_ppdb.php" method="POST" onsubmit="return confirm('Hapus data pendaftar ini?')">
                                <input type="hidden" name="reg_id" value="<?= htmlspecialchars($row['id'] ?? '') ?>">
                                <button type="submit" name="delete_ppdb" value="1" class="text-red-500 hover:text-red-700 p-1" title="Hapus">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

    </div>

</div>

<?php require_once __DIR__ . '/footer.php'; ?>
