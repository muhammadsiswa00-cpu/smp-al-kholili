<?php
$admin_page = "absen_siswa";
$admin_title = "Presensi & Absen Siswa";
require_once __DIR__ . '/header.php';

$studentsFile = __DIR__ . '/../data/students.json';
$attendanceFile = __DIR__ . '/../data/attendance_students.json';

$students = [];
if (file_exists($studentsFile)) {
    $students = json_decode(file_get_contents($studentsFile), true) ?: [];
}

$attendanceList = [];
if (file_exists($attendanceFile)) {
    $attendanceList = json_decode(file_get_contents($attendanceFile), true) ?: [];
}

$alert = "";
$selectedDate = $_GET['tanggal'] ?? date('Y-m-d');
$selectedKelas = $_GET['kelas'] ?? 'VII-A';

// Handle Save Attendance
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['save_attendance'])) {
    $postDate = $_POST['tanggal'] ?? date('Y-m-d');
    $postKelas = $_POST['kelas'] ?? 'VII-A';
    $statusMap = $_POST['status'] ?? []; // student_id => 'H'|'S'|'I'|'A'
    $ketMap = $_POST['keterangan'] ?? [];

    $records = [];
    $hadirCount = 0;
    $sakitCount = 0;
    $izinCount = 0;
    $alpaCount = 0;

    foreach ($statusMap as $sid => $st) {
        $stVal = in_array($st, ['H', 'S', 'I', 'A']) ? $st : 'H';
        if ($stVal === 'H') $hadirCount++;
        elseif ($stVal === 'S') $sakitCount++;
        elseif ($stVal === 'I') $izinCount++;
        elseif ($stVal === 'A') $alpaCount++;

        // Find student name
        $sName = "Siswa #" . $sid;
        foreach ($students as $s) {
            if ((int)($s['id'] ?? 0) === (int)$sid) {
                $sName = $s['nama'] ?? $sName;
                break;
            }
        }

        $records[] = [
            'student_id' => (int)$sid,
            'nama' => $sName,
            'status' => $stVal,
            'keterangan' => trim($ketMap[$sid] ?? '')
        ];
    }

    $sessionId = "ATT-S-" . str_replace('-', '', $postDate) . "-" . preg_replace('/[^A-Za-z0-9]/', '', $postKelas);

    // Replace if exists
    $found = false;
    foreach ($attendanceList as &$att) {
        if (($att['id'] ?? '') === $sessionId || (($att['tanggal'] ?? '') === $postDate && ($att['kelas'] ?? '') === $postKelas)) {
            $att['total_siswa'] = count($records);
            $att['hadir'] = $hadirCount;
            $att['sakit'] = $sakitCount;
            $att['izin'] = $izinCount;
            $att['alpa'] = $alpaCount;
            $att['records'] = $records;
            $att['updated_at'] = date('Y-m-d H:i:s');
            $found = true;
            break;
        }
    }

    if (!$found) {
        $attendanceList[] = [
            'id' => $sessionId,
            'tanggal' => $postDate,
            'kelas' => $postKelas,
            'total_siswa' => count($records),
            'hadir' => $hadirCount,
            'sakit' => $sakitCount,
            'izin' => $izinCount,
            'alpa' => $alpaCount,
            'records' => $records,
            'created_at' => date('Y-m-d H:i:s')
        ];
    }

    file_put_contents($attendanceFile, json_encode($attendanceList, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    $alert = "Data absensi kelas " . htmlspecialchars($postKelas) . " tanggal " . htmlspecialchars($postDate) . " berhasil disimpan!";
    $selectedDate = $postDate;
    $selectedKelas = $postKelas;
}

// Filter students for current class
$activeClassStudents = array_filter($students, fn($s) => ($s['kelas'] ?? '') === $selectedKelas && ($s['status'] ?? 'Aktif') === 'Aktif');

// Find existing record for this date & class
$existingSession = null;
foreach ($attendanceList as $att) {
    if (($att['tanggal'] ?? '') === $selectedDate && ($att['kelas'] ?? '') === $selectedKelas) {
        $existingSession = $att;
        break;
    }
}
$existingStatusMap = [];
$existingKetMap = [];
if ($existingSession && !empty($existingSession['records'])) {
    foreach ($existingSession['records'] as $r) {
        $existingStatusMap[$r['student_id']] = $r['status'] ?? 'H';
        $existingKetMap[$r['student_id']] = $r['keterangan'] ?? '';
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

    <!-- Header Filter Controls -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-5 mb-6">
            <div>
                <h2 class="text-xl font-heading font-extrabold text-slate-900">Input Absensi Harian Siswa</h2>
                <p class="text-xs text-slate-500 mt-1">Pilih tanggal dan kelas untuk mencatat atau memperbarui kehadiran</p>
            </div>
            
            <form method="GET" class="flex flex-wrap items-center gap-3">
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-0.5">Pilih Tanggal</label>
                    <input type="date" name="tanggal" value="<?= htmlspecialchars($selectedDate) ?>" onchange="this.form.submit()" class="px-3 py-2 text-xs rounded-xl border border-slate-300 font-semibold focus:ring-2 focus:ring-brand-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-0.5">Pilih Kelas</label>
                    <select name="kelas" onchange="this.form.submit()" class="px-3 py-2 text-xs rounded-xl border border-slate-300 font-semibold focus:ring-2 focus:ring-brand-500 focus:outline-none">
                        <?php foreach (['VII-A', 'VII-B', 'VIII-A', 'VIII-B', 'IX-A', 'IX-B'] as $k): ?>
                        <option value="<?= $k ?>" <?= $selectedKelas === $k ? 'selected' : '' ?>><?= $k ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="pt-4">
                    <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition">
                        <i class="fa-solid fa-arrows-rotate mr-1"></i> Muat
                    </button>
                </div>
            </form>
        </div>

        <?php if ($existingSession): ?>
        <div class="grid grid-cols-4 gap-3 mb-6 p-4 rounded-2xl bg-slate-50 border border-slate-200 text-center">
            <div>
                <span class="block text-[11px] text-emerald-600 font-bold uppercase">Hadir</span>
                <span class="text-xl font-heading font-extrabold text-emerald-700"><?= $existingSession['hadir'] ?? 0 ?></span>
            </div>
            <div>
                <span class="block text-[11px] text-blue-600 font-bold uppercase">Sakit</span>
                <span class="text-xl font-heading font-extrabold text-blue-700"><?= $existingSession['sakit'] ?? 0 ?></span>
            </div>
            <div>
                <span class="block text-[11px] text-amber-600 font-bold uppercase">Izin</span>
                <span class="text-xl font-heading font-extrabold text-amber-700"><?= $existingSession['izin'] ?? 0 ?></span>
            </div>
            <div>
                <span class="block text-[11px] text-red-600 font-bold uppercase">Alpa</span>
                <span class="text-xl font-heading font-extrabold text-red-700"><?= $existingSession['alpa'] ?? 0 ?></span>
            </div>
        </div>
        <?php endif; ?>

        <!-- Attendance Form -->
        <form method="POST">
            <input type="hidden" name="save_attendance" value="1">
            <input type="hidden" name="tanggal" value="<?= htmlspecialchars($selectedDate) ?>">
            <input type="hidden" name="kelas" value="<?= htmlspecialchars($selectedKelas) ?>">

            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold text-slate-600">
                    Daftar Siswa Kelas <?= htmlspecialchars($selectedKelas) ?> (<?= count($activeClassStudents) ?> Siswa)
                </span>
                <button type="button" onclick="setAllHadir()" class="px-3.5 py-1.5 rounded-xl text-xs font-bold text-brand-700 bg-brand-50 hover:bg-brand-100 border border-brand-200 transition flex items-center gap-1.5">
                    <i class="fa-solid fa-check-double"></i> Tandai Semua Hadir
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-bold uppercase tracking-wider border-b border-slate-200">
                            <th class="p-3.5 rounded-l-xl w-12 text-center">No</th>
                            <th class="p-3.5">NIS</th>
                            <th class="p-3.5">Nama Siswa</th>
                            <th class="p-3.5">JK</th>
                            <th class="p-3.5 text-center">Kehadiran (H / S / I / A)</th>
                            <th class="p-3.5 rounded-r-xl">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        <?php if (empty($activeClassStudents)): ?>
                        <tr>
                            <td colspan="6" class="py-10 text-center text-slate-400">
                                Tidak ada data siswa aktif di kelas <?= htmlspecialchars($selectedKelas) ?>.
                                Silakan tambahkan siswa di menu <a href="siswa.php" class="text-brand-700 font-bold underline">Data Siswa</a>.
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php $no = 1; foreach ($activeClassStudents as $st): 
                            $sid = $st['id'];
                            $curStatus = $existingStatusMap[$sid] ?? 'H';
                            $curKet = $existingKetMap[$sid] ?? '';
                        ?>
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-3.5 text-center text-slate-400 font-bold"><?= $no++ ?></td>
                            <td class="p-3.5 font-bold text-slate-900"><?= htmlspecialchars($st['nis'] ?? '-') ?></td>
                            <td class="p-3.5 font-bold text-slate-900"><?= htmlspecialchars($st['nama'] ?? '-') ?></td>
                            <td class="p-3.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold <?= ($st['jenis_kelamin'] ?? '') === 'L' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' ?>">
                                    <?= ($st['jenis_kelamin'] ?? '') === 'L' ? 'L' : 'P' ?>
                                </span>
                            </td>
                            <td class="p-3.5 text-center">
                                <div class="inline-flex items-center gap-1.5 p-1 bg-slate-100 rounded-xl">
                                    <label class="cursor-pointer px-2.5 py-1 rounded-lg text-xs font-bold transition flex items-center gap-1 has-[:checked]:bg-emerald-600 has-[:checked]:text-white text-slate-600 hover:text-slate-900">
                                        <input type="radio" name="status[<?= $sid ?>]" value="H" class="hidden status-radio-h" <?= $curStatus === 'H' ? 'checked' : '' ?>>
                                        <span>H</span>
                                    </label>
                                    <label class="cursor-pointer px-2.5 py-1 rounded-lg text-xs font-bold transition flex items-center gap-1 has-[:checked]:bg-blue-600 has-[:checked]:text-white text-slate-600 hover:text-slate-900">
                                        <input type="radio" name="status[<?= $sid ?>]" value="S" class="hidden" <?= $curStatus === 'S' ? 'checked' : '' ?>>
                                        <span>S</span>
                                    </label>
                                    <label class="cursor-pointer px-2.5 py-1 rounded-lg text-xs font-bold transition flex items-center gap-1 has-[:checked]:bg-amber-600 has-[:checked]:text-white text-slate-600 hover:text-slate-900">
                                        <input type="radio" name="status[<?= $sid ?>]" value="I" class="hidden" <?= $curStatus === 'I' ? 'checked' : '' ?>>
                                        <span>I</span>
                                    </label>
                                    <label class="cursor-pointer px-2.5 py-1 rounded-lg text-xs font-bold transition flex items-center gap-1 has-[:checked]:bg-red-600 has-[:checked]:text-white text-slate-600 hover:text-slate-900">
                                        <input type="radio" name="status[<?= $sid ?>]" value="A" class="hidden" <?= $curStatus === 'A' ? 'checked' : '' ?>>
                                        <span>A</span>
                                    </label>
                                </div>
                            </td>
                            <td class="p-3.5">
                                <input type="text" name="keterangan[<?= $sid ?>]" value="<?= htmlspecialchars($curKet) ?>" placeholder="Catatan (opsional)..." class="w-full px-3 py-1.5 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-brand-500 focus:outline-none">
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if (!empty($activeClassStudents)): ?>
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100 mt-6">
                <button type="submit" class="px-6 py-3 rounded-xl font-bold text-white bg-brand-700 hover:bg-brand-800 shadow transition flex items-center gap-2 text-sm">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Rekap Absensi
                </button>
            </div>
            <?php endif; ?>
        </form>

    </div>

    <!-- Riwayat Rekap Absensi Terakhir -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
        <h3 class="text-base font-heading font-extrabold text-slate-900 mb-4">Riwayat Rekap Presensi Siswa</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 font-bold uppercase tracking-wider border-b border-slate-200">
                        <th class="p-3 rounded-l-xl">Tanggal</th>
                        <th class="p-3">Kelas</th>
                        <th class="p-3">Total Siswa</th>
                        <th class="p-3 text-emerald-700">Hadir</th>
                        <th class="p-3 text-blue-700">Sakit</th>
                        <th class="p-3 text-amber-700">Izin</th>
                        <th class="p-3 text-red-700">Alpa</th>
                        <th class="p-3 text-right rounded-r-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    <?php if (empty($attendanceList)): ?>
                    <tr>
                        <td colspan="8" class="py-6 text-center text-slate-400">Belum ada rekapan absensi siswa tersimpan.</td>
                    </tr>
                    <?php else: ?>
                    <?php 
                    $reversed = array_reverse($attendanceList);
                    foreach (array_slice($reversed, 0, 10) as $att): 
                    ?>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-3 font-bold text-slate-800"><?= htmlspecialchars($att['tanggal'] ?? '-') ?></td>
                        <td class="p-3"><span class="px-2 py-0.5 rounded bg-brand-50 text-brand-700 font-bold"><?= htmlspecialchars($att['kelas'] ?? '-') ?></span></td>
                        <td class="p-3 font-bold"><?= $att['total_siswa'] ?? 0 ?></td>
                        <td class="p-3 text-emerald-600 font-bold"><?= $att['hadir'] ?? 0 ?></td>
                        <td class="p-3 text-blue-600 font-bold"><?= $att['sakit'] ?? 0 ?></td>
                        <td class="p-3 text-amber-600 font-bold"><?= $att['izin'] ?? 0 ?></td>
                        <td class="p-3 text-red-600 font-bold"><?= $att['alpa'] ?? 0 ?></td>
                        <td class="p-3 text-right">
                            <a href="?tanggal=<?= urlencode($att['tanggal'] ?? '') ?>&kelas=<?= urlencode($att['kelas'] ?? '') ?>" class="px-3 py-1.5 rounded-lg bg-brand-50 text-brand-700 hover:bg-brand-100 font-bold text-xs inline-flex items-center gap-1">
                                <i class="fa-solid fa-pen-to-square"></i> Buka / Edit
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
function setAllHadir() {
    document.querySelectorAll('.status-radio-h').forEach(radio => {
        radio.checked = true;
    });
}
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
