<?php
/**
 * Pterodactyl Custom Theme Installer
 * 
 * Usage: php install.php [path-to-config.json]
 * 
 * This script:
 * 1. Reads config.json for your customization preferences
 * 2. Downloads your background image
 * 3. Generates the final CSS from the template
 * 4. Injects the CSS link + anti-flash styles into the panel
 * 5. Sets body class to bg-transparent
 * 6. Disables forced 2FA
 * 7. Unlocks admin settings panel
 * 8. Clears all caches
 */

echo "╔══════════════════════════════════════════════╗\n";
echo "║   Pterodactyl Custom Theme Installer v1.0    ║\n";
echo "╚══════════════════════════════════════════════╝\n\n";

// ─── Load Config ─────────────────────────────────────
$config_path = $argv[1] ?? __DIR__ . '/config.json';
if (!file_exists($config_path)) {
    die("ERROR: Config file not found at $config_path\n");
}

$config = json_decode(file_get_contents($config_path), true);
if (!$config) {
    die("ERROR: Invalid JSON in config file\n");
}

$base = rtrim($config['pterodactyl_install_path'], '/');
if (!is_dir($base)) {
    die("ERROR: Pterodactyl not found at $base\n");
}

echo "Panel path:     $base\n";
echo "Panel name:     {$config['panel_name']}\n";
echo "Welcome msg:    {$config['welcome_message']}\n";
echo "Flower:         {$config['flower_name']}\n";
echo "Primary color:  {$config['primary_color']}\n\n";

// ─── 1. Create themes directory ──────────────────────
$themes_dir = "$base/public/themes";
if (!is_dir($themes_dir)) {
    mkdir($themes_dir, 0755, true);
    echo "[✓] Created themes directory\n";
} else {
    echo "[•] Themes directory exists\n";
}

// ─── 2. Download background image ────────────────────
$bg_url = $config['background_image_url'];
$bg_file = "$themes_dir/custom-bg.jpg";
echo "[…] Downloading background image...\n";
exec("curl -L -o '$bg_file' '$bg_url' 2>&1", $dl_out, $dl_ret);
if ($dl_ret === 0 && file_exists($bg_file) && filesize($bg_file) > 1000) {
    chmod($bg_file, 0644);
    echo "[✓] Background downloaded: " . number_format(filesize($bg_file)) . " bytes\n";
} else {
    echo "[✗] WARNING: Background download failed. Theme will use solid color fallback.\n";
}

// ─── 3. Generate CSS from template ───────────────────
$template_path = __DIR__ . '/theme.css';
if (!file_exists($template_path)) {
    die("ERROR: theme.css template not found\n");
}

$css = file_get_contents($template_path);
$replacements = [
    '{{HEADING_FONT}}'       => $config['heading_font'],
    '{{BODY_FONT}}'          => $config['body_font'],
    '{{BG_IMAGE}}'           => '/themes/custom-bg.jpg',
    '{{PRIMARY_COLOR}}'      => $config['primary_color'],
    '{{ACCENT_COLOR}}'       => $config['accent_color'],
    '{{GLOW_COLOR}}'         => $config['glow_color'],
    '{{GLASS_BG}}'           => $config['glass_bg'],
    '{{GLASS_BORDER}}'       => $config['glass_border'],
    '{{FLOWER_EMOJI}}'       => $config['flower_emoji'],
    '{{BTN_GRADIENT_START}}' => $config['button_gradient_start'],
    '{{BTN_GRADIENT_END}}'   => $config['button_gradient_end'],
    '{{BTN_TEXT_COLOR}}'     => $config['button_text_color'],
];

foreach ($replacements as $key => $value) {
    $css = str_replace($key, $value, $css);
}

file_put_contents("$themes_dir/custom-theme.css", $css);
echo "[✓] CSS generated: " . number_format(strlen($css)) . " bytes\n";

// ─── 4. Fix core.blade.php (bg-transparent) ──────────
$core = "$base/resources/views/templates/base/core.blade.php";
if (file_exists($core)) {
    $cc = file_get_contents($core);
    if (strpos($cc, 'bg-neutral-800') !== false) {
        $cc = str_replace('bg-neutral-800', 'bg-transparent', $cc);
        file_put_contents($core, $cc);
        echo "[✓] core.blade.php: bg-transparent\n";
    } else {
        echo "[•] core.blade.php: already transparent\n";
    }
}

// ─── 5. Inject CSS into wrapper.blade.php ────────────
$wrapper = "$base/resources/views/templates/wrapper.blade.php";
if (file_exists($wrapper)) {
    $wc = file_get_contents($wrapper);
    
    if (strpos($wc, 'custom-theme.css') === false) {
        $inline = '<style>html,body,#app,#app>div,div[class*="bg-"]{background:transparent!important}body{background:#0a0a0a url(/themes/custom-bg.jpg) no-repeat center center fixed!important;background-size:cover!important}</style>';
        $link = '<link rel="stylesheet" href="/themes/custom-theme.css">';
        
        if (strpos($wc, '</head>') !== false) {
            $wc = str_replace('</head>', "    $inline\n    $link\n    </head>", $wc);
            file_put_contents($wrapper, $wc);
            echo "[✓] Injected CSS + anti-flash into wrapper.blade.php\n";
        } else {
            echo "[✗] WARNING: Could not find </head> in wrapper.blade.php\n";
        }
    } else {
        echo "[•] CSS already injected in wrapper\n";
    }
} else {
    echo "[✗] WARNING: wrapper.blade.php not found at $wrapper\n";
}

// ─── 6. Unlock admin settings ────────────────────────
$env_file = "$base/.env";
if (file_exists($env_file)) {
    $env = file_get_contents($env_file);
    if (strpos($env, 'APP_ENVIRONMENT_ONLY=true') !== false) {
        $env = str_replace('APP_ENVIRONMENT_ONLY=true', 'APP_ENVIRONMENT_ONLY=false', $env);
        file_put_contents($env_file, $env);
        echo "[✓] .env: APP_ENVIRONMENT_ONLY=false\n";
    } else {
        echo "[•] .env: already unlocked\n";
    }
}

// ─── 7. Disable forced 2FA ──────────────────────────
exec("cd $base && php artisan tinker --execute=\"DB::table('settings')->updateOrInsert(['key' => 'settings::pterodactyl:auth:2fa_required'], ['value' => '0']);\" 2>&1", $tfa_out);
echo "[✓] 2FA requirement disabled\n";

// ─── 8. Clear caches ────────────────────────────────
exec("cd $base && php artisan view:clear && php artisan cache:clear 2>&1");
echo "[✓] All caches cleared\n";

// ─── Done ────────────────────────────────────────────
echo "\n╔══════════════════════════════════════════════╗\n";
echo "║         INSTALLATION COMPLETE! 🎉            ║\n";
echo "╠══════════════════════════════════════════════╣\n";
echo "║  Next steps:                                 ║\n";
echo "║  1. Open Admin Panel → Settings              ║\n";
echo "║  2. Change panel name to your preference     ║\n";
echo "║  3. Hard-refresh your browser (Ctrl+Shift+R) ║\n";
echo "╚══════════════════════════════════════════════╝\n";
?>
