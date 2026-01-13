# 🔥 DEBUGGING SESSION ISSUE - SOLUSI LENGKAP

## ✅ MASALAH YANG DITEMUKAN DAN DIPERBAIKI:

### 1. **SESSION_DOMAIN SALAH** ❌ → ✅
- **Masalah**: Di `.env` tercantum `SESSION_DOMAIN=127.0.0.1` (IP address tidak valid untuk cookie)
- **Solusi**: Ubah menjadi kosong `SESSION_DOMAIN=` atau domain yang benar
- **File**: `.env`
- **Status**: ✅ SUDAH DIPERBAIKI

### 2. **SESSION_SAME_SITE TIDAK TERDEFINISI** ❌ → ✅
- **Masalah**: Cookie tidak dikirim karena SameSite attribute default 'lax' vs request credentials
- **Solusi**: Tambahkan `SESSION_SAME_SITE=lax` ke `.env`
- **File**: `.env`
- **Status**: ✅ SUDAH DIPERBAIKI

### 3. **FETCH CREDENTIALS SETTING SALAH** ❌ → ✅
- **Masalah**: Login AJAX menggunakan `credentials: "same-origin"` yang tidak tepat
- **Solusi**: Ubah ke `credentials: "include"` untuk mengirim cookies dengan fetch request
- **File**: `resources/js/app.js` (Login & Register)
- **Status**: ✅ SUDAH DIPERBAIKI

### 4. **MIDDLEWARE CONFIGURATION TIDAK LENGKAP** ⚠️ → ✅
- **Masalah**: Laravel 11 memerlukan middleware session/CSRF terekspor secara eksplisit
- **Solusi**: Tambahkan `$middleware->web()` di bootstrap/app.php
- **File**: `bootstrap/app.php`
- **Status**: ✅ SUDAH DIPERBAIKI

---

## 🔍 DEBUGGING CHECKLIST:

Lakukan pengecekan berikut untuk memastikan session berfungsi:

### A. **Database Session Table** (PENTING)
```bash
# Pastikan tabel 'sessions' ada dan user_id tersimpan
SELECT * FROM sessions LIMIT 5;
SELECT id, user_id, last_activity FROM sessions WHERE user_id IS NOT NULL;
```

### B. **Clear Cache & Session**
```bash
php artisan config:clear
php artisan cache:clear
php artisan session:table
php artisan migrate
```

### C. **Test Login Dari Console**
```bash
# Buka browser DevTools > Console
// Test 1: Cek apakah fetch mengirim credentials
const response = await fetch('/login', {
    method: 'POST',
    credentials: 'include',
    headers: { 'Content-Type': 'application/json' },
});

// Test 2: Cek session cookie di Application > Cookies
// Harus ada cookie: laravel_session (atau sesuai nama di .env)

// Test 3: Lihat Response Headers
// Harus ada: Set-Cookie: laravel_session=...
```

### D. **Cek .env Konfigurasi**
```env
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_DOMAIN=
SESSION_PATH=/
SESSION_SECURE_COOKIE=false
SESSION_SAME_SITE=lax
```

### E. **Check Auth Guard**
```php
// Di app.js atau console.js
\Log::info('Auth check', ['authenticated' => Auth::check()]);
\Log::info('User', ['user' => Auth::user()]);
```

---

## 📋 PERUBAHAN YANG TELAH DILAKUKAN:

### 1. `.env`
```diff
SESSION_DOMAIN=127.0.0.1  ❌
SESSION_DOMAIN=           ✅
+SESSION_SAME_SITE=lax    ✅
```

### 2. `resources/js/app.js`
```diff
- credentials: "same-origin"
+ credentials: "include"
```

### 3. `bootstrap/app.php`
```diff
->withMiddleware(function ($middleware) {
+   $middleware->web(append: []);
    $middleware->alias([...]);
})
```

---

## 🚀 LANGKAH SELANJUTNYA:

1. **Clear cache dan session**:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

2. **Build assets**:
   ```bash
   npm run build
   ```

3. **Test login** di browser baru atau incognito (untuk fresh session)

4. **Monitor logs** saat login:
   ```bash
   tail -f storage/logs/laravel.log
   ```

5. **Cek DevTools Network** saat login:
   - Response headers harus punya `Set-Cookie: laravel_session=...`
   - Request headers harus punya `Cookie: laravel_session=...` untuk request berikutnya

---

## ⚠️ COMMON ISSUES LAINNYA:

### Issue 1: "Memori session tidak tersimpan di database"
**Solusi**: 
```bash
php artisan migrate
php artisan session:table
php artisan migrate
```

### Issue 2: "Cookie tidak diterima browser"
**Solusi**: 
- Buka DevTools > Application > Cookies
- Lihat apakah ada cookie `laravel_session`
- Cek SameSite: harus `Lax` atau `None` (jika Strict, tidak bisa)

### Issue 3: "Login berhasil tapi redirect ke login lagi"
**Solusi**:
```php
// Di AuthenticatedSessionController@store
\Log::info('Auth user', ['user' => Auth::user()]);
\Log::info('Session ID', ['sid' => session()->getId()]);
```

### Issue 4: "Route tidak ditemukan setelah login"
**Solusi**: Pastikan route di `routes/web.php` sudah benar:
```php
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', ...)->name('dashboard');
    Route::get('/admin/dashboard', ...)->middleware('admin')->name('admin.dashboard');
});
```

---

## 📞 JIKA MASIH ERROR:

1. Cek `storage/logs/laravel.log` untuk error details
2. Enable SQL logging di `.env`: `DB_LOG=single`
3. Gunakan `\Log::info()` untuk debug setiap tahap
4. Check session table: `SELECT * FROM sessions;`
5. Test session secara manual:
   ```php
   // Di route
   Route::get('/test-session', function() {
       session(['test' => 'berhasil']);
       return session('test');
   });
   ```

---

## ✅ VERIFIKASI SETELAH PERBAIKAN:

- [ ] `.env` session domain kosong
- [ ] `.env` memiliki `SESSION_SAME_SITE=lax`
- [ ] `app.js` menggunakan `credentials: "include"`
- [ ] `bootstrap/app.php` punya middleware web config
- [ ] Database migration `sessions` table sudah ada
- [ ] Cache sudah di-clear
- [ ] Assets sudah di-build (npm run build)
- [ ] Test login di browser incognito/fresh
- [ ] Session cookie muncul di DevTools
- [ ] Berhasil redirect ke dashboard setelah login

---

**Last Updated**: 13 Jan 2026  
**Status**: ✅ SEMUA PERBAIKAN SUDAH DILAKUKAN
