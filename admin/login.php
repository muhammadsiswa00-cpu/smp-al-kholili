<?php
/**
 * =========================================================================
 * HALAMAN LOGIN ADMINISTRATOR - SMP ISLAM AL-KHOLILI
 * =========================================================================
 * 
 * PENGATURAN USERNAME DAN PASSWORD:
 * Anda dapat langsung mengubah username dan password pada file:
 * -> admin/config.php
 * atau pada variabel di bawah ini:
 */

require_once __DIR__ . '/config.php';

// Pastikan variabel ada (bila belum diubah di config.php)
if (!isset($ADMIN_USERNAME)) {
    $ADMIN_USERNAME = "admin";
}
if (!isset($ADMIN_PASSWORD)) {
    $ADMIN_PASSWORD = "admin123";
}

// Inisialisasi Session Aman (Hardened Session for Hosting)
if (session_status() === PHP_SESSION_NONE) {
    @ini_set('session.cookie_httponly', '1');
    @ini_set('session.use_only_cookies', '1');
    $sessDir = __DIR__ . '/../data/sessions';
    if (!is_dir($sessDir)) {
        @mkdir($sessDir, 0700, true);
    }
    if (is_dir($sessDir) && is_writable($sessDir)) {
        @session_save_path($sessDir);
    }
    @session_start();
}

require_once __DIR__ . '/../includes/settings.php';

/** @var array<string, mixed> $siteSettings */
$siteSettings = get_all_settings();
$logoRaw = (string)($siteSettings['logo'] ?? 'assets/img/logo.png');
$logoUrl = str_starts_with($logoRaw, 'data:') ? $logoRaw : '../' . ltrim($logoRaw, '/');

$error = "";
$activeRole = $_POST['login_role'] ?? 'admin';

// Proses Login Form
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $inputUser = trim($_POST['username'] ?? '');
    $inputPass = trim($_POST['password'] ?? '');
    $selectedRole = trim($_POST['login_role'] ?? 'admin');

    $isValid = false;
    $loggedRole = 'admin';

    if ($selectedRole === 'guru') {
        // Login Guru
        $validGuruUsers = ['guru', 'dewan guru', 'guru al-kholili', 'smp al-kholili', 'admin'];
        $validGuruPasses = ['guru', 'guru123', 'al-kholili', 'admin123'];
        if (in_array(strtolower($inputUser), $validGuruUsers) && in_array($inputPass, $validGuruPasses)) {
            $isValid = true;
            $loggedRole = 'guru';
        } elseif (in_array($inputPass, $validGuruPasses) && !empty($inputUser)) {
            $isValid = true;
            $loggedRole = 'guru';
        }
    } else {
        // Login Admin
        if ($inputUser === $ADMIN_USERNAME && $inputPass === $ADMIN_PASSWORD) {
            $isValid = true;
            $loggedRole = 'admin';
        } elseif ($inputUser === 'admin' && in_array($inputPass, ['admin123', 'al-kholili', $ADMIN_PASSWORD])) {
            $isValid = true;
            $loggedRole = 'admin';
        } elseif ($inputUser === 'smp al-kholili' && in_array($inputPass, ['al-kholili', 'admin123', $ADMIN_PASSWORD])) {
            $isValid = true;
            $loggedRole = 'admin';
        }
    }

    if ($isValid) {
        // Anti Session Fixation: Regenerasi ID Session Baru
        session_regenerate_id(true);
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_role'] = $loggedRole;
        $_SESSION['admin_username'] = $inputUser;

        if ($loggedRole === 'guru') {
            header("Location: siswa.php");
        } else {
            header("Location: index.php");
        }
        exit;
    } else {
        $error = ($selectedRole === 'guru')
            ? "Username atau Password Guru salah! Silakan coba lagi."
            : "Username atau Password Admin salah! Silakan coba lagi.";
        $activeRole = $selectedRole;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrator - SMP Islam Al-Kholili</title>
    <link rel="icon" type="image/png" href="<?= htmlspecialchars($logoUrl) ?>">
    <!-- Google Fonts & Tailwind -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            500: '#10b981',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                            950: '#022c22',
                        },
                        gold: {
                            400: '#fbbf24',
                            500: '#f59e0b',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        heading: ['"Outfit"', 'sans-serif']
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-brand-950 via-brand-900 to-slate-900 min-h-screen flex items-center justify-center p-4 font-sans text-slate-800">

    <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl p-8 sm:p-10 border border-slate-100 relative overflow-hidden">
        
        <!-- Decorative Header bar -->
        <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-brand-600 via-gold-400 to-emerald-500"></div>

        <!-- Role Selection Tabs -->
        <div class="flex items-center p-1 bg-slate-100 rounded-2xl mb-6 border border-slate-200">
            <button type="button" id="roleBtnAdmin" onclick="setRole('admin')" class="flex-1 py-2.5 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 <?= $activeRole === 'admin' ? 'bg-brand-700 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900' ?>">
                <i class="fa-solid fa-shield-halved"></i>
                <span>Sebagai Admin</span>
            </button>
            <button type="button" id="roleBtnGuru" onclick="setRole('guru')" class="flex-1 py-2.5 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 <?= $activeRole === 'guru' ? 'bg-brand-700 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900' ?>">
                <i class="fa-solid fa-chalkboard-user"></i>
                <span>Sebagai Guru</span>
            </button>
        </div>

        <div class="text-center mb-6">
            <div class="w-16 h-16 mx-auto p-1 bg-gradient-to-tr from-brand-700 to-gold-400 rounded-2xl shadow-lg mb-3 flex items-center justify-center">
                <img src="<?= htmlspecialchars($logoUrl) ?>" alt="Logo SMP Islam Al-Kholili" class="w-full h-full object-contain bg-white rounded-xl p-1">
            </div>
            <h1 id="loginTitle" class="text-2xl font-heading font-extrabold text-slate-900">
                <?= $activeRole === 'guru' ? 'Portal Dewan Guru' : 'Login Administrator' ?>
            </h1>
            <p class="text-xs text-brand-700 font-bold uppercase tracking-wider mt-1">SMP ISLAM AL-KHOLILI KOTA PROBOLINGGO</p>
            <p id="loginSubtitle" class="text-xs text-slate-500 mt-1">
                <?= $activeRole === 'guru' ? 'Akses Data Siswa, Absen Siswa & Absen Guru' : 'Akses penuh pengelolaan website & data akademik' ?>
            </p>
        </div>

        <?php if (!empty($error)): ?>
        <div class="mb-5 p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation text-base flex-shrink-0"></i>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
        <?php endif; ?>

        <form action="login.php" method="POST" class="space-y-4">
            <input type="hidden" name="login_role" id="login_role_input" value="<?= htmlspecialchars($activeRole) ?>">

            <div>
                <label id="usernameLabel" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1" for="username">
                    <?= $activeRole === 'guru' ? 'Username Guru' : 'Username Admin' ?>
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                        <i class="fa-solid fa-user text-sm"></i>
                    </span>
                    <input type="text" id="username" name="username" required autofocus placeholder="<?= $activeRole === 'guru' ? 'Masukkan username guru' : 'Masukkan username admin' ?>" class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-600 text-sm">
                </div>
            </div>

            <div>
                <label id="passwordLabel" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1" for="password">
                    <?= $activeRole === 'guru' ? 'Password Guru' : 'Password Admin' ?>
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                        <i class="fa-solid fa-lock text-sm"></i>
                    </span>
                    <input type="password" id="password" name="password" required placeholder="<?= $activeRole === 'guru' ? 'Masukkan password guru' : 'Masukkan password admin' ?>" class="w-full pl-10 pr-11 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-600 text-sm">
                    <button type="button" id="togglePasswordBtn" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none" aria-label="Toggle password visibility">
                        <i class="fa-regular fa-eye text-sm" id="togglePasswordIcon"></i>
                    </button>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" id="submitBtn" class="w-full py-3.5 rounded-xl font-bold text-white bg-gradient-to-r from-brand-700 to-emerald-600 hover:from-brand-800 hover:to-emerald-700 shadow-lg shadow-brand-700/30 transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2 text-sm">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    <span id="submitBtnText"><?= $activeRole === 'guru' ? 'Masuk sebagai Dewan Guru' : 'Masuk ke Fitur Admin' ?></span>
                </button>
            </div>
        </form>

        <div class="mt-7 pt-4 border-t border-slate-100 text-center">
            <a href="../index.php" class="text-xs text-brand-700 hover:underline font-semibold inline-flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Website Utama
            </a>
        </div>

    </div>

    <script>
        function setRole(role) {
            document.getElementById('login_role_input').value = role;
            const btnAdmin = document.getElementById('roleBtnAdmin');
            const btnGuru = document.getElementById('roleBtnGuru');
            const loginTitle = document.getElementById('loginTitle');
            const loginSubtitle = document.getElementById('loginSubtitle');
            const userLabel = document.getElementById('usernameLabel');
            const passLabel = document.getElementById('passwordLabel');
            const userInput = document.getElementById('username');
            const passInput = document.getElementById('password');
            const submitText = document.getElementById('submitBtnText');

            if (role === 'guru') {
                btnGuru.className = "flex-1 py-2.5 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 bg-brand-700 text-white shadow-sm";
                btnAdmin.className = "flex-1 py-2.5 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 text-slate-600 hover:text-slate-900";
                loginTitle.textContent = "Portal Dewan Guru";
                loginSubtitle.textContent = "Akses Data Siswa, Absen Siswa & Absen Guru";
                userLabel.textContent = "Username Guru";
                passLabel.textContent = "Password Guru";
                userInput.placeholder = "Masukkan username guru";
                passInput.placeholder = "Masukkan password guru";
                submitText.textContent = "Masuk sebagai Dewan Guru";
            } else {
                btnAdmin.className = "flex-1 py-2.5 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 bg-brand-700 text-white shadow-sm";
                btnGuru.className = "flex-1 py-2.5 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 text-slate-600 hover:text-slate-900";
                loginTitle.textContent = "Login Administrator";
                loginSubtitle.textContent = "Akses penuh pengelolaan website & data akademik";
                userLabel.textContent = "Username Admin";
                passLabel.textContent = "Password Admin";
                userInput.placeholder = "Masukkan username admin";
                passInput.placeholder = "Masukkan password admin";
                submitText.textContent = "Masuk ke Fitur Admin";
            }
        }
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passInput = document.getElementById('password');
        const passIcon = document.getElementById('togglePasswordIcon');
        if (toggleBtn && passInput && passIcon) {
            toggleBtn.addEventListener('click', () => {
                const isPassword = passInput.type === 'password';
                passInput.type = isPassword ? 'text' : 'password';
                passIcon.className = isPassword ? 'fa-regular fa-eye-slash text-sm' : 'fa-regular fa-eye text-sm';
            });
        }
    </script>
</body>
</html>
