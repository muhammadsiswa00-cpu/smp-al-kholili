<?php
$admin_page = "absen_guru";
$admin_title = "Presensi & Absen Guru";
require_once __DIR__ . '/header.php';

$teachersFile = __DIR__ . '/../data/teachers.json';
$attendanceFile = __DIR__ . '/../data/attendance_teachers.json';

$teachers = [];
if (file_exists($teachersFile)) {
    $teachers = json_decode(file_get_contents($teachersFile), true) ?: [];
}

$attendanceList = [];
if (file_exists($attendanceFile)) {
    $attendanceList = json_decode(file_get_contents($attendanceFile), true) ?: [];
}

$alert = "";
$selectedDate = $_GET['tanggal'] ?? date('Y-m-d');

// Handle Save Attendance
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['save_guru_attendance'])) {
    $postDate = $_POST['tanggal'] ?? date('Y-m-d');
    $statusMap = $_POST['status'] ?? [];
    $masukMap = $_POST['jam_masuk'] ?? [];
    $pulangMap = $_POST['jam_pulang'] ?? [];
    $ketMap = $_POST['keterangan'] ?? [];

    $records = [];
    $hadirCount = 0;
    $terlambatCount = 0;
    $sakitCount = 0;
    $izinCount = 0;
    $dinasCount = 0;

    foreach ($statusMap as $gid => $st) {
        $stVal = in_array($st, ['Hadir', 'Terlambat', 'Sakit', 'Izin', 'Dinas Luar']) ? $st : 'Hadir';
        if ($stVal === 'Hadir') $hadirCount++;
        elseif ($stVal === 'Terlambat') $terlambatCount++;
        elseif ($stVal === 'Sakit') $sakitCount++;
        elseif ($stVal === 'Izin') $izinCount++;
        elseif ($stVal === 'Dinas Luar') $dinasCount++;

        // Find teacher name
        $gName = "Guru #" . $gid;
        foreach ($teachers as $t) {
            if (($t['id'] ?? '') === $gid) {
                $gName = $t['nama'] ?? $gName;
                break;
            }
        }

        $records[] = [
            'guru_id' => $gid,
            'nama' => $gName,
            'status' => $stVal,
            'jam_masuk' => trim($masukMap[$gid] ?? '06:45'),
            'jam_pulang' => trim($pulangMap[$gid] ?? '14:00'),
            'keterangan' => trim($ketMap[$gid] ?? '')
        ];
    }

    $sessionId = "ATT-G-" . str_replace('-', '', $postDate);

    // Replace if exists
    $found = false;
    foreach ($attendanceList as &$att) {
        if (($att['id'] ?? '') === $sessionId || ($att['tanggal'] ?? '') === $postDate) {
            $att['total_guru'] = count($records);
            $att['hadir'] = $hadirCount;
            $att['terlambat'] = $terlambatCount;
            $att['sakit'] = $sakitCount;
            $att['izin'] = $izinCount;
            $att['dinas_luar'] = $dinasCount;
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
            'total_guru' => count($records),
            'hadir' => $hadirCount,
            'terlambat' => $terlambatCount,
            'sakit' => $sakitCount,
            'izin' => $izinCount,
            'dinas_luar' => $dinasCount,
            'records' => $records,
            'created_at' => date('Y-m-d H:i:s')
        ];
    }

    file_put_contents($attendanceFile, json_encode($attendanceList, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    $alert = "Data absensi guru tanggal " . htmlspecialchars($postDate) . " berhasil disimpan!";
    $selectedDate = $postDate;
}

// Find existing record for this date
$existingSession = null;
foreach ($attendanceList as $att) {
    if (($att['tanggal'] ?? '') === $selectedDate) {
        $existingSession = $att;
        break;
    }
}
$existingRecordsMap = [];
if ($existingSession && !empty($existingSession['records'])) {
    foreach ($existingSession['records'] as $r) {
        $existingRecordsMap[$r['guru_id']] = $r;
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
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5 mb-6">
            <div>
                <h2 class="text-xl font-heading font-extrabold text-slate-900">Input Presensi Harian Guru & Staf</h2>
                <p class="text-xs text-slate-500 mt-1">Catat kehadiran, jam datang, dan jam pulang pendidik</p>
            </div>
            
            <form method="GET" class="flex items-center gap-3">
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-0.5">Pilih Tanggal</label>
                    <input type="date" name="tanggal" value="<?= htmlspecialchars($selectedDate) ?>" onchange="this.form.submit()" class="px-3 py-2 text-xs rounded-xl border border-slate-300 font-semibold focus:ring-2 focus:ring-brand-500 focus:outline-none">
                </div>
                <div class="pt-4">
                    <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition">
                        <i class="fa-solid fa-arrows-rotate mr-1"></i> Muat
                    </button>
                </div>
            </form>
        </div>

        <?php if ($existingSession): ?>
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-6 p-4 rounded-2xl bg-slate-50 border border-slate-200 text-center">
            <div>
                <span class="block text-[11px] text-emerald-600 font-bold uppercase">Hadir</span>
                <span class="text-xl font-heading font-extrabold text-emerald-700"><?= $existingSession['hadir'] ?? 0 ?></span>
            </div>
            <div>
                <span class="block text-[11px] text-amber-600 font-bold uppercase">Terlambat</span>
                <span class="text-xl font-heading font-extrabold text-amber-700"><?= $existingSession['terlambat'] ?? 0 ?></span>
            </div>
            <div>
                <span class="block text-[11px] text-blue-600 font-bold uppercase">Sakit</span>
                <span class="text-xl font-heading font-extrabold text-blue-700"><?= $existingSession['sakit'] ?? 0 ?></span>
            </div>
            <div>
                <span class="block text-[11px] text-purple-600 font-bold uppercase">Izin</span>
                <span class="text-xl font-heading font-extrabold text-purple-700"><?= $existingSession['izin'] ?? 0 ?></span>
            </div>
            <div>
                <span class="block text-[11px] text-indigo-600 font-bold uppercase">Dinas Luar</span>
                <span class="text-xl font-heading font-extrabold text-indigo-700"><?= $existingSession['dinas_luar'] ?? 0 ?></span>
            </div>
        </div>
        <?php endif; ?>

        <!-- Attendance Form -->
        <form method="POST">
            <input type="hidden" name="save_guru_attendance" value="1">
            <input type="hidden" name="tanggal" value="<?= htmlspecialchars($selectedDate) ?>">

            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold text-slate-600">
                    Daftar Guru & Staf (<?= count($teachers) ?> Orang)
                </span>
                <button type="button" onclick="setAllGuruHadir()" class="px-3.5 py-1.5 rounded-xl text-xs font-bold text-brand-700 bg-brand-50 hover:bg-brand-100 border border-brand-200 transition flex items-center gap-1.5">
                    <i class="fa-solid fa-check-double"></i> Tandai Semua Hadir
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-bold uppercase tracking-wider border-b border-slate-200">
                            <th class="p-3.5 rounded-l-xl w-12 text-center">No</th>
                            <th class="p-3.5">Nama Guru</th>
                            <th class="p-3.5">Jabatan / Mapel</th>
                            <th class="p-3.5">Status Kehadiran</th>
                            <th class="p-3.5">Jam Masuk</th>
                            <th class="p-3.5">Jam Pulang</th>
                            <th class="p-3.5 rounded-r-xl">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        <?php if (empty($teachers)): ?>
                        <tr>
                            <td colspan="7" class="py-10 text-center text-slate-400">
                                Belum ada data guru. Silakan tambahkan di menu <a href="guru.php" class="text-brand-700 font-bold underline">Data Guru</a>.
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php $no = 1; foreach ($teachers as $t): 
                            $gid = $t['id'] ?? ('g-' . $no);
                            $rec = $existingRecordsMap[$gid] ?? null;
                            $curStatus = $rec['status'] ?? 'Hadir';
                            $curMasuk = $rec['jam_masuk'] ?? '06:45';
                            $curPulang = $rec['jam_pulang'] ?? '14:00';
                            $curKet = $rec['keterangan'] ?? '';
                        ?>
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-3.5 text-center text-slate-400 font-bold"><?= $no++ ?></td>
                            <td class="p-3.5">
                                <span class="font-bold text-slate-900 block"><?= htmlspecialchars($t['nama'] ?? '-') ?></span>
                                <span class="text-[11px] text-slate-400"><?= htmlspecialchars($t['pendidikan'] ?? '') ?></span>
                            </td>
                            <td class="p-3.5">
                                <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-brand-50 text-brand-700">
                                    <?= htmlspecialchars($t['jabatan'] ?? 'Guru') ?>
                                </span>
                            </td>
                            <td class="p-3.5">
                                <select name="status[<?= $gid ?>]" class="guru-status-select px-3 py-1.5 rounded-xl border border-slate-300 text-xs font-bold focus:ring-2 focus:ring-brand-500 focus:outline-none">
                                    <option value="Hadir" <?= $curStatus === 'Hadir' ? 'selected' : '' ?>>Hadir</option>
                                    <option value="Terlambat" <?= $curStatus === 'Terlambat' ? 'selected' : '' ?>>Terlambat</option>
                                    <option value="Sakit" <?= $curStatus === 'Sakit' ? 'selected' : '' ?>>Sakit</option>
                                    <option value="Izin" <?= $curStatus === 'Izin' ? 'selected' : '' ?>>Izin</option>
                                    <option value="Dinas Luar" <?= $curStatus === 'Dinas Luar' ? 'selected' : '' ?>>Dinas Luar</option>
                                </select>
                            </td>
                            <td class="p-3.5">
                                <input type="time" name="jam_masuk[<?= $gid ?>]" value="<?= htmlspecialchars($curMasuk) ?>" class="guru-masuk-input px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-brand-500 focus:outline-none">
                            </td>
                            <td class="p-3.5">
                                <input type="time" name="jam_pulang[<?= $gid ?>]" value="<?= htmlspecialchars($curPulang) ?>" class="guru-pulang-input px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-brand-500 focus:outline-none">
                            </td>
                            <td class="p-3.5">
                                <input type="text" name="keterangan[<?= $gid ?>]" value="<?= htmlspecialchars($curKet) ?>" placeholder="Catatan kegiatan..." class="w-full px-3 py-1.5 rounded-lg border border-slate-200 text-xs focus:ring-1 focus:ring-brand-500 focus:outline-none">
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if (!empty($teachers)): ?>
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100 mt-6">
                <button type="submit" class="px-6 py-3 rounded-xl font-bold text-white bg-brand-700 hover:bg-brand-800 shadow transition flex items-center gap-2 text-sm">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Rekap Presensi Guru
                </button>
            </div>
            <?php endif; ?>
        </form>

    </div>

    <!-- Riwayat Rekap Presensi Guru -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
        <h3 class="text-base font-heading font-extrabold text-slate-900 mb-4">Riwayat Rekap Presensi Guru</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 font-bold uppercase tracking-wider border-b border-slate-200">
                        <th class="p-3 rounded-l-xl">Tanggal</th>
                        <th class="p-3">Total Guru</th>
                        <th class="p-3 text-emerald-700">Hadir</th>
                        <th class="p-3 text-amber-700">Terlambat</th>
                        <th class="p-3 text-blue-700">Sakit</th>
                        <th class="p-3 text-purple-700">Izin</th>
                        <th class="p-3 text-indigo-700">Dinas Luar</th>
                        <th class="p-3 text-right rounded-r-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    <?php if (empty($attendanceList)): ?>
                    <tr>
                        <td colspan="8" class="py-6 text-center text-slate-400">Belum ada rekapan absensi guru tersimpan.</td>
                    </tr>
                    <?php else: ?>
                    <?php 
                    $reversed = array_reverse($attendanceList);
                    foreach (array_slice($reversed, 0, 10) as $att): 
                    ?>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-3 font-bold text-slate-800"><?= htmlspecialchars($att['tanggal'] ?? '-') ?></td>
                        <td class="p-3 font-bold"><?= $att['total_guru'] ?? 0 ?></td>
                        <td class="p-3 text-emerald-600 font-bold"><?= $att['hadir'] ?? 0 ?></td>
                        <td class="p-3 text-amber-600 font-bold"><?= $att['terlambat'] ?? 0 ?></td>
                        <td class="p-3 text-blue-600 font-bold"><?= $att['sakit'] ?? 0 ?></td>
                        <td class="p-3 text-purple-600 font-bold"><?= $att['izin'] ?? 0 ?></td>
                        <td class="p-3 text-indigo-600 font-bold"><?= $att['dinas_luar'] ?? 0 ?></td>
                        <td class="p-3 text-right">
                            <a href="?tanggal=<?= urlencode($att['tanggal'] ?? '') ?>" class="px-3 py-1.5 rounded-lg bg-brand-50 text-brand-700 hover:bg-brand-100 font-bold text-xs inline-flex items-center gap-1">
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
function setAllGuruHadir() {
    document.querySelectorAll('.guru-status-select').forEach(sel => {
        sel.value = 'Hadir';
    });
    document.querySelectorAll('.guru-masuk-input').forEach(inp => {
        inp.value = '06:45';
    });
    document.querySelectorAll('.guru-pulang-input').forEach(inp => {
        inp.value = '14:00';
    });
}
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
