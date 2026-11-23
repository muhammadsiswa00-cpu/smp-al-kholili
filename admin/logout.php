<?php
$sessDir = __DIR__ . '/../data/sessions';
if (is_dir($sessDir) && is_writable($sessDir)) {
    session_save_path($sessDir);
}
@session_start();
$_SESSION = [];
@session_destroy();
header("Location: login.php");
exit;
