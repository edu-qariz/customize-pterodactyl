# 🎨 Customize Pterodactyl Panel

A one-click theme installer for [Pterodactyl Panel](https://pterodactyl.io/). Transform your panel with custom branding, glass effects, animated text, and more — no coding required.

![License](https://img.shields.io/badge/license-MIT-green)
![Version](https://img.shields.io/badge/version-1.0-blue)

## ✨ Features

- 🖼️ **Custom background image** — any URL or local file
- 🌸 **Custom flower emoji** — hibiscus, sunflower, rose, cherry blossom, or any emoji
- 🎨 **Custom color scheme** — primary, accent, glass, button colors
- ✍️ **Custom fonts** — heading + body fonts from Google Fonts
- 🪟 **Glassmorphism** — frosted glass containers with blur effects
- ⚡ **Glitch animation** — animated headings with neon glow
- 💡 **Glowing labels** — animated form labels
- 📱 **Mobile-optimized** — responsive console and layouts
- 🔒 **2FA unlock** — disables forced two-factor requirement
- 🧹 **Clean uninstall** — fully reversible, restores defaults

## 📁 Files

| File | Description |
|------|-------------|
| `config.json` | Your customization settings (edit this!) |
| `theme.css` | CSS template with `{{VARIABLES}}` |
| `install.php` | One-click installer script |
| `uninstall.php` | Removes theme, restores defaults |

## 🚀 Quick Start

### 1. Clone this repo on your server

```bash
cd /root
git clone https://github.com/edu-qariz/customize-pterodactyl.git
cd customize-pterodactyl
```

### 2. Edit `config.json`

```json
{
    "panel_name": "My Hosting",
    "welcome_message": "WELCOME TO MY HOSTING",
    "copyright": "© MyCompany 2025-2026",
    "flower_emoji": "\\01F33A",
    "flower_name": "hibiscus",
    "background_image_url": "https://example.com/my-bg.jpg",
    "primary_color": "#ff6b8a",
    "accent_color": "#ff3366",
    "glow_color": "rgba(255, 100, 150, 0.8)",
    "glass_bg": "rgba(20, 10, 30, 0.5)",
    "glass_border": "rgba(255, 100, 150, 0.15)",
    "button_gradient_start": "#ff3366",
    "button_gradient_end": "#ff6b8a",
    "button_text_color": "#ffffff",
    "heading_font": "Creepster",
    "body_font": "Outfit",
    "pterodactyl_install_path": "/var/www/pterodactyl"
}
```

### 3. Run the installer

```bash
php install.php
```

### 4. Change panel name

After installing, go to **Admin Panel → Settings** and change the panel name.

## 🌸 Popular Flower Emojis

| Emoji | Code | Name |
|-------|------|------|
| 🌺 | `\\01F33A` | Hibiscus |
| 🌻 | `\\01F33B` | Sunflower |
| 🌹 | `\\01F339` | Rose |
| 🌸 | `\\01F338` | Cherry Blossom |
| 🌷 | `\\01F337` | Tulip |
| 🌼 | `\\01F33C` | Blossom |
| 💐 | `\\01F490` | Bouquet |
| 🏵️ | `\\01F3F5` | Rosette |

## 🎨 Example Color Schemes

### Pink/Red (Tyrex Style)
```json
"primary_color": "#ff6b8a",
"accent_color": "#ff3366",
"glass_bg": "rgba(20, 10, 30, 0.5)",
"glass_border": "rgba(255, 100, 150, 0.15)"
```

### Gold/Yellow (Charles Style)
```json
"primary_color": "#ffd700",
"accent_color": "#ffa500",
"glass_bg": "rgba(30, 20, 5, 0.5)",
"glass_border": "rgba(255, 215, 0, 0.15)"
```

### Cyan/Blue
```json
"primary_color": "#00d4ff",
"accent_color": "#0099cc",
"glass_bg": "rgba(5, 15, 30, 0.5)",
"glass_border": "rgba(0, 212, 255, 0.15)"
```

### Purple/Violet
```json
"primary_color": "#a855f7",
"accent_color": "#7c3aed",
"glass_bg": "rgba(20, 10, 40, 0.5)",
"glass_border": "rgba(168, 85, 247, 0.15)"
```

### Green/Emerald
```json
"primary_color": "#10b981",
"accent_color": "#059669",
"glass_bg": "rgba(5, 20, 15, 0.5)",
"glass_border": "rgba(16, 185, 129, 0.15)"
```

## 🗑️ Uninstall

To remove the theme and restore defaults:

```bash
php uninstall.php
```

## 📋 Requirements

- Pterodactyl Panel v1.x
- PHP 7.4+
- `curl` (for background image download)
- SSH/root access to your server

## 📄 License

MIT License — use freely, modify freely, share freely.

## 🙏 Credits

Made with ❤️ by [edu-qariz](https://github.com/edu-qariz)
