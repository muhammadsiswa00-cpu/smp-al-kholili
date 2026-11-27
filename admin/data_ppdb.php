<?php
$admin_page = "ppdb";
$admin_title = "Data Pendaftar PPDB Online";
require_once __DIR__ . '/header.php';

/** @var array<string, mixed> $siteSettings */
global $siteSettings;
if (!isset($siteSettings)) {
    $siteSettings = get_all_settings();
}

$ppdbFile = __DIR__ . '/../data/ppdb_submissions.json';
$ppdbList = [];
if (file_exists($ppdbFile)) {
    $ppdbList = json_decode(file_get_contents($ppdbFile), true) ?: [];
}

$alert = "";

// Handle Delete Entry
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (isset($_POST['delete_ppdb'])) {
        $delId = $_POST['reg_id'] ?? '';
        $updated = [];
        foreach ($ppdbList as $item) {
            if (($item['id'] ?? '') !== $delId) {
                $updated[] = $item;
            }
        }
        $ppdbList = $updated;
        file_put_contents($ppdbFile, json_encode($ppdbList, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        $alert = "Data pendaftar berhasil dihapus.";
    } elseif (isset($_POST['update_status'])) {
        $upId = $_POST['reg_id'] ?? '';
        $newStatus = $_POST['status'] ?? 'Menunggu';
        foreach ($ppdbList as &$item) {
            if (($item['id'] ?? '') === $upId) {
                $item['status'] = $newStatus;
                break;
            }
        }
        file_put_contents($ppdbFile, json_encode($ppdbList, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        $alert = "Status verifikasi calon siswa berhasil diubah!";
    }
}
?>

<div class="space-y-6 max-w-7xl mx-auto">
    
    <?php if (!empty($alert)): ?>
    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-emerald-600"></i>
            <span class="text-sm font-semibold"><?= htmlspecialchars($alert) ?></span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-slate-400 hover:text-slate-700">&times;</button>
    </div>
    <?php endif; ?>

    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5 mb-6">
            <div>
                <h2 class="text-xl font-heading font-extrabold text-slate-900">
                    Data Pendaftaran Siswa Baru (PPDB) Masuk
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Total pendaftar tercatat: <strong><?= count($ppdbList) ?> calon siswa</strong>. Tautan formulir pendaftaran: 
                    <a href="../ppdb.php" target="_blank" class="text-brand-700 font-bold hover:underline">
                        ppdb.php
                    </a>
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="../ppdb.php" target="_blank" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-brand-700 hover:bg-brand-800 transition inline-flex items-center gap-1.5 shadow">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i> Buka Form PPDB Web
                </a>
            </div>
        </div>

        <?php if (empty($ppdbList)): ?>
        <div class="text-center py-16 text-slate-400">
            <i class="fa-solid fa-user-graduate text-5xl mb-3 text-slate-300"></i>
            <p class="text-sm font-medium">Belum ada data calon siswa pendaftar PPDB.</p>
            <p class="text-xs text-slate-400 mt-1">Formulir pendaftaran dapat diakses di halaman <code>ppdb.php</code>.</p>
        </div>
        <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-bold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3.5 rounded-l-xl">No. Registrasi</th>
                        <th class="px-4 py-3.5">Nama Calon Siswa</th>
                        <th class="px-4 py-3.5">NISN / JK</th>
                        <th class="px-4 py-3.5">Asal Sekolah</th>
                        <th class="px-4 py-3.5">Jalur</th>
                        <th class="px-4 py-3.5">Orang Tua / Wali</th>
                        <th class="px-4 py-3.5">WhatsApp</th>
                        <th class="px-4 py-3.5 text-center">Status</th>
                        <th class="px-4 py-3.5 text-center rounded-r-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    <?php foreach (array_reverse($ppdbList) as $row): 
                        $stClass = match($row['status'] ?? 'Menunggu') {
                            'Diterima' => 'bg-emerald-100 text-emerald-800',
                            'Ditolak' => 'bg-red-100 text-red-800',
                            'Cadangan' => 'bg-amber-100 text-amber-800',
                            default => 'bg-blue-100 text-blue-800'
                        };
                    ?>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3.5 font-mono font-bold text-brand-800">
                            <?= htmlspecialchars($row['id'] ?? '-') ?>
                        </td>
                        <td class="px-4 py-3.5 font-bold text-slate-900">
                            <?= htmlspecialchars($row['nama'] ?? $row['nama_siswa'] ?? '-') ?>
                        </td>
                        <td class="px-4 py-3.5">
                            <span class="block font-semibold"><?= htmlspecialchars($row['nisn'] ?? '-') ?></span>
                            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded <?= ($row['jenis_kelamin'] ?? '') === 'P' ? 'bg-pink-100 text-pink-700' : 'bg-blue-100 text-blue-700' ?>">
                                <?= ($row['jenis_kelamin'] ?? '') === 'P' ? 'Perempuan' : 'Laki-Laki' ?>
                            </span>
                        </td>
                        <td class="px-4 py-3.5"><?= htmlspecialchars($row['asal_sekolah'] ?? '-') ?></td>
                        <td class="px-4 py-3.5">
                            <span class="px-2 py-0.5 rounded-lg bg-emerald-100 text-brand-800 font-bold text-[11px]">
                                <?= htmlspecialchars($row['jalur'] ?? 'Reguler') ?>
                            </span>
                        </td>
                        <td class="px-4 py-3.5">
                            <span class="block font-semibold"><?= htmlspecialchars($row['nama_ayah'] ?? $row['nama_ortu'] ?? '-') ?></span>
                            <span class="text-[11px] text-slate-400">Ibu: <?= htmlspecialchars($row['nama_ibu'] ?? '-') ?></span>
                        </td>
                        <td class="px-4 py-3.5">
                            <?php if (!empty($row['no_wa'])): ?>
                            <a href="https://wa.me/<?= preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $row['no_wa'])) ?>?text=Halo%20<?= urlencode($row['nama'] ?? '') ?>,%20kami%20dari%20panitia%20PPDB%20SMP%20Islam%20Al-Kholili" target="_blank" class="text-emerald-700 font-bold hover:underline inline-flex items-center gap-1">
                                <i class="fa-brands fa-whatsapp text-emerald-600 text-sm"></i> <?= htmlspecialchars($row['no_wa']) ?>
                            </a>
                            <?php else: ?>
                            -
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold <?= $stClass ?>">
                                <?= htmlspecialchars($row['status'] ?? 'Menunggu') ?>
                            </span>
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <button type="button" onclick='showPpdbDetail(<?= json_encode($row) ?>)' class="px-2.5 py-1.5 rounded-lg bg-brand-50 text-brand-700 hover:bg-brand-100 font-bold transition flex items-center gap-1" title="Lihat Berkas & Detail Lengkap">
                                    <i class="fa-solid fa-file-lines"></i> Detail
                                </button>
                                <form action="data_ppdb.php" method="POST" onsubmit="return confirm('Hapus data calon siswa ini?')">
                                    <input type="hidden" name="reg_id" value="<?= htmlspecialchars($row['id'] ?? '') ?>">
                                    <button type="submit" name="delete_ppdb" value="1" class="p-1.5 rounded-lg text-red-500 hover:bg-red-50 transition" title="Hapus">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

    </div>

</div>

<!-- Modal Detail Lengkap & Berkas Dokumen Calon Siswa -->
<div id="detailPpdbModal" class="hidden fixed inset-0 z-50 bg-black/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-4xl w-full p-6 sm:p-8 shadow-2xl border border-slate-100 max-h-[92vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-5">
            <div>
                <span class="text-[11px] font-bold text-brand-700 uppercase tracking-wider" id="detailModalReg">REGISTRASI</span>
                <h3 id="detailModalNama" class="text-xl font-heading font-extrabold text-slate-900">Detail Calon Siswa</h3>
            </div>
            <button type="button" onclick="closePpdbDetail()" class="text-slate-400 hover:text-slate-700 text-2xl font-bold">&times;</button>
        </div>

        <div class="space-y-6 text-xs text-slate-700">
            
            <!-- Section 1: Data Calon Siswa -->
            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200">
                <h4 class="font-bold text-brand-800 uppercase tracking-wider text-[11px] mb-3 flex items-center gap-1.5">
                    <i class="fa-solid fa-user-graduate"></i> 1. Identitas Calon Siswa
                </h4>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    <div>
                        <span class="block text-slate-400 text-[10px] uppercase">Nama Lengkap</span>
                        <strong id="d_nama" class="text-slate-900">-</strong>
                    </div>
                    <div>
                        <span class="block text-slate-400 text-[10px] uppercase">NISN</span>
                        <strong id="d_nisn" class="text-slate-900">-</strong>
                    </div>
                    <div>
                        <span class="block text-slate-400 text-[10px] uppercase">Jenis Kelamin</span>
                        <strong id="d_jk" class="text-slate-900">-</strong>
                    </div>
                    <div>
                        <span class="block text-slate-400 text-[10px] uppercase">Tempat, Tanggal Lahir</span>
                        <strong id="d_ttl" class="text-slate-900">-</strong>
                    </div>
                    <div>
                        <span class="block text-slate-400 text-[10px] uppercase">Asal Sekolah</span>
                        <strong id="d_sekolah" class="text-slate-900">-</strong>
                    </div>
                    <div>
                        <span class="block text-slate-400 text-[10px] uppercase">Alamat Sekolah Asal</span>
                        <strong id="d_alamat_sekolah" class="text-slate-900">-</strong>
                    </div>
                    <div>
                        <span class="block text-slate-400 text-[10px] uppercase">Jalur Pendaftaran</span>
                        <strong id="d_jalur" class="text-emerald-700">-</strong>
                    </div>
                    <div>
                        <span class="block text-slate-400 text-[10px] uppercase">Email Siswa / Ortu</span>
                        <strong id="d_email" class="text-slate-900">-</strong>
                    </div>
                    <div>
                        <span class="block text-slate-400 text-[10px] uppercase">WhatsApp</span>
                        <strong id="d_wa" class="text-slate-900">-</strong>
                    </div>
                    <div class="col-span-2 sm:col-span-3">
                        <span class="block text-slate-400 text-[10px] uppercase">Alamat Tempat Tinggal Lengkap</span>
                        <strong id="d_alamat" class="text-slate-900">-</strong>
                    </div>
                </div>
            </div>

            <!-- Section 2: Data Orang Tua / Wali -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-blue-50/50 p-4 rounded-2xl border border-blue-100">
                    <h4 class="font-bold text-blue-900 uppercase tracking-wider text-[11px] mb-3 flex items-center gap-1.5">
                        <i class="fa-solid fa-person"></i> Data Ayah Kandung
                    </h4>
                    <div class="space-y-2">
                        <div><span class="text-slate-400 text-[10px] block">Nama Lengkap:</span> <strong id="d_nama_ayah">-</strong></div>
                        <div><span class="text-slate-400 text-[10px] block">TTL:</span> <strong id="d_ttl_ayah">-</strong></div>
                        <div><span class="text-slate-400 text-[10px] block">Pekerjaan:</span> <strong id="d_kerja_ayah">-</strong></div>
                        <div><span class="text-slate-400 text-[10px] block">Pendidikan Terakhir:</span> <strong id="d_pend_ayah">-</strong></div>
                        <div><span class="text-slate-400 text-[10px] block">No. HP / Telp:</span> <strong id="d_hp_ayah">-</strong></div>
                    </div>
                </div>

                <div class="bg-pink-50/50 p-4 rounded-2xl border border-pink-100">
                    <h4 class="font-bold text-pink-900 uppercase tracking-wider text-[11px] mb-3 flex items-center gap-1.5">
                        <i class="fa-solid fa-person-dress"></i> Data Ibu Kandung
                    </h4>
                    <div class="space-y-2">
                        <div><span class="text-slate-400 text-[10px] block">Nama Lengkap:</span> <strong id="d_nama_ibu">-</strong></div>
                        <div><span class="text-slate-400 text-[10px] block">TTL:</span> <strong id="d_ttl_ibu">-</strong></div>
                        <div><span class="text-slate-400 text-[10px] block">Pekerjaan:</span> <strong id="d_kerja_ibu">-</strong></div>
                        <div><span class="text-slate-400 text-[10px] block">Pendidikan Terakhir:</span> <strong id="d_pend_ibu">-</strong></div>
                        <div><span class="text-slate-400 text-[10px] block">No. HP / Telp:</span> <strong id="d_hp_ibu">-</strong></div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Berkas Dokumen yang Diunggah -->
            <div class="bg-amber-50/60 p-4 rounded-2xl border border-amber-200">
                <h4 class="font-bold text-amber-900 uppercase tracking-wider text-[11px] mb-3 flex items-center gap-1.5">
                    <i class="fa-solid fa-folder-open"></i> Dokumen Berkas yang Dilampirkan
                </h4>
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-3" id="berkasContainer">
                    <!-- Populated dynamically -->
                </div>
            </div>

            <!-- Section 4: Status Verifikasi & Tindakan -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-4 border-t border-slate-100">
                <form action="data_ppdb.php" method="POST" class="flex items-center gap-2">
                    <input type="hidden" name="update_status" value="1">
                    <input type="hidden" name="reg_id" id="statusFormRegId" value="">
                    <label class="text-xs font-bold text-slate-700">Ubah Status:</label>
                    <select name="status" id="statusSelect" class="px-3 py-1.5 rounded-xl border border-slate-300 font-bold text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                        <option value="Menunggu">Menunggu</option>
                        <option value="Diterima">Diterima</option>
                        <option value="Cadangan">Cadangan</option>
                        <option value="Ditolak">Ditolak</option>
                    </select>
                    <button type="submit" class="px-3.5 py-1.5 rounded-xl text-xs font-bold text-white bg-brand-700 hover:bg-brand-800 transition">
                        Simpan
                    </button>
                </form>

                <div class="flex items-center gap-2">
                    <a id="btnWaChat" href="#" target="_blank" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition flex items-center gap-1.5">
                        <i class="fa-brands fa-whatsapp"></i> Hubungi Calon Siswa
                    </a>
                    <button type="button" onclick="closePpdbDetail()" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition">
                        Tutup
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal Zoom Pratinjau Berkas Gambar (Lightbox) -->
<div id="imageLightbox" class="hidden fixed inset-0 z-50 bg-black/85 backdrop-blur-md flex items-center justify-center p-4">
    <div class="max-w-3xl w-full bg-slate-900 rounded-3xl p-4 shadow-2xl relative text-white">
        <div class="flex items-center justify-between pb-3 border-b border-slate-800 mb-3">
            <h5 id="lightboxDocTitle" class="text-sm font-bold">Pratinjau Dokumen</h5>
            <button type="button" onclick="closeLightbox()" class="text-slate-400 hover:text-white text-xl font-bold">&times;</button>
        </div>
        <div class="flex items-center justify-center max-h-[75vh] overflow-auto bg-black/40 rounded-2xl p-2">
            <img id="lightboxDocImg" src="" alt="Pratinjau" class="max-h-[70vh] object-contain rounded-xl">
        </div>
        <div class="flex justify-end gap-2 pt-3">
            <a id="lightboxDocDownload" href="#" download="berkas.jpg" class="px-4 py-2 rounded-xl text-xs font-bold bg-brand-700 hover:bg-brand-800 text-white flex items-center gap-1.5">
                <i class="fa-solid fa-download"></i> Unduh Berkas
            </a>
            <button type="button" onclick="closeLightbox()" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-800 hover:bg-slate-700 text-slate-300">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
function showPpdbDetail(d) {
    document.getElementById('detailModalReg').textContent = "NO. REGISTRASI: " + (d.id || "-");
    document.getElementById('detailModalNama').textContent = (d.nama || d.nama_siswa || "-");
    document.getElementById('statusFormRegId').value = d.id || "";
    document.getElementById('statusSelect').value = d.status || "Menunggu";

    document.getElementById('d_nama').textContent = d.nama || d.nama_siswa || "-";
    document.getElementById('d_nisn').textContent = d.nisn || "-";
    document.getElementById('d_jk').textContent = (d.jenis_kelamin === 'P') ? 'Perempuan' : 'Laki-Laki';
    document.getElementById('d_ttl').textContent = (d.tempat_lahir || "-") + ", " + (d.tanggal_lahir || "-");
    document.getElementById('d_sekolah').textContent = d.asal_sekolah || "-";
    document.getElementById('d_alamat_sekolah').textContent = d.alamat_sekolah_asal || "-";
    document.getElementById('d_jalur').textContent = d.jalur || "Reguler";
    document.getElementById('d_email').textContent = d.email || "-";
    document.getElementById('d_wa').textContent = d.no_wa || "-";
    document.getElementById('d_alamat').textContent = d.alamat_lengkap || d.alamat || "-";

    // Ayah
    document.getElementById('d_nama_ayah').textContent = d.nama_ayah || d.nama_ortu || "-";
    document.getElementById('d_ttl_ayah').textContent = (d.tempat_lahir_ayah || "-") + ", " + (d.tanggal_lahir_ayah || "-");
    document.getElementById('d_kerja_ayah').textContent = d.pekerjaan_ayah || "-";
    document.getElementById('d_pend_ayah').textContent = d.pendidikan_ayah || "-";
    document.getElementById('d_hp_ayah').textContent = d.telepon_ayah || "-";

    // Ibu
    document.getElementById('d_nama_ibu').textContent = d.nama_ibu || "-";
    document.getElementById('d_ttl_ibu').textContent = (d.tempat_lahir_ibu || "-") + ", " + (d.tanggal_lahir_ibu || "-");
    document.getElementById('d_kerja_ibu').textContent = d.pekerjaan_ibu || "-";
    document.getElementById('d_pend_ibu').textContent = d.pendidikan_ibu || "-";
    document.getElementById('d_hp_ibu').textContent = d.telepon_ibu || "-";

    // Berkas
    const container = document.getElementById('berkasContainer');
    container.innerHTML = "";

    const docs = [
        { label: "Akta Kelahiran", key: "berkas_akta", icon: "fa-certificate" },
        { label: "KTP Ortu / Wali", key: "berkas_ktp", icon: "fa-id-card" },
        { label: "Pas Foto 3x4", key: "berkas_foto", icon: "fa-image" },
        { label: "Raport Terakhir", key: "berkas_rapor", icon: "fa-book" },
        { label: "Surat Ket. Lulus", key: "berkas_skl", icon: "fa-file-lines" }
    ];

    docs.forEach(doc => {
        const fileData = d[doc.key] || (d.documents ? d.documents[doc.key] : null);
        const card = document.createElement('div');
        card.className = "bg-white p-3 rounded-xl border border-slate-200 text-center flex flex-col justify-between";

        if (fileData && fileData.length > 50) {
            card.innerHTML = `
                <div class="h-20 w-full rounded-lg overflow-hidden bg-slate-100 mb-2 cursor-pointer border border-slate-200 flex items-center justify-center" onclick="zoomDoc('${doc.label}', '${fileData}')">
                    <img src="${fileData}" alt="${doc.label}" class="w-full h-full object-cover">
                </div>
                <span class="text-[10px] font-bold text-slate-800 line-clamp-1">${doc.label}</span>
                <button type="button" onclick="zoomDoc('${doc.label}', '${fileData}')" class="mt-2 text-[10px] font-bold text-brand-700 bg-brand-50 hover:bg-brand-100 py-1 rounded-md">
                    <i class="fa-solid fa-magnifying-glass"></i> Zoom
                </button>
            `;
        } else {
            card.innerHTML = `
                <div class="h-20 w-full rounded-lg bg-slate-100 mb-2 flex items-center justify-center text-slate-400">
                    <i class="fa-solid ${doc.icon} text-2xl"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-500 line-clamp-1">${doc.label}</span>
                <span class="mt-2 text-[9px] text-slate-400 italic">Belum dilampirkan</span>
            `;
        }
        container.appendChild(card);
    });

    // WhatsApp link
    if (d.no_wa) {
        const cleanWa = d.no_wa.replace(/^0/, '62').replace(/[^0-9]/g, '');
        document.getElementById('btnWaChat').href = `https://wa.me/${cleanWa}?text=Halo%20${encodeURIComponent(d.nama || '')},%20kami%20dari%20panitia%20PPDB%20SMP%20Islam%20Al-Kholili`;
        document.getElementById('btnWaChat').classList.remove('hidden');
    } else {
        document.getElementById('btnWaChat').classList.add('hidden');
    }

    document.getElementById('detailPpdbModal').classList.remove('hidden');
}

function closePpdbDetail() {
    document.getElementById('detailPpdbModal').classList.add('hidden');
}

function zoomDoc(title, src) {
    document.getElementById('lightboxDocTitle').textContent = "Pratinjau: " + title;
    document.getElementById('lightboxDocImg').src = src;
    document.getElementById('lightboxDocDownload').href = src;
    document.getElementById('lightboxDocDownload').download = title.replace(/\s+/g, '_') + '.jpg';
    document.getElementById('imageLightbox').classList.remove('hidden');
}

function closeLightbox() {
    document.getElementById('imageLightbox').classList.add('hidden');
}
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
