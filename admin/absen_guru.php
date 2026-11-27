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

$userRole = $_SESSION['admin_role'] ?? 'admin';
$loggedUser = $_SESSION['admin_username'] ?? '';
$loggedTeacherName = $_SESSION['teacher_nama'] ?? '';

// Cari guru aktif berdasarkan sesi login
$activeGuru = null;
if (!empty($loggedTeacherName)) {
    foreach ($teachers as $t) {
        if (strcasecmp($t['nama'] ?? '', $loggedTeacherName) === 0) {
            $activeGuru = $t;
            break;
        }
    }
}
if (!$activeGuru && !empty($loggedUser)) {
    foreach ($teachers as $t) {
        if (($t['nip'] ?? '') === $loggedUser || strcasecmp($t['nama'] ?? '', $loggedUser) === 0) {
            $activeGuru = $t;
            break;
        }
    }
}
if (!$activeGuru && !empty($teachers)) {
    $activeGuru = $teachers[0];
}

$alert = "";
$alertType = "success";
$selectedDate = $_GET['tanggal'] ?? date('Y-m-d');

// 1. Handle Self-Attendance Submission (Guru Masuk / Pulang)
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['submit_self_attendance'])) {
    $guruId = trim($_POST['guru_id'] ?? '');
    $type = trim($_POST['presensi_type'] ?? 'Masuk');
    $fotoSelfie = trim($_POST['foto_selfie'] ?? '');
    $jarakMeter = intval($_POST['jarak_meter'] ?? 0);
    $lat = trim($_POST['lat'] ?? '');
    $lng = trim($_POST['lng'] ?? '');
    $isDemo = isset($_POST['is_demo']) && $_POST['is_demo'] === '1';

    // Validasi radius sekolah (200m)
    if ($jarakMeter > 200 && !$isDemo) {
        $alert = "Gagal: Anda terdeteksi berada di luar radius sekolah ({$jarakMeter} meter)! Batas maksimal adalah 200 meter.";
        $alertType = "danger";
    } else {
        $curDate = date('Y-m-d');
        $curTime = date('H:i');
        $attId = "ATT-G-" . str_replace('-', '', $curDate);

        // Cari sesi hari ini
        $foundSession = false;
        foreach ($attendanceList as &$session) {
            if (($session['id'] ?? '') === $attId || ($session['tanggal'] ?? '') === $curDate) {
                $foundSession = true;
                $records = &$session['records'];
                $foundRec = false;

                foreach ($records as &$rec) {
                    if (strval($rec['guru_id'] ?? '') === strval($guruId)) {
                        $foundRec = true;
                        if ($type === 'Masuk') {
                            $rec['jam_masuk'] = $curTime;
                            $rec['status'] = (date('H') > 7 || (date('H') == 7 && date('i') > 0)) ? 'Terlambat' : 'Hadir';
                            if (!empty($fotoSelfie)) $rec['foto_selfie'] = $fotoSelfie;
                            $rec['lat'] = $lat;
                            $rec['lng'] = $lng;
                            $rec['jarak_meter'] = $jarakMeter;
                            $rec['metode'] = 'Wajah & GPS (Mandiri)';
                        } elseif ($type === 'Pulang') {
                            $rec['jam_pulang'] = $curTime;
                            if (!empty($fotoSelfie)) $rec['foto_selfie'] = $fotoSelfie;
                        }
                        break;
                    }
                }

                if (!$foundRec) {
                    $gName = "Guru";
                    foreach ($teachers as $t) {
                        if (strval($t['id'] ?? '') === strval($guruId)) {
                            $gName = $t['nama'] ?? 'Guru';
                            break;
                        }
                    }
                    $statusInit = ($type === 'Masuk' && (date('H') > 7 || (date('H') == 7 && date('i') > 0))) ? 'Terlambat' : 'Hadir';
                    $records[] = [
                        'guru_id' => $guruId,
                        'nama' => $gName,
                        'status' => $statusInit,
                        'jam_masuk' => $type === 'Masuk' ? $curTime : '06:45',
                        'jam_pulang' => $type === 'Pulang' ? $curTime : '',
                        'keterangan' => $type === 'Masuk' ? 'Presensi mandiri wajah & GPS' : '',
                        'foto_selfie' => $fotoSelfie,
                        'lat' => $lat,
                        'lng' => $lng,
                        'jarak_meter' => $jarakMeter,
                        'metode' => 'Wajah & GPS (Mandiri)'
                    ];
                }
                break;
            }
        }

        if (!$foundSession) {
            $gName = "Guru";
            foreach ($teachers as $t) {
                if (strval($t['id'] ?? '') === strval($guruId)) {
                    $gName = $t['nama'] ?? 'Guru';
                    break;
                }
            }
            $statusInit = ($type === 'Masuk' && (date('H') > 7 || (date('H') == 7 && date('i') > 0))) ? 'Terlambat' : 'Hadir';
            $attendanceList[] = [
                'id' => $attId,
                'tanggal' => $curDate,
                'total_guru' => count($teachers),
                'hadir' => 1,
                'terlambat' => $statusInit === 'Terlambat' ? 1 : 0,
                'sakit' => 0,
                'izin' => 0,
                'dinas_luar' => 0,
                'records' => [
                    [
                        'guru_id' => $guruId,
                        'nama' => $gName,
                        'status' => $statusInit,
                        'jam_masuk' => $type === 'Masuk' ? $curTime : '06:45',
                        'jam_pulang' => $type === 'Pulang' ? $curTime : '',
                        'keterangan' => 'Presensi mandiri wajah & GPS',
                        'foto_selfie' => $fotoSelfie,
                        'lat' => $lat,
                        'lng' => $lng,
                        'jarak_meter' => $jarakMeter,
                        'metode' => 'Wajah & GPS (Mandiri)'
                    ]
                ],
                'created_at' => date('Y-m-d H:i:s')
            ];
        }

        file_put_contents($attendanceFile, json_encode($attendanceList, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        $alert = "Presensi {$type} berhasil dikonfirmasi pada pukul {$curTime} WIB!";
        $alertType = "success";
    }
}

// 2. Handle Manual Attendance (Admin / Guru Izin/Sakit)
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['save_manual_attendance'])) {
    $guruId = trim($_POST['guru_id'] ?? '');
    $status = trim($_POST['status'] ?? 'Hadir');
    $masuk = trim($_POST['jam_masuk'] ?? '06:45');
    $pulang = trim($_POST['jam_pulang'] ?? '14:00');
    $keterangan = trim($_POST['keterangan'] ?? '');
    $postDate = trim($_POST['tanggal'] ?? date('Y-m-d'));

    $attId = "ATT-G-" . str_replace('-', '', $postDate);
    $foundSession = false;

    foreach ($attendanceList as &$session) {
        if (($session['id'] ?? '') === $attId || ($session['tanggal'] ?? '') === $postDate) {
            $foundSession = true;
            $records = &$session['records'];
            $foundRec = false;

            foreach ($records as &$rec) {
                if (strval($rec['guru_id'] ?? '') === strval($guruId)) {
                    $foundRec = true;
                    $rec['status'] = $status;
                    $rec['jam_masuk'] = $masuk;
                    $rec['jam_pulang'] = $pulang;
                    $rec['keterangan'] = $keterangan;
                    $rec['metode'] = 'Manual';
                    break;
                }
            }

            if (!$foundRec) {
                $gName = "Guru";
                foreach ($teachers as $t) {
                    if (strval($t['id'] ?? '') === strval($guruId)) {
                        $gName = $t['nama'] ?? 'Guru';
                        break;
                    }
                }
                $records[] = [
                    'guru_id' => $guruId,
                    'nama' => $gName,
                    'status' => $status,
                    'jam_masuk' => $masuk,
                    'jam_pulang' => $pulang,
                    'keterangan' => $keterangan,
                    'metode' => 'Manual'
                ];
            }
            break;
        }
    }

    if (!$foundSession) {
        $gName = "Guru";
        foreach ($teachers as $t) {
            if (strval($t['id'] ?? '') === strval($guruId)) {
                $gName = $t['nama'] ?? 'Guru';
                break;
            }
        }
        $attendanceList[] = [
            'id' => $attId,
            'tanggal' => $postDate,
            'total_guru' => count($teachers),
            'hadir' => ($status === 'Hadir' || $status === 'Terlambat') ? 1 : 0,
            'terlambat' => $status === 'Terlambat' ? 1 : 0,
            'sakit' => $status === 'Sakit' ? 1 : 0,
            'izin' => $status === 'Izin' ? 1 : 0,
            'dinas_luar' => $status === 'Dinas Luar' ? 1 : 0,
            'records' => [
                [
                    'guru_id' => $guruId,
                    'nama' => $gName,
                    'status' => $status,
                    'jam_masuk' => $masuk,
                    'jam_pulang' => $pulang,
                    'keterangan' => $keterangan,
                    'metode' => 'Manual'
                ]
            ],
            'created_at' => date('Y-m-d H:i:s')
        ];
    }

    file_put_contents($attendanceFile, json_encode($attendanceList, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    $alert = "Presensi manual berhasil disimpan!";
    $alertType = "success";
}

// Cari sesi tanggal terpilih
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
        $existingRecordsMap[strval($r['guru_id'] ?? '')] = $r;
    }
}
?>

<div class="space-y-6 max-w-7xl mx-auto">

    <?php if (!empty($alert)): ?>
    <div class="p-4 rounded-2xl <?= $alertType === 'danger' ? 'bg-rose-50 border border-rose-300 text-rose-800' : 'bg-emerald-50 border border-emerald-300 text-emerald-800' ?> flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i class="fa-solid <?= $alertType === 'danger' ? 'fa-triangle-exclamation text-rose-600' : 'fa-circle-check text-emerald-600' ?>"></i>
            <span class="text-sm font-semibold"><?= htmlspecialchars($alert) ?></span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-slate-400 hover:text-slate-700">&times;</button>
    </div>
    <?php endif; ?>

    <?php if ($userRole === 'guru'): ?>
    <!-- ======================================================== -->
    <!-- TAMPILAN KHUSUS PORTAL GURU: PRESENSI MANDIRI           -->
    <!-- ======================================================== -->
    <div class="bg-gradient-to-r from-brand-900 via-brand-800 to-emerald-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-white/10 p-1 backdrop-blur-md border border-white/20 shadow-inner flex-shrink-0">
                    <img src="../<?= htmlspecialchars($activeGuru['foto'] ?? 'assets/img/teachers/male-1.svg') ?>" class="w-full h-full object-cover rounded-xl" alt="Foto Profil Guru">
                </div>
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 mb-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                        Portal Presensi Mandiri
                    </span>
                    <h2 class="text-xl sm:text-2xl font-heading font-extrabold text-white"><?= htmlspecialchars($activeGuru['nama'] ?? 'Dewan Guru') ?></h2>
                    <p class="text-xs text-slate-300 font-medium mt-0.5">NIP: <?= htmlspecialchars($activeGuru['nip'] ?? '-') ?> • <?= htmlspecialchars($activeGuru['jabatan'] ?? 'Guru') ?></p>
                </div>
            </div>

            <div class="flex flex-col sm:items-end gap-2 bg-white/10 p-4 rounded-2xl backdrop-blur-md border border-white/15">
                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-200 flex items-center gap-1.5">
                    <i class="fa-regular fa-clock"></i> Waktu Indonesia Barat (WIB)
                </span>
                <div id="phpLiveClock" class="text-2xl sm:text-3xl font-heading font-black tracking-tight text-white font-mono">
                    <?= date('H:i:s') ?>
                </div>
                <div class="text-xs text-slate-300 font-semibold">
                    <?= date('l, d F Y') ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel Presensi Wajah & GPS -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Geolocation Check -->
        <div class="lg:col-span-5 bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2 border-b pb-3">
                <i class="fa-solid fa-location-dot text-brand-700"></i> Verifikasi Lokasi GPS
            </h3>

            <div id="phpGpsCard" class="p-4 rounded-2xl border bg-slate-50 border-slate-200 space-y-2">
                <div class="flex items-center gap-2">
                    <span id="phpGpsDot" class="w-3 h-3 rounded-full bg-amber-500 animate-pulse"></span>
                    <strong id="phpGpsTitle" class="text-xs font-bold text-slate-800">Mendeteksi Titik GPS...</strong>
                </div>
                <p id="phpGpsDesc" class="text-xs text-slate-500 leading-relaxed">
                    Mengukur jarak Anda ke gerbang SMP Islam Al-Kholili (Radius maks: 200 meter).
                </p>
                <div class="pt-2 border-t border-slate-200 text-xs flex justify-between font-mono">
                    <span class="text-slate-500">Jarak Terdeteksi:</span>
                    <strong id="phpGpsDistVal" class="text-slate-800">-</strong>
                </div>
            </div>

            <div class="p-3 bg-amber-50 rounded-2xl border border-amber-200 flex items-center justify-between text-xs">
                <div>
                    <strong class="text-amber-900 block text-[11px]">Mode Simulasi GPS:</strong>
                    <span class="text-[10px] text-amber-700">Gunakan untuk uji coba jika di luar sekolah</span>
                </div>
                <input type="checkbox" id="phpGpsDemoToggle" onchange="togglePhpGpsDemo(this.checked)" class="rounded text-brand-600 focus:ring-brand-500 h-4 w-4">
            </div>
        </div>

        <!-- Kamera Webcam -->
        <div class="lg:col-span-7 bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2 border-b pb-3">
                <i class="fa-solid fa-camera text-brand-700"></i> Verifikasi Wajah (Selfie)
            </h3>

            <div class="relative w-full max-w-sm mx-auto aspect-[4/3] bg-slate-950 rounded-2xl overflow-hidden shadow-inner flex items-center justify-center border-2 border-slate-300">
                <video id="phpVideoFeed" autoplay playsinline muted class="w-full h-full object-cover"></video>
                <canvas id="phpCanvas" class="hidden"></canvas>
                <img id="phpSnapshotPreview" class="hidden absolute inset-0 w-full h-full object-cover z-10" alt="Selfie Guru">
            </div>

            <div class="flex items-center justify-center gap-2">
                <button type="button" onclick="startPhpCamera()" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold">
                    <i class="fa-solid fa-video mr-1"></i> Buka Kamera
                </button>
                <button type="button" onclick="capturePhpSelfie()" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold">
                    <i class="fa-solid fa-camera mr-1"></i> Ambil Foto Wajah
                </button>
            </div>

            <!-- Form Action Submit -->
            <form method="POST" action="absen_guru.php" class="pt-4 border-t border-slate-100 grid grid-cols-2 gap-3">
                <input type="hidden" name="submit_self_attendance" value="1">
                <input type="hidden" name="guru_id" value="<?= htmlspecialchars($activeGuru['id'] ?? '') ?>">
                <input type="hidden" id="phpInputFoto" name="foto_selfie" value="">
                <input type="hidden" id="phpInputJarak" name="jarak_meter" value="0">
                <input type="hidden" id="phpInputLat" name="lat" value="">
                <input type="hidden" id="phpInputLng" name="lng" value="">
                <input type="hidden" id="phpInputDemo" name="is_demo" value="0">

                <button type="submit" name="presensi_type" value="Masuk" class="py-3 bg-brand-700 hover:bg-brand-800 text-white rounded-xl text-xs font-bold shadow-md">
                    <i class="fa-solid fa-right-to-bracket mr-1"></i> Absen Masuk
                </button>
                <button type="submit" name="presensi_type" value="Pulang" class="py-3 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl text-xs font-bold shadow-md">
                    <i class="fa-solid fa-right-from-bracket mr-1"></i> Absen Pulang
                </button>
            </form>
        </div>
    </div>

    <?php else: ?>
    <!-- ======================================================== -->
    <!-- TAMPILAN KHUSUS ADMIN: REKAPITULASI PRESENSI GURU       -->
    <!-- ======================================================== -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
            <div>
                <h2 class="text-xl font-heading font-extrabold text-slate-900">Rekapitulasi Presensi Dewan Guru & Staf</h2>
                <p class="text-xs text-slate-500 mt-1">Data absensi otomatis dari presensi mandiri (wajah & GPS) serta input manual.</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <button type="button" onclick="openPhpAttendanceSettingsModal()" class="px-3.5 py-2 rounded-xl text-xs font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 transition flex items-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-sliders text-indigo-600"></i>
                    <span>Atur Jam & Lokasi Sekolah</span>
                </button>
                <form method="GET" class="flex items-center gap-3">
                    <div>
                        <input type="date" name="tanggal" value="<?= htmlspecialchars($selectedDate) ?>" onchange="this.form.submit()" class="px-3 py-2 text-xs rounded-xl border border-slate-300 font-semibold focus:ring-2 focus:ring-brand-500 focus:outline-none">
                    </div>
                    <div>
                        <button type="button" onclick="window.print()" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition">
                            <i class="fa-solid fa-print mr-1"></i> Cetak
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Banner Kebijakan Jam Kerja & Lokasi Sekolah Aktif -->
        <div class="p-4 rounded-2xl bg-gradient-to-r from-emerald-50 via-teal-50 to-slate-50 border border-emerald-200/90 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="space-y-1.5">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-700 text-white text-[10px] font-extrabold uppercase tracking-wide flex items-center gap-1 shadow-xs">
                        <i class="fa-solid fa-shield-halved"></i> Proteksi Geofencing Sekolah Aktif
                    </span>
                    <span class="text-xs font-bold text-slate-800" id="phpPolicyLocationName">SMP Islam Al-Kholili (Kedopok, Probolinggo)</span>
                </div>
                <div class="flex flex-wrap items-center gap-x-5 gap-y-1 text-xs text-slate-600">
                    <span class="flex items-center gap-1.5">
                        <i class="fa-regular fa-clock text-emerald-600 font-bold"></i> Jam Masuk: <strong id="phpPolicyJamMasuk" class="text-slate-900 font-bold">06.45 WIB</strong> (Batas: <strong id="phpPolicyBatasTerlambat" class="text-amber-700 font-bold">07.00 WIB</strong>)
                    </span>
                    <span class="flex items-center gap-1.5">
                        <i class="fa-solid fa-person-walking-arrow-right text-emerald-600 font-bold"></i> Jam Pulang: <strong id="phpPolicyJamPulang" class="text-slate-900 font-bold">14.00 WIB</strong>
                    </span>
                    <span class="flex items-center gap-1.5">
                        <i class="fa-solid fa-location-dot text-rose-600 font-bold"></i> Radius Maksimal: <strong id="phpPolicyRadius" class="text-rose-700 font-bold">200 Meter</strong>
                    </span>
                </div>
                <p class="text-[11px] text-emerald-800/90 font-medium flex items-center gap-1">
                    <i class="fa-solid fa-circle-info text-emerald-600"></i>
                    <span>Guru yang berada di luar radius sekolah otomatis <strong>diblokir</strong> dan tidak dapat melakukan presensi mandiri.</span>
                </p>
            </div>
            <button type="button" onclick="openPhpAttendanceSettingsModal()" class="px-3.5 py-2 bg-white hover:bg-emerald-50 text-emerald-800 border border-emerald-300 rounded-xl text-xs font-bold flex items-center gap-2 shadow-sm transition whitespace-nowrap self-stretch md:self-auto justify-center">
                <i class="fa-solid fa-gear text-emerald-600"></i>
                <span>Ubah Jam & Lokasi</span>
            </button>
        </div>

        <!-- Tabel Rekapan Admin -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 font-bold uppercase tracking-wider border-b border-slate-200">
                        <th class="p-3.5 w-12 text-center rounded-l-xl">No</th>
                        <th class="p-3.5">Foto Wajah</th>
                        <th class="p-3.5">Nama Guru & NIP</th>
                        <th class="p-3.5">Status Kehadiran</th>
                        <th class="p-3.5">Jam Masuk</th>
                        <th class="p-3.5">Jam Pulang</th>
                        <th class="p-3.5">Lokasi & Metode</th>
                        <th class="p-3.5">Keterangan</th>
                        <th class="p-3.5 text-center rounded-r-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    <?php if (empty($teachers)): ?>
                    <tr>
                        <td colspan="9" class="p-6 text-center text-slate-400">Belum ada data guru terdaftar.</td>
                    </tr>
                    <?php else: ?>
                    <?php 
                    $no = 1;
                    foreach ($teachers as $g): 
                        $gid = strval($g['id'] ?? '');
                        $rec = $existingRecordsMap[$gid] ?? null;
                        $status = $rec['status'] ?? 'Belum Presensi';
                        $masuk = $rec['jam_masuk'] ?? '-';
                        $pulang = $rec['jam_pulang'] ?? '-';
                        $foto = $rec['foto_selfie'] ?? '';
                        $jarak = $rec['jarak_meter'] ?? null;
                        $metode = $rec['metode'] ?? ($foto ? 'Wajah & GPS' : 'Manual');
                        $ket = $rec['keterangan'] ?? '-';
                    ?>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-3.5 text-center font-bold text-slate-400"><?= $no++ ?></td>
                        <td class="p-3.5">
                            <?php if (!empty($foto)): ?>
                            <a href="<?= htmlspecialchars($foto) ?>" target="_blank" class="block w-10 h-10 rounded-xl overflow-hidden border border-emerald-300 shadow hover:scale-105 transition" title="Klik lihat foto wajah">
                                <img src="<?= htmlspecialchars($foto) ?>" class="w-full h-full object-cover">
                            </a>
                            <?php else: ?>
                            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center text-xs">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <?php endif; ?>
                        </td>
                        <td class="p-3.5">
                            <strong class="text-slate-900 block"><?= htmlspecialchars($g['nama']) ?></strong>
                            <span class="text-[10px] text-slate-500 font-mono">NIP: <?= htmlspecialchars($g['nip'] ?? '-') ?> • <?= htmlspecialchars($g['jabatan'] ?? 'Guru') ?></span>
                        </td>
                        <td class="p-3.5">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold <?= $status === 'Hadir' ? 'bg-emerald-100 text-emerald-800' : ($status === 'Terlambat' ? 'bg-amber-100 text-amber-800' : ($status === 'Sakit' ? 'bg-rose-100 text-rose-800' : ($status === 'Izin' ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-600'))) ?>">
                                <?= htmlspecialchars($status) ?>
                            </span>
                        </td>
                        <td class="p-3.5 font-mono font-semibold text-slate-800"><?= htmlspecialchars($masuk) ?></td>
                        <td class="p-3.5 font-mono font-semibold text-slate-800"><?= htmlspecialchars($pulang) ?></td>
                        <td class="p-3.5">
                            <?php if ($jarak !== null): ?>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-emerald-50 text-emerald-800 text-[10px] font-bold border border-emerald-200">
                                <i class="fa-solid fa-location-dot"></i> <?= intval($jarak) ?>m (Valid)
                            </span>
                            <?php else: ?>
                            <span class="text-[10px] text-slate-500"><?= htmlspecialchars($metode) ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="p-3.5 text-slate-600"><?= htmlspecialchars($ket) ?></td>
                        <td class="p-3.5 text-center">
                            <button type="button" onclick="openPhpEditModal('<?= htmlspecialchars($gid) ?>', '<?= htmlspecialchars(addslashes($g['nama'])) ?>', '<?= htmlspecialchars($status) ?>', '<?= htmlspecialchars($masuk) ?>', '<?= htmlspecialchars($pulang) ?>', '<?= htmlspecialchars(addslashes($ket)) ?>')" class="p-1.5 text-brand-700 hover:bg-brand-50 rounded-lg transition" title="Edit Manual">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL EDIT MANUAL ADMIN -->
    <div id="phpEditModal" class="hidden fixed inset-0 z-50 bg-black/70 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-200 space-y-4">
            <div class="flex items-center justify-between border-b pb-3">
                <h4 class="font-bold text-base text-slate-900" id="phpModalTitle">Edit Presensi Manual</h4>
                <button type="button" onclick="document.getElementById('phpEditModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-700">&times;</button>
            </div>
            <form method="POST" action="absen_guru.php" class="space-y-4 text-xs">
                <input type="hidden" name="save_manual_attendance" value="1">
                <input type="hidden" id="phpModalGuruId" name="guru_id" value="">
                <input type="hidden" name="tanggal" value="<?= htmlspecialchars($selectedDate) ?>">

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Status Kehadiran</label>
                    <select id="phpModalStatus" name="status" class="w-full px-3 py-2 rounded-xl border border-slate-300 font-semibold">
                        <option value="Hadir">Hadir</option>
                        <option value="Terlambat">Terlambat</option>
                        <option value="Sakit">Sakit</option>
                        <option value="Izin">Izin</option>
                        <option value="Dinas Luar">Dinas Luar</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Jam Masuk</label>
                        <input type="time" id="phpModalMasuk" name="jam_masuk" value="06:45" class="w-full px-3 py-2 rounded-xl border border-slate-300">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Jam Pulang</label>
                        <input type="time" id="phpModalPulang" name="jam_pulang" value="14:00" class="w-full px-3 py-2 rounded-xl border border-slate-300">
                    </div>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Keterangan</label>
                    <input type="text" id="phpModalKet" name="keterangan" placeholder="Keterangan dinas / izin..." class="w-full px-3 py-2 rounded-xl border border-slate-300">
                </div>
                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('phpEditModal').classList.add('hidden')" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-brand-700 text-white rounded-xl font-bold">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL PENGATURAN JAM & LOKASI SEKOLAH (PHP) -->
    <div id="modalAttendanceSettingsPhp" class="hidden fixed inset-0 z-50 bg-black/70 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full overflow-hidden shadow-2xl border border-slate-200">
            <div class="bg-gradient-to-r from-brand-900 via-brand-800 to-emerald-900 p-5 text-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-emerald-300 text-lg border border-white/20">
                        <i class="fa-solid fa-sliders"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-heading font-extrabold text-white">Atur Jam Kerja & Lokasi Sekolah</h3>
                        <p class="text-xs text-emerald-200/80">Atur jam masuk, jam pulang, dan radius geofence sekolah.</p>
                    </div>
                </div>
                <button type="button" onclick="closePhpAttendanceSettingsModal()" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 flex items-center justify-center text-white/80 hover:text-white transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form id="formAttendanceSettingsPhp" onsubmit="savePhpAttendanceSettings(event)" class="p-6 space-y-5 text-xs max-h-[80vh] overflow-y-auto">
                <div class="space-y-3">
                    <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider flex items-center gap-2 border-b border-slate-100 pb-1.5">
                        <i class="fa-regular fa-clock text-brand-700"></i>
                        <span>1. Aturan Jam Kerja Sekolah</span>
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Jam Standar Masuk *</label>
                            <input type="time" id="phpCfgJamMasuk" required class="w-full px-3 py-2.5 rounded-xl border border-slate-300 font-semibold text-slate-800 focus:outline-none">
                            <span class="text-[10px] text-slate-400">Contoh: 06:45 WIB</span>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Batas Jam Terlambat *</label>
                            <input type="time" id="phpCfgBatasTerlambat" required class="w-full px-3 py-2.5 rounded-xl border border-slate-300 font-semibold text-slate-800 focus:outline-none">
                            <span class="text-[10px] text-slate-400">Lewat jam ini dihitung Terlambat (07:00)</span>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block font-bold text-slate-700 mb-1">Jam Standar Kepulangan *</label>
                            <input type="time" id="phpCfgJamPulang" required class="w-full px-3 py-2.5 rounded-xl border border-slate-300 font-semibold text-slate-800 focus:outline-none">
                            <span class="text-[10px] text-slate-400">Jam pulang resmi yang dianjurkan (14:00)</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-3 pt-2">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-1.5">
                        <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-location-dot text-rose-600"></i>
                            <span>2. Titik Lokasi & Radius Sekolah</span>
                        </h4>
                        <button type="button" onclick="getPhpCurrentLocation()" class="text-[11px] text-brand-700 hover:text-brand-800 font-bold flex items-center gap-1 hover:underline">
                            <i class="fa-solid fa-crosshairs"></i> Gunakan Lokasi Saya
                        </button>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Lokasi Sekolah</label>
                        <input type="text" id="phpCfgLocationName" required class="w-full px-3 py-2 rounded-xl border border-slate-300 font-medium text-slate-800 focus:outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Latitude</label>
                            <input type="text" id="phpCfgLatitude" required placeholder="-7.77884" class="w-full px-3 py-2 rounded-xl border border-slate-300 font-mono text-xs text-slate-800 focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Longitude</label>
                            <input type="text" id="phpCfgLongitude" required placeholder="113.20853" class="w-full px-3 py-2 rounded-xl border border-slate-300 font-mono text-xs text-slate-800 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Radius Toleransi Presensi (Meter) *</label>
                        <div class="flex items-center gap-3">
                            <input type="number" id="phpCfgRadiusMeters" min="20" max="5000" step="10" required class="w-32 px-3 py-2 rounded-xl border border-slate-300 font-bold text-slate-800 focus:outline-none">
                            <span class="text-xs text-slate-500 font-medium">Meter dari titik koordinat</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" onclick="closePhpAttendanceSettingsModal()" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">Batal</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-brand-700 hover:bg-brand-800 text-white font-bold text-xs transition shadow-md flex items-center gap-1.5">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Simpan Aturan Presensi</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

</div>

<script>
const DEFAULT_ATTENDANCE_CONFIG = {
    jamMasuk: "06:45",
    batasTerlambat: "07:00",
    jamPulang: "14:00",
    lat: -7.77884,
    lng: 113.20853,
    radiusMeters: 200,
    name: "SMP Islam Al-Kholili (Kedopok, Probolinggo)"
};

function getPhpAttendanceConfig() {
    try {
        const saved = localStorage.getItem('alkholili_attendance_settings');
        if (saved) return { ...DEFAULT_ATTENDANCE_CONFIG, ...JSON.parse(saved) };
    } catch (e) {}
    return { ...DEFAULT_ATTENDANCE_CONFIG };
}

let activeAttendanceConfig = getPhpAttendanceConfig();

function renderPhpPolicyDisplays() {
    const cfg = activeAttendanceConfig;
    const nameEl = document.getElementById('phpPolicyLocationName');
    const masukEl = document.getElementById('phpPolicyJamMasuk');
    const lambatEl = document.getElementById('phpPolicyBatasTerlambat');
    const pulangEl = document.getElementById('phpPolicyJamPulang');
    const radEl = document.getElementById('phpPolicyRadius');

    if (nameEl) nameEl.textContent = cfg.name;
    if (masukEl) masukEl.textContent = cfg.jamMasuk + " WIB";
    if (lambatEl) lambatEl.textContent = cfg.batasTerlambat + " WIB";
    if (pulangEl) pulangEl.textContent = cfg.jamPulang + " WIB";
    if (radEl) radEl.textContent = cfg.radiusMeters + " Meter";
}
renderPhpPolicyDisplays();

function openPhpAttendanceSettingsModal() {
    const cfg = getPhpAttendanceConfig();
    const inMasuk = document.getElementById('phpCfgJamMasuk');
    const inLambat = document.getElementById('phpCfgBatasTerlambat');
    const inPulang = document.getElementById('phpCfgJamPulang');
    const inName = document.getElementById('phpCfgLocationName');
    const inLat = document.getElementById('phpCfgLatitude');
    const inLng = document.getElementById('phpCfgLongitude');
    const inRad = document.getElementById('phpCfgRadiusMeters');

    if (inMasuk) inMasuk.value = cfg.jamMasuk || "06:45";
    if (inLambat) inLambat.value = cfg.batasTerlambat || "07:00";
    if (inPulang) inPulang.value = cfg.jamPulang || "14:00";
    if (inName) inName.value = cfg.name || "SMP Islam Al-Kholili";
    if (inLat) inLat.value = cfg.lat ?? -7.77884;
    if (inLng) inLng.value = cfg.lng ?? 113.20853;
    if (inRad) inRad.value = cfg.radiusMeters || 200;

    document.getElementById('modalAttendanceSettingsPhp')?.classList.remove('hidden');
}

function closePhpAttendanceSettingsModal() {
    document.getElementById('modalAttendanceSettingsPhp')?.classList.add('hidden');
}

function getPhpCurrentLocation() {
    if (!navigator.geolocation) {
        alert("Browser tidak mendukung GPS.");
        return;
    }
    navigator.geolocation.getCurrentPosition(
        pos => {
            document.getElementById('phpCfgLatitude').value = pos.coords.latitude.toFixed(6);
            document.getElementById('phpCfgLongitude').value = pos.coords.longitude.toFixed(6);
        },
        err => alert("Gagal mengambil titik GPS: " + err.message),
        { enableHighAccuracy: true }
    );
}

function savePhpAttendanceSettings(e) {
    e.preventDefault();
    const jamMasuk = document.getElementById('phpCfgJamMasuk').value;
    const batasTerlambat = document.getElementById('phpCfgBatasTerlambat').value;
    const jamPulang = document.getElementById('phpCfgJamPulang').value;
    const name = document.getElementById('phpCfgLocationName').value.trim();
    const lat = parseFloat(document.getElementById('phpCfgLatitude').value);
    const lng = parseFloat(document.getElementById('phpCfgLongitude').value);
    const radiusMeters = parseInt(document.getElementById('phpCfgRadiusMeters').value, 10);

    activeAttendanceConfig = { jamMasuk, batasTerlambat, jamPulang, lat, lng, radiusMeters, name };
    localStorage.setItem('alkholili_attendance_settings', JSON.stringify(activeAttendanceConfig));
    closePhpAttendanceSettingsModal();
    renderPhpPolicyDisplays();
    detectPhpLocation();
    alert("Aturan jam kerja dan lokasi sekolah berhasil disimpan!");
}

let isPhpDemo = false;
let phpStream = null;

function calculateDistance(lat1, lon1, lat2, lon2) {
    const R = 6371e3;
    const φ1 = lat1 * Math.PI / 180, φ2 = lat2 * Math.PI / 180;
    const Δφ = (lat2 - lat1) * Math.PI / 180, Δλ = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(Δφ/2) * Math.sin(Δφ/2) + Math.cos(φ1) * Math.cos(φ2) * Math.sin(Δλ/2) * Math.sin(Δλ/2);
    return Math.round(R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)));
}

function detectPhpLocation() {
    const card = document.getElementById('phpGpsCard');
    const dot = document.getElementById('phpGpsDot');
    const title = document.getElementById('phpGpsTitle');
    const desc = document.getElementById('phpGpsDesc');
    const distVal = document.getElementById('phpGpsDistVal');

    if (!navigator.geolocation) {
        if (title) title.textContent = "GPS tidak didukung browser";
        return;
    }

    navigator.geolocation.getCurrentPosition(
        pos => {
            const dist = calculateDistance(pos.coords.latitude, pos.coords.longitude, activeAttendanceConfig.lat, activeAttendanceConfig.lng);
            if (distVal) distVal.textContent = dist + " Meter";
            const inpJarak = document.getElementById('phpInputJarak');
            const inpLat = document.getElementById('phpInputLat');
            const inpLng = document.getElementById('phpInputLng');
            if (inpJarak) inpJarak.value = dist;
            if (inpLat) inpLat.value = pos.coords.latitude;
            if (inpLng) inpLng.value = pos.coords.longitude;

            if (dist <= activeAttendanceConfig.radiusMeters || isPhpDemo) {
                if (card) card.className = "p-4 rounded-2xl border bg-emerald-50 border-emerald-300 space-y-2";
                if (dot) dot.className = "w-3 h-3 rounded-full bg-emerald-500";
                if (title) title.textContent = "Lokasi Valid di Lingkungan Sekolah";
                if (desc) desc.textContent = "Titik GPS Anda terverifikasi di area sekolah (Jarak: " + dist + "m dari " + activeAttendanceConfig.name + ").";
            } else {
                if (card) card.className = "p-4 rounded-2xl border bg-rose-50 border-rose-300 space-y-2";
                if (dot) dot.className = "w-3 h-3 rounded-full bg-rose-500";
                if (title) title.textContent = "Di Luar Radius Sekolah (Maks: " + activeAttendanceConfig.radiusMeters + "m)";
                if (desc) desc.textContent = "Jarak Anda: " + dist + " meter. Presensi di luar sekolah tidak diizinkan!";
            }
        },
        err => {
            console.warn(err);
            if (isPhpDemo) {
                if (card) card.className = "p-4 rounded-2xl border bg-emerald-50 border-emerald-300 space-y-2";
                if (title) title.textContent = "Mode Simulasi GPS Aktif";
            }
        }
    );
}

function togglePhpGpsDemo(val) {
    isPhpDemo = val;
    document.getElementById('phpInputDemo').value = val ? '1' : '0';
    detectPhpLocation();
}

async function startPhpCamera() {
    const video = document.getElementById('phpVideoFeed');
    try {
        phpStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } });
        video.srcObject = phpStream;
    } catch (err) {
        alert("Gagal membuka kamera: " + err.message);
    }
}

function capturePhpSelfie() {
    const video = document.getElementById('phpVideoFeed');
    const canvas = document.getElementById('phpCanvas');
    const preview = document.getElementById('phpSnapshotPreview');
    if (!video || !canvas) return;

    canvas.width = video.videoWidth || 640;
    canvas.height = video.videoHeight || 480;
    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

    ctx.fillStyle = 'rgba(0, 0, 0, 0.7)';
    ctx.fillRect(0, canvas.height - 40, canvas.width, 40);
    ctx.fillStyle = '#10b981';
    ctx.font = 'bold 14px sans-serif';
    ctx.fillText('SMP ISLAM AL-KHOLILI - PRESENSI WAJAH', 15, canvas.height - 15);

    const dataUrl = canvas.toDataURL('image/jpeg', 0.85);
    document.getElementById('phpInputFoto').value = dataUrl;
    preview.src = dataUrl;
    preview.classList.remove('hidden');
}

function openPhpEditModal(id, nama, status, masuk, pulang, ket) {
    document.getElementById('phpModalGuruId').value = id;
    document.getElementById('phpModalTitle').textContent = "Edit Presensi: " + nama;
    document.getElementById('phpModalStatus').value = status !== 'Belum Presensi' ? status : 'Hadir';
    document.getElementById('phpModalMasuk').value = masuk !== '-' ? masuk : '06:45';
    document.getElementById('phpModalPulang').value = pulang !== '-' ? pulang : '14:00';
    document.getElementById('phpModalKet').value = ket !== '-' ? ket : '';
    document.getElementById('phpEditModal').classList.remove('hidden');
}

if (document.getElementById('phpGpsCard')) {
    detectPhpLocation();
}
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
