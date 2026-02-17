<?php
/**
 * Pterodactyl Custom Theme Uninstaller
 * 
 * Usage: php uninstall.php [pterodactyl-install-path]
 * 
 * Removes all theme customizations and restores defaults.
 */

echo "╔══════════════════════════════════╗\n";
echo "║   Theme Uninstaller v1.0        ║\n";
echo "╚══════════════════════════════════╝\n\n";

// Get install path
$config_path = __DIR__ . '/config.json';
if (file_exists($config_path)) {
    $config = json_decode(file_get_contents($config_path), true);
    $base = $config['pterodactyl_install_path'] ?? '/var/www/pterodactyl';
} else {
    $base = $argv[1] ?? '/var/www/pterodactyl';
}

if (!is_dir($base)) {
    die("ERROR: Pterodactyl not found at $base\n");
}

echo "Uninstalling theme from: $base\n\n";

// 1. Remove theme files
$files_removed = 0;
foreach (['custom-theme.css', 'custom-bg.jpg'] as $f) {
    $path = "$base/public/themes/$f";
    if (file_exists($path)) {
        unlink($path);
        echo "[✓] Removed $f\n";
        $files_removed++;
    }
}

// 2. Restore core.blade.php
$core = "$base/resources/views/templates/base/core.blade.php";
if (file_exists($core)) {
    $cc = file_get_contents($core);
    $cc = str_replace('bg-transparent', 'bg-neutral-800', $cc);
    file_put_contents($core, $cc);
    echo "[✓] Restored bg-neutral-800 in core.blade.php\n";
}

// 3. Remove CSS injection from wrapper.blade.php
$wrapper = "$base/resources/views/templates/wrapper.blade.php";
if (file_exists($wrapper)) {
    $wc = file_get_contents($wrapper);
    // Remove the injected style and link tags
    $wc = preg_replace('/<style>html,body.*?<\/style>\s*/s', '', $wc);
    $wc = preg_replace('/<link[^>]*custom-theme\.css[^>]*>\s*/s', '', $wc);
    file_put_contents($wrapper, $wc);
    echo "[✓] Removed CSS injection from wrapper.blade.php\n";
}

// 4. Clear caches
exec("cd $base && php artisan view:clear && php artisan cache:clear 2>&1");
echo "[✓] Caches cleared\n";

echo "\n[✓] Uninstall complete! Panel restored to default.\n";
echo "    Hard-refresh your browser (Ctrl+Shift+R) to see changes.\n";
?>
