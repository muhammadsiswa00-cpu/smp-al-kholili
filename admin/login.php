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

$error = "";

// Proses Login Form
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $inputUser = trim($_POST['username'] ?? '');
    $inputPass = trim($_POST['password'] ?? '');

    // Validasi langsung dengan variabel kodingan
    if ($inputUser === $ADMIN_USERNAME && $inputPass === $ADMIN_PASSWORD) {
        // Anti Session Fixation: Regenerasi ID Session Baru
        session_regenerate_id(true);
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $inputUser;
        header("Location: index.php");
        exit;
    } else {
        $error = "Username atau Password yang Anda masukkan salah! Silakan coba lagi.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrator - SMP Islam Al-Kholili</title>
    <link rel="icon" type="image/png" href="../<?= htmlspecialchars($siteSettings['logo']) ?>">
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

        <div class="text-center mb-7">
            <div class="w-20 h-20 mx-auto p-1 bg-gradient-to-tr from-brand-700 to-gold-400 rounded-2xl shadow-lg mb-4 flex items-center justify-center">
                <img src="../<?= htmlspecialchars($siteSettings['logo']) ?>" alt="Logo SMP Islam Al-Kholili" class="w-full h-full object-contain bg-white rounded-xl p-1">
            </div>
            <h1 class="text-2xl font-heading font-extrabold text-slate-900">Login Administrator</h1>
            <p class="text-xs text-brand-700 font-bold uppercase tracking-wider mt-1">SMP ISLAM AL-KHOLILI KOTA PROBOLINGGO</p>
            <p class="text-xs text-slate-500 mt-1">Masukkan username dan password untuk masuk ke fitur admin</p>
        </div>

        <?php if (!empty($error)): ?>
        <div class="mb-5 p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation text-base flex-shrink-0"></i>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
        <?php endif; ?>

        <form action="login.php" method="POST" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1" for="username">
                    Username Admin
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                        <i class="fa-solid fa-user text-sm"></i>
                    </span>
                    <input type="text" id="username" name="username" required autofocus placeholder="Masukkan username" class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-600 text-sm">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1" for="password">
                    Password Admin
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                        <i class="fa-solid fa-lock text-sm"></i>
                    </span>
                    <input type="password" id="password" name="password" required placeholder="Masukkan password" class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-600 text-sm">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-3.5 rounded-xl font-bold text-white bg-gradient-to-r from-brand-700 to-emerald-600 hover:from-brand-800 hover:to-emerald-700 shadow-lg shadow-brand-700/30 transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2 text-sm">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    <span>Masuk ke Fitur Admin</span>
                </button>
            </div>
        </form>

        <div class="mt-7 pt-4 border-t border-slate-100 text-center">
            <a href="../index.php" class="text-xs text-brand-700 hover:underline font-semibold inline-flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Website Utama
            </a>
        </div>

    </div>

</body>
</html>
