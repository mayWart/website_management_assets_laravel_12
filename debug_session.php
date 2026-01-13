#!/usr/bin/env php
<?php

/**
 * DEBUGGING SESSION KONFIGURASI
 * 
 * Script ini membantu verify bahwa session sudah dikonfigurasi dengan benar
 * Jalankan: php app/debug_session.php
 */

echo "\n========================================\n";
echo "   SESSION CONFIGURATION CHECK\n";
echo "========================================\n\n";

// Check 1: .env configuration
echo "1️⃣  CHECKING .ENV CONFIGURATION\n";
echo "   - SESSION_DRIVER: " . getenv('SESSION_DRIVER') . "\n";
echo "   - SESSION_DOMAIN: " . (getenv('SESSION_DOMAIN') ?: '(empty - OK)') . "\n";
echo "   - SESSION_SAME_SITE: " . getenv('SESSION_SAME_SITE') . "\n";
echo "   - SESSION_SECURE_COOKIE: " . getenv('SESSION_SECURE_COOKIE') . "\n";
echo "   ✅ Configuration loaded\n\n";

// Check 2: Sessions table exists
echo "2️⃣  CHECKING DATABASE TABLES\n";
$tables = [
    'users' => 'Users table',
    'sessions' => 'Sessions table',
    'pegawai' => 'Pegawai table'
];

foreach ($tables as $table => $label) {
    echo "   - $label: ";
    // Simple check - note: ini akan error jika DB tidak terhubung
    // Tapi kita cukup check file migration
    $migration_files = glob(__DIR__ . '/../database/migrations/*.php');
    $found = false;
    foreach ($migration_files as $file) {
        if (strpos($file, $table) !== false) {
            $found = true;
            break;
        }
    }
    echo ($found ? "✅ Found migration\n" : "❌ Migration not found\n");
}
echo "\n";

// Check 3: Config files
echo "3️⃣  CHECKING CONFIGURATION FILES\n";
echo "   - config/session.php: " . (file_exists(__DIR__ . '/../config/session.php') ? "✅" : "❌") . "\n";
echo "   - config/auth.php: " . (file_exists(__DIR__ . '/../config/auth.php') ? "✅" : "❌") . "\n";
echo "   - bootstrap/app.php: " . (file_exists(__DIR__ . '/../bootstrap/app.php') ? "✅" : "❌") . "\n";
echo "\n";

// Check 4: Auth guard configuration
echo "4️⃣  CHECKING AUTH GUARD\n";
$auth_config = require __DIR__ . '/../config/auth.php';
echo "   - Guard: " . ($auth_config['defaults']['guard'] ?? 'web') . "\n";
echo "   - Provider: " . ($auth_config['defaults']['passwords'] ?? 'users') . "\n";
echo "   - User Model: " . ($auth_config['providers']['users']['model'] ?? 'App\\Models\\User') . "\n";
echo "   - Auth Column: " . ($auth_config['providers']['users']['column'] ?? 'username') . "\n";
echo "\n";

// Check 5: Middleware
echo "5️⃣  CHECKING MIDDLEWARE\n";
$app_config = file_get_contents(__DIR__ . '/../bootstrap/app.php');
echo "   - Middleware.web() configured: " . (strpos($app_config, 'middleware->web') !== false ? "✅" : "⚠️") . "\n";
echo "   - Admin middleware: " . (file_exists(__DIR__ . '/../app/Http/Middleware/IsAdmin.php') ? "✅" : "❌") . "\n";
echo "   - EnsurePegawaiExists middleware: " . (file_exists(__DIR__ . '/../app/Http/Middleware/EnsurePegawaiExists.php') ? "✅" : "❌") . "\n";
echo "\n";

// Check 6: JavaScript files
echo "6️⃣  CHECKING JAVASCRIPT FILES\n";
$app_js = file_get_contents(__DIR__ . '/../resources/js/app.js');
echo "   - credentials: 'include' (Login): " . (strpos($app_js, 'credentials: "include"') !== false ? "✅" : "❌ (MASALAH!)") . "\n";
echo "   - credentials: 'include' (Register): " . (substr_count($app_js, 'credentials: "include"') >= 2 ? "✅" : "❌ (MASALAH!)") . "\n";
echo "\n";

// Check 7: Routes
echo "7️⃣  CHECKING ROUTES\n";
$routes = file_get_contents(__DIR__ . '/../routes/web.php');
echo "   - Login POST route: " . (strpos($routes, "Route::post('/login'") !== false ? "✅" : "❌") . "\n";
echo "   - Dashboard route with auth: " . (strpos($routes, "Route::middleware('auth')") !== false ? "✅" : "❌") . "\n";
echo "   - Admin dashboard route: " . (strpos($routes, "'/admin/dashboard'") !== false ? "✅" : "❌") . "\n";
echo "\n";

echo "========================================\n";
echo "✅ Configuration check completed!\n";
echo "========================================\n\n";

echo "📋 NEXT STEPS:\n";
echo "1. Run: php artisan migrate (if not done)\n";
echo "2. Run: php artisan config:clear\n";
echo "3. Run: php artisan cache:clear\n";
echo "4. Run: npm run build\n";
echo "5. Test login in browser (incognito mode recommended)\n";
echo "6. Check DevTools > Application > Cookies for 'laravel_session'\n";
echo "\n";
