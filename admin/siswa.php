<?php
$admin_page = "siswa";
$admin_title = "Data Siswa Aktif";
require_once __DIR__ . '/header.php';

$studentsFile = __DIR__ . '/../data/students.json';
$students = [];
if (file_exists($studentsFile)) {
    $students = json_decode(file_get_contents($studentsFile), true) ?: [];
}

$alert = "";
$alertType = "success";

// Handle Add / Edit Student
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (isset($_POST['save_student'])) {
        $id = !empty($_POST['student_id']) ? (int)$_POST['student_id'] : 0;
        $nis = trim($_POST['nis'] ?? '');
        $nisn = trim($_POST['nisn'] ?? '');
        $nama = trim($_POST['nama'] ?? '');
        $jk = trim($_POST['jenis_kelamin'] ?? 'L');
        $kelas = trim($_POST['kelas'] ?? 'VII-A');
        $tempat_lahir = trim($_POST['tempat_lahir'] ?? '');
        $tanggal_lahir = trim($_POST['tanggal_lahir'] ?? '');
        $alamat = trim($_POST['alamat'] ?? '');
        $nama_ortu = trim($_POST['nama_ortu'] ?? '');
        $telepon_ortu = trim($_POST['telepon_ortu'] ?? '');
        $status = trim($_POST['status'] ?? 'Aktif');

        if (!empty($nama) && !empty($nis)) {
            if ($id > 0) {
                // Edit
                foreach ($students as &$st) {
                    if ((int)($st['id'] ?? 0) === $id) {
                        $st['nis'] = $nis;
                        $st['nisn'] = $nisn;
                        $st['nama'] = $nama;
                        $st['jenis_kelamin'] = $jk;
                        $st['kelas'] = $kelas;
                        $st['tempat_lahir'] = $tempat_lahir;
                        $st['tanggal_lahir'] = $tanggal_lahir;
                        $st['alamat'] = $alamat;
                        $st['nama_ortu'] = $nama_ortu;
                        $st['telepon_ortu'] = $telepon_ortu;
                        $st['status'] = $status;
                        break;
                    }
                }
                $alert = "Data siswa berhasil diperbarui!";
            } else {
                // Add new
                $maxId = 0;
                foreach ($students as $st) {
                    if ((int)($st['id'] ?? 0) > $maxId) $maxId = (int)$st['id'];
                }
                $newStudent = [
                    'id' => $maxId + 1,
                    'nis' => $nis,
                    'nisn' => $nisn,
                    'nama' => $nama,
                    'jenis_kelamin' => $jk,
                    'kelas' => $kelas,
                    'tempat_lahir' => $tempat_lahir,
                    'tanggal_lahir' => $tanggal_lahir,
                    'alamat' => $alamat,
                    'nama_ortu' => $nama_ortu,
                    'telepon_ortu' => $telepon_ortu,
                    'status' => $status
                ];
                $students[] = $newStudent;
                $alert = "Siswa baru berhasil ditambahkan!";
            }
            file_put_contents($studentsFile, json_encode($students, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        } else {
            $alert = "Mohon lengkapi NIS dan Nama Siswa!";
            $alertType = "danger";
        }
    } elseif (isset($_POST['delete_student'])) {
        $delId = (int)($_POST['student_id'] ?? 0);
        $updated = [];
        foreach ($students as $st) {
            if ((int)($st['id'] ?? 0) !== $delId) {
                $updated[] = $st;
            }
        }
        $students = $updated;
        file_put_contents($studentsFile, json_encode($students, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        $alert = "Data siswa telah dihapus.";
    }
}

// Stats
$totalSiswa = count($students);
$countL = count(array_filter($students, fn($s) => ($s['jenis_kelamin'] ?? '') === 'L'));
$countP = count(array_filter($students, fn($s) => ($s['jenis_kelamin'] ?? '') === 'P'));
$countAktif = count(array_filter($students, fn($s) => ($s['status'] ?? '') === 'Aktif'));
?>

<div class="space-y-6 max-w-7xl mx-auto">

    <?php if (!empty($alert)): ?>
    <div class="p-4 rounded-2xl <?= $alertType === 'danger' ? 'bg-red-50 border border-red-200 text-red-800' : 'bg-emerald-50 border border-emerald-200 text-emerald-800' ?> flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i class="fa-solid <?= $alertType === 'danger' ? 'fa-circle-exclamation text-red-600' : 'fa-circle-check text-emerald-600' ?>"></i>
            <span class="text-sm font-semibold"><?= htmlspecialchars($alert) ?></span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-slate-400 hover:text-slate-700">&times;</button>
    </div>
    <?php endif; ?>

    <!-- Summary Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-700 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fa-solid fa-users"></i>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase">Total Siswa</p>
                <h3 class="text-2xl font-heading font-extrabold text-slate-800"><?= $totalSiswa ?></h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fa-solid fa-mars"></i>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase">Laki-Laki</p>
                <h3 class="text-2xl font-heading font-extrabold text-slate-800"><?= $countL ?></h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fa-solid fa-venus"></i>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase">Perempuan</p>
                <h3 class="text-2xl font-heading font-extrabold text-slate-800"><?= $countP ?></h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl flex-shrink-0">
                <i class="fa-solid fa-user-check"></i>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-semibold uppercase">Status Aktif</p>
                <h3 class="text-2xl font-heading font-extrabold text-slate-800"><?= $countAktif ?></h3>
            </div>
        </div>
    </div>

    <!-- Main Table Container -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5 mb-6">
            <div>
                <h2 class="text-xl font-heading font-extrabold text-slate-900">Daftar Data Induk Siswa</h2>
                <p class="text-xs text-slate-500 mt-1">Data siswa terdaftar di SMP Islam Al-Kholili Kota Probolinggo</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <input type="text" id="searchSiswaInput" placeholder="Cari NIS / Nama Siswa..." class="px-3.5 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-500 w-48 sm:w-64">
                <select id="filterKelasSelect" class="px-3 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-500 font-semibold">
                    <option value="">Semua Kelas</option>
                    <option value="VII-A">Kelas VII-A</option>
                    <option value="VII-B">Kelas VII-B</option>
                    <option value="VIII-A">Kelas VIII-A</option>
                    <option value="VIII-B">Kelas VIII-B</option>
                    <option value="IX-A">Kelas IX-A</option>
                    <option value="IX-B">Kelas IX-B</option>
                </select>
                <button type="button" onclick="openStudentModal()" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-brand-700 hover:bg-brand-800 shadow transition flex items-center gap-1.5">
                    <i class="fa-solid fa-plus"></i> Tambah Siswa
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs" id="studentsTable">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 font-bold uppercase tracking-wider border-b border-slate-200">
                        <th class="p-3.5 rounded-l-xl">No</th>
                        <th class="p-3.5">NIS / NISN</th>
                        <th class="p-3.5">Nama Lengkap</th>
                        <th class="p-3.5">JK</th>
                        <th class="p-3.5">Kelas</th>
                        <th class="p-3.5">TTL</th>
                        <th class="p-3.5">Orang Tua / Wali</th>
                        <th class="p-3.5">No. HP</th>
                        <th class="p-3.5">Status</th>
                        <th class="p-3.5 text-center rounded-r-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    <?php if (empty($students)): ?>
                    <tr>
                        <td colspan="10" class="py-12 text-center text-slate-400">
                            <i class="fa-solid fa-users text-4xl mb-2 block"></i>
                            Belum ada data siswa. Silakan klik tombol "Tambah Siswa".
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($students as $idx => $st): ?>
                    <tr class="hover:bg-slate-50 transition student-row" data-nama="<?= strtolower(htmlspecialchars($st['nama'] ?? '')) ?>" data-nis="<?= htmlspecialchars($st['nis'] ?? '') ?>" data-kelas="<?= htmlspecialchars($st['kelas'] ?? '') ?>">
                        <td class="p-3.5 font-bold text-slate-400"><?= $idx + 1 ?></td>
                        <td class="p-3.5">
                            <span class="font-bold text-slate-900"><?= htmlspecialchars($st['nis'] ?? '-') ?></span>
                            <span class="block text-[11px] text-slate-400">NISN: <?= htmlspecialchars($st['nisn'] ?? '-') ?></span>
                        </td>
                        <td class="p-3.5 font-bold text-slate-900"><?= htmlspecialchars($st['nama'] ?? '-') ?></td>
                        <td class="p-3.5">
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold <?= ($st['jenis_kelamin'] ?? '') === 'L' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' ?>">
                                <?= ($st['jenis_kelamin'] ?? '') === 'L' ? 'L' : 'P' ?>
                            </span>
                        </td>
                        <td class="p-3.5">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-brand-50 text-brand-700 border border-brand-200">
                                <?= htmlspecialchars($st['kelas'] ?? '-') ?>
                            </span>
                        </td>
                        <td class="p-3.5 text-slate-500">
                            <?= htmlspecialchars($st['tempat_lahir'] ?? '-') ?>, <?= htmlspecialchars($st['tanggal_lahir'] ?? '-') ?>
                        </td>
                        <td class="p-3.5"><?= htmlspecialchars($st['nama_ortu'] ?? '-') ?></td>
                        <td class="p-3.5">
                            <?php if (!empty($st['telepon_ortu'])): ?>
                            <a href="https://wa.me/<?= preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $st['telepon_ortu'])) ?>" target="_blank" class="text-brand-700 font-semibold hover:underline flex items-center gap-1">
                                <i class="fa-brands fa-whatsapp text-emerald-600"></i> <?= htmlspecialchars($st['telepon_ortu']) ?>
                            </a>
                            <?php else: ?>
                            -
                            <?php endif; ?>
                        </td>
                        <td class="p-3.5">
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                                <?= htmlspecialchars($st['status'] ?? 'Aktif') ?>
                            </span>
                        </td>
                        <td class="p-3.5 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <button type="button" onclick='editStudent(<?= json_encode($st) ?>)' class="p-2 rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 transition" title="Edit Siswa">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </button>
                                <form method="POST" onsubmit="return confirm('Hapus siswa <?= htmlspecialchars(addslashes($st['nama'] ?? '')) ?>?')">
                                    <input type="hidden" name="delete_student" value="1">
                                    <input type="hidden" name="student_id" value="<?= (int)($st['id'] ?? 0) ?>">
                                    <button type="submit" class="p-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition" title="Hapus Siswa">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

</div>

<!-- Modal Form Tambah / Edit Siswa -->
<div id="studentModal" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl border border-slate-100 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-5">
            <h3 id="modalTitle" class="text-lg font-heading font-extrabold text-slate-900">Tambah Data Siswa</h3>
            <button type="button" onclick="closeStudentModal()" class="text-slate-400 hover:text-slate-700 text-xl font-bold">&times;</button>
        </div>

        <form method="POST" class="space-y-4">
            <input type="hidden" name="save_student" value="1">
            <input type="hidden" name="student_id" id="form_student_id" value="0">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">NIS (Nomor Induk Sekolah) *</label>
                    <input type="text" name="nis" id="form_nis" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none" placeholder="Contoh: 24001">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">NISN (10 Digit)</label>
                    <input type="text" name="nisn" id="form_nisn" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none" placeholder="Contoh: 0091122334">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap Siswa *</label>
                    <input type="text" name="nama" id="form_nama" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none" placeholder="Nama lengkap siswa">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="form_jk" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none font-semibold">
                        <option value="L">Laki-Laki (L)</option>
                        <option value="P">Perempuan (P)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kelas</label>
                    <select name="kelas" id="form_kelas" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none font-semibold">
                        <option value="VII-A">VII-A</option>
                        <option value="VII-B">VII-B</option>
                        <option value="VIII-A">VIII-A</option>
                        <option value="VIII-B">VIII-B</option>
                        <option value="IX-A">IX-A</option>
                        <option value="IX-B">IX-B</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Status Keaktifan</label>
                    <select name="status" id="form_status" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none font-semibold">
                        <option value="Aktif">Aktif</option>
                        <option value="Lulus">Lulus</option>
                        <option value="Pindah">Pindah</option>
                        <option value="Non-Aktif">Non-Aktif</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" id="form_tempat_lahir" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none" placeholder="Kota lahir">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" id="form_tanggal_lahir" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Alamat Tinggal Lengkap</label>
                <textarea name="alamat" id="form_alamat" rows="2" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none" placeholder="Alamat lengkap jalan, RT/RW, kelurahan"></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Orang Tua / Wali</label>
                    <input type="text" name="nama_ortu" id="form_nama_ortu" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none" placeholder="Nama ayah / ibu / wali">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">No. Telp / WhatsApp Ortu</label>
                    <input type="text" name="telepon_ortu" id="form_telepon_ortu" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500 focus:outline-none" placeholder="Contoh: 085234567801">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeStudentModal()" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-brand-700 hover:bg-brand-800 shadow transition flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Data Siswa
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openStudentModal() {
    document.getElementById('modalTitle').textContent = "Tambah Data Siswa";
    document.getElementById('form_student_id').value = "0";
    document.getElementById('form_nis').value = "";
    document.getElementById('form_nisn').value = "";
    document.getElementById('form_nama').value = "";
    document.getElementById('form_jk').value = "L";
    document.getElementById('form_kelas').value = "VII-A";
    document.getElementById('form_status').value = "Aktif";
    document.getElementById('form_tempat_lahir').value = "";
    document.getElementById('form_tanggal_lahir').value = "";
    document.getElementById('form_alamat').value = "";
    document.getElementById('form_nama_ortu').value = "";
    document.getElementById('form_telepon_ortu').value = "";
    document.getElementById('studentModal').classList.remove('hidden');
}

function closeStudentModal() {
    document.getElementById('studentModal').classList.add('hidden');
}

function editStudent(st) {
    document.getElementById('modalTitle').textContent = "Edit Data Siswa - " + st.nama;
    document.getElementById('form_student_id').value = st.id || "0";
    document.getElementById('form_nis').value = st.nis || "";
    document.getElementById('form_nisn').value = st.nisn || "";
    document.getElementById('form_nama').value = st.nama || "";
    document.getElementById('form_jk').value = st.jenis_kelamin || "L";
    document.getElementById('form_kelas').value = st.kelas || "VII-A";
    document.getElementById('form_status').value = st.status || "Aktif";
    document.getElementById('form_tempat_lahir').value = st.tempat_lahir || "";
    document.getElementById('form_tanggal_lahir').value = st.tanggal_lahir || "";
    document.getElementById('form_alamat').value = st.alamat || "";
    document.getElementById('form_nama_ortu').value = st.nama_ortu || "";
    document.getElementById('form_telepon_ortu').value = st.telepon_ortu || "";
    document.getElementById('studentModal').classList.remove('hidden');
}

// Search and filter
const searchInput = document.getElementById('searchSiswaInput');
const filterKelas = document.getElementById('filterKelasSelect');
const rows = document.querySelectorAll('.student-row');

function filterTable() {
    const q = (searchInput?.value || '').toLowerCase();
    const k = filterKelas?.value || '';
    rows.forEach(r => {
        const nama = r.getAttribute('data-nama') || '';
        const nis = r.getAttribute('data-nis') || '';
        const kelas = r.getAttribute('data-kelas') || '';
        const matchQ = !q || nama.includes(q) || nis.includes(q);
        const matchK = !k || kelas === k;
        r.style.display = (matchQ && matchK) ? '' : 'none';
    });
}

searchInput?.addEventListener('input', filterTable);
filterKelas?.addEventListener('change', filterTable);
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
