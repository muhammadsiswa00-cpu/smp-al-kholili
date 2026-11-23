<?php
$admin_page = "guru";
$admin_title = "Kelola Data & Foto Guru";
require_once __DIR__ . '/header.php';

$teachersFile = __DIR__ . '/../data/teachers.json';
$teachers = [];
if (file_exists($teachersFile)) {
    $teachers = json_decode(file_get_contents($teachersFile), true) ?: [];
}

$success = "";
$error = "";

// 1. Tambah / Upload Foto Guru Baru
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['save_teacher'])) {
    $nama = trim($_POST['nama'] ?? '');
    $jabatan = trim($_POST['jabatan'] ?? '');
    $mapel = trim($_POST['mapel'] ?? '');
    $pendidikan = trim($_POST['pendidikan'] ?? '');
    $guruId = trim($_POST['guru_id'] ?? '');

    if (empty($nama) || empty($jabatan)) {
        $error = "Nama dan Jabatan guru wajib diisi!";
    } else {
        $photoPath = "";

        // Check if file uploaded
        if (isset($_FILES['teacher_photo']) && $_FILES['teacher_photo']['error'] === UPLOAD_ERR_OK) {
            $fileTmp = $_FILES['teacher_photo']['tmp_name'];
            $fileName = $_FILES['teacher_photo']['name'];
            $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];

            if (in_array($ext, $allowed)) {
                $newPhotoName = "guru_" . time() . "_" . rand(100, 999) . "." . $ext;
                $dest = __DIR__ . '/../assets/uploads/teachers/' . $newPhotoName;
                if (move_uploaded_file($fileTmp, $dest)) {
                    $photoPath = "assets/uploads/teachers/" . $newPhotoName;
                }
            }
        }

        if (!empty($guruId)) {
            // Update existing teacher
            foreach ($teachers as &$t) {
                if ($t['id'] === $guruId) {
                    $t['nama'] = $nama;
                    $t['jabatan'] = $jabatan;
                    $t['mapel'] = $mapel;
                    $t['pendidikan'] = $pendidikan;
                    if (!empty($photoPath)) {
                        $t['foto'] = $photoPath;
                    }
                    break;
                }
            }
            $success = "Data dan foto guru berhasil diperbarui!";
        } else {
            // Add new teacher
            $colors = [
                'from-brand-700 to-emerald-600',
                'from-emerald-600 to-teal-500',
                'from-blue-600 to-indigo-500',
                'from-amber-600 to-yellow-500',
                'from-purple-600 to-indigo-500',
                'from-teal-600 to-emerald-500'
            ];
            $newTeacher = [
                "id" => "guru-" . time(),
                "nama" => $nama,
                "jabatan" => $jabatan,
                "mapel" => $mapel,
                "pendidikan" => $pendidikan,
                "foto" => !empty($photoPath) ? $photoPath : "assets/img/teachers/male-1.svg",
                "color" => $colors[array_rand($colors)]
            ];
            $teachers[] = $newTeacher;
            $success = "Guru baru berhasil ditambahkan beserta fotonya!";
        }

        file_put_contents($teachersFile, json_encode($teachers, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}

// 2. Hapus Guru
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['delete_teacher'])) {
    $delId = $_POST['teacher_id'] ?? '';
    $updated = [];
    foreach ($teachers as $t) {
        if ($t['id'] !== $delId) {
            $updated[] = $t;
        }
    }
    $teachers = $updated;
    file_put_contents($teachersFile, json_encode($teachers, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    $success = "Data guru berhasil dihapus.";
}
?>

<div class="space-y-8 max-w-6xl mx-auto">
    
    <?php if (!empty($success)): ?>
    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-300 text-brand-900 flex items-center gap-3 shadow-sm">
        <i class="fa-solid fa-circle-check text-2xl text-emerald-600 flex-shrink-0"></i>
        <div class="flex-1 text-sm font-semibold"><?= htmlspecialchars($success) ?></div>
        <a href="../guru.php" target="_blank" class="px-3 py-1.5 rounded-lg bg-brand-700 text-white text-xs font-bold hover:bg-brand-800 transition">
            Lihat Halaman Guru &rarr;
        </a>
    </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
    <div class="p-4 rounded-2xl bg-red-50 border border-red-300 text-red-800 flex items-center gap-3 shadow-sm">
        <i class="fa-solid fa-triangle-exclamation text-2xl text-red-600 flex-shrink-0"></i>
        <div class="text-sm font-semibold"><?= htmlspecialchars($error) ?></div>
    </div>
    <?php endif; ?>

    <!-- FORM TAMBAH / UPLOAD FOTO GURU -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
        <div class="border-b border-slate-100 pb-4">
            <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-emerald-100 text-brand-800">
                Pendidik & Staf
            </span>
            <h2 class="text-xl font-heading font-extrabold text-slate-900 mt-1 flex items-center gap-2">
                <i class="fa-solid fa-user-plus text-brand-700"></i> Tambah / Perbarui Guru & Upload Foto
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">
                Tambahkan profil dewan guru dan unggah foto formal mereka agar tampil di website.
            </p>
        </div>

        <form action="guru.php" method="POST" enctype="multipart/form-data" class="space-y-5">
            <input type="hidden" id="form_guru_id" name="guru_id" value="">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="nama">
                        Nama Lengkap & Gelar Guru <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="form_nama" name="nama" required placeholder="Contoh: Ust. M. Khusnul Yakin, S.Pd.I." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-600 text-sm font-semibold">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="jabatan">
                        Jabatan / Posisi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="form_jabatan" name="jabatan" required placeholder="Contoh: Waka Kurikulum / Guru Mata Pelajaran" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-600 text-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="mapel">
                        Mata Pelajaran yang Diampu
                    </label>
                    <input type="text" id="form_mapel" name="mapel" placeholder="Contoh: Pendidikan Agama Islam & Fiqih" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="pendidikan">
                        Pendidikan Terakhir
                    </label>
                    <input type="text" id="form_pendidikan" name="pendidikan" placeholder="Contoh: S1 Tarbiyah & Studi Islam" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5" for="teacher_photo">
                        Upload Foto Guru (JPG/PNG/WEBP)
                    </label>
                    <input type="file" id="teacher_photo" name="teacher_photo" accept="image/*" class="w-full text-xs text-slate-600 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-brand-700 file:text-white hover:file:bg-brand-800 cursor-pointer">
                </div>
            </div>

            <div class="pt-2 flex items-center gap-3">
                <button type="submit" name="save_teacher" value="1" class="px-6 py-3 rounded-xl font-bold text-white bg-gradient-to-r from-brand-700 to-emerald-600 hover:from-brand-800 transition shadow-md flex items-center gap-2 text-sm">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Simpan Data & Foto Guru</span>
                </button>
                <button type="button" onclick="resetForm()" class="px-4 py-3 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition">
                    Reset Form
                </button>
            </div>
        </form>
    </div>

    <!-- DAFTAR GURU & FOTO -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <h3 class="text-lg font-heading font-extrabold text-slate-900">
                    Daftar Guru & Foto Saat Ini (<?= count($teachers) ?>)
                </h3>
                <p class="text-xs text-slate-500">Foto profil guru yang ditampilkan pada website publik.</p>
            </div>
            <a href="../guru.php" target="_blank" class="text-xs font-bold text-brand-700 hover:underline flex items-center gap-1">
                <span>Lihat Halaman Guru</span> <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($teachers as $t): 
                $fotoG = !empty($t['foto']) ? $t['foto'] : 'assets/img/teachers/male-1.svg';
                if (($t['jabatan'] ?? '') === 'Kepala Sekolah' && !empty($siteSettings['kepala_sekolah_foto'])) {
                    $fotoG = $siteSettings['kepala_sekolah_foto'];
                }
                $namaG = ($t['jabatan'] ?? '') === 'Kepala Sekolah' && !empty($siteSettings['nama_kepala_sekolah']) ? $siteSettings['nama_kepala_sekolah'] : ($t['nama'] ?? 'Guru');
            ?>
            <div class="bg-slate-50 rounded-2xl border border-slate-200 p-5 flex items-center gap-4 hover:shadow-md transition">
                <div class="w-20 h-20 rounded-2xl overflow-hidden bg-white shadow-md border-2 border-emerald-200 flex-shrink-0 flex items-center justify-center">
                    <img src="../<?= htmlspecialchars($fotoG) ?>" alt="<?= htmlspecialchars($namaG) ?>" class="w-full h-full object-cover">
                </div>
                <div class="flex-1 min-w-0">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded-full inline-block mb-1">
                        <?= htmlspecialchars($t['jabatan']) ?>
                    </span>
                    <h4 class="font-heading font-extrabold text-sm text-slate-900 truncate" title="<?= htmlspecialchars($namaG) ?>">
                        <?= htmlspecialchars($namaG) ?>
                    </h4>
                    <p class="text-xs font-semibold text-gold-600 truncate">
                        <?= htmlspecialchars($t['mapel']) ?>
                    </p>
                    <p class="text-[11px] text-slate-400 mt-1 truncate">
                        <?= htmlspecialchars($t['pendidikan']) ?>
                    </p>

                    <div class="mt-3 flex items-center gap-3 border-t border-slate-200/60 pt-2">
                        <button type="button" onclick='editTeacher(<?= json_encode($t) ?>)' class="text-[11px] font-bold text-brand-700 hover:underline">
                            <i class="fa-solid fa-pen-to-square mr-1"></i> Ganti Foto / Edit
                        </button>
                        <form action="guru.php" method="POST" onsubmit="return confirm('Hapus guru ini?')">
                            <input type="hidden" name="teacher_id" value="<?= htmlspecialchars($t['id']) ?>">
                            <button type="submit" name="delete_teacher" value="1" class="text-[11px] font-bold text-red-600 hover:underline">
                                <i class="fa-solid fa-trash-can mr-1"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>

<script>
function editTeacher(t) {
    document.getElementById('form_guru_id').value = t.id || '';
    document.getElementById('form_nama').value = t.nama || '';
    document.getElementById('form_jabatan').value = t.jabatan || '';
    document.getElementById('form_mapel').value = t.mapel || '';
    document.getElementById('form_pendidikan').value = t.pendidikan || '';
    window.scrollTo({ top: 150, behavior: 'smooth' });
}

function resetForm() {
    document.getElementById('form_guru_id').value = '';
    document.getElementById('form_nama').value = '';
    document.getElementById('form_jabatan').value = '';
    document.getElementById('form_mapel').value = '';
    document.getElementById('form_pendidikan').value = '';
}
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
