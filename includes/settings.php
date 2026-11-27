<?php
// Settings Helper for SMP Islam Al-Kholili

function get_all_settings() {
    $file = __DIR__ . '/../data/settings.json';
    $defaults = [
        "site_name" => "SMP ISLAM AL-KHOLILI",
        "site_tagline" => "Kota Probolinggo • Berkarakter Qur'ani",
        "hero_title" => "Selamat Datang di SMP Islam Al-Kholili",
        "hero_subtitle" => "Mewujudkan generasi Muslim yang kokoh dalam akidah, unggul dalam prestasi akademik, santun dalam budi pekerti, serta terampil dalam penguasaan teknologi.",
        "hero_bg" => "assets/img/hero-bg.svg",
        "logo" => "assets/img/logo.png",
        "ppdb_url" => "ppdb.php",
        "ppdb_open" => true,
        "whatsapp_number" => "6285234567890",
        "whatsapp_display" => "+62 852-3456-7890",
        "phone" => "(0335) 421xxx / 0852-3456-7890",
        "email" => "info@smpislam-alkholili.sch.id",
        "alamat" => "Jl. KUA Blok Masjid Baiturrohman RT03 RW04 Kel. Kedopok, Kec. Kedopok, Kota Probolinggo",
        "jam_kerja" => "Senin - Sabtu: 07.00 - 14.00 WIB",
        "maps_embed" => "https://maps.google.com/maps?q=Kedopok,+Kota+Probolinggo,+Jawa+Timur&t=&z=15&ie=UTF8&iwloc=&output=embed",
        "admin_username" => "admin",
        "admin_password_hash" => '$2y$10$bzZ4W3Rlyr2qZYqe0NsfHuAlcSLIRbprOh1v8ZxZdmlAdBbEbxGw6'
    ];

    if (!file_exists($file)) {
        return $defaults;
    }

    $content = @file_get_contents($file);
    $data = json_decode($content, true);
    if (!is_array($data)) {
        return $defaults;
    }

    return array_merge($defaults, $data);
}

function get_setting($key, $default = '') {
    $settings = get_all_settings();
    return $settings[$key] ?? $default;
}

function save_settings($newData) {
    $file = __DIR__ . '/../data/settings.json';
    $current = get_all_settings();
    $merged = array_merge($current, $newData);
    return file_put_contents($file, json_encode($merged, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

$siteSettings = get_all_settings();
