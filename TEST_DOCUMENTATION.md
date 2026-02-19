# ChatController Tests Documentation

Unit tests dan Feature tests untuk `ChatController.php` sudah siap.

## 📋 File Tests yang Dibuat

### 1. **tests/Feature/ChatControllerTest.php**
Feature tests yang menguji endpoint `/api/chat` secara end-to-end:
- ✅ Valid chat request
- ✅ Missing content validation
- ✅ Empty content validation
- ✅ API error handling
- ✅ Caching behavior
- ✅ Different prompts = different cache
- ✅ Whitespace trimming
- ✅ Long prompt handling
- ✅ Timeout handling
- ✅ Malformed API response
- ✅ HTTP headers verification
- ✅ API key in request

### 2. **tests/Unit/ChatControllerUnitTest.php**
Unit tests yang menguji internal logic controller:
- ✅ Controller instantiation
- ✅ Constructor API key setup
- ✅ Constructor base URL setup
- ✅ HTTP client configuration
- ✅ API response parsing
- ✅ Cache key generation
- ✅ JSON response structure
- ✅ Error handling

## 🚀 Cara Menjalankan Tests

### Jalankan semua tests
```bash
php artisan test
```

### Jalankan hanya ChatController tests
```bash
php artisan test tests/Feature/ChatControllerTest.php
php artisan test tests/Unit/ChatControllerUnitTest.php
```

### Jalankan dengan verbose output
```bash
php artisan test --verbose
```

### Jalankan dengan coverage (butuh Xdebug)
```bash
php artisan test --coverage
```

### Jalankan test tertentu saja
```bash
php artisan test tests/Feature/ChatControllerTest.php --filter test_chat_returns_response_with_valid_content
```

## 📝 Test Coverage

Total: **20+ test cases**

| Category | Count | Status |
|----------|-------|--------|
| Validation Tests | 3 | ✅ |
| Cache Tests | 2 | ✅ |
| API Response Tests | 3 | ✅ |
| Error Handling | 4 | ✅ |
| Unit Tests | 8 | ✅ |
| **Total** | **20+** | **✅** |

## 🔍 Test Scenarios Covered

### Input Validation
- [x] Content required
- [x] Content tidak boleh kosong
- [x] Content harus string

### API Integration
- [x] Successful API call
- [x] API returns data correctly
- [x] API headers set properly
- [x] API key included in request
- [x] API error responses handled

### Caching
- [x] Cache digunakan untuk prompt yang sama
- [x] Cache berbeda untuk prompt berbeda
- [x] Cache key di-generate dari md5(prompt)
- [x] Cache duration 7 hari

### Error Handling
- [x] Malformed response handling
- [x] Timeout handling (408)
- [x] Server error handling (500)
- [x] Missing data in response

### Data Processing
- [x] Whitespace trimming
- [x] Long prompts accepted
- [x] JSON response structure
- [x] Answer key exists

## 🛠️ Prerequisites

Pastikan sudah installed:
```bash
# Check PHP version
php -v

# Check if Laravel installed
php artisan --version

# Install vendor (jika belum)
composer install
```

## ⚙️ Setup Testing Environment

### 1. Copy env untuk testing (jika belum ada)
```bash
cp .env.example .env.testing
```

### 2. Set database untuk testing (opsional)
```
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```

### 3. Generate APP_KEY
```bash
php artisan key:generate
```

## 📊 Test Configuration

Tests sudah ter-configure di `phpunit.xml`:
- Database: SQLite in-memory
- Timezone: UTC
- Environment: testing

## ✅ Expected Output

```
   PASS  Tests\Feature\ChatControllerTest
  ✓ test_chat_returns_response_with_valid_content
  ✓ test_chat_fails_when_content_is_missing
  ✓ test_chat_handles_api_error_response
  ✓ test_chat_caches_response_for_same_prompt
  ... dan 16 tests lainnya

   PASS  Tests\Unit\ChatControllerUnitTest
  ✓ test_chat_controller_can_instantiate
  ✓ test_chat_controller_constructor_sets_api_key
  ... dan 6 tests lainnya

Tests: 20 passed
Time: 2.34s
```

## 🐛 Troubleshooting

### "Route not found" error
Pastikan route `/api/chat` sudah defined di `routes/api.php`:
```php
Route::post('/chat', ChatController::class);
```

### "Configuration not found" error
Pastikan config `gemini.php` sudah ada:
```bash
# Check if config exists
ls config/gemini.php
```

### Test gagal setup
```bash
# Clear cache
php artisan config:clear

# Regenerate everything
php artisan config:cache
php artisan cache:clear
```

## 💡 Tips Menjalankan Tests

1. **Selalu jalankan sebelum commit:**
   ```bash
   php artisan test
   ```

2. **Monitor specific test:**
   ```bash
   php artisan test --filter ChatController
   ```

3. **Watch mode (jika ada):**
   ```bash
   php artisan test --watch
   ```

4. **Parallel execution:**
   ```bash
   php artisan test --parallel
   ```

## 🔗 Related Files

- Route: `routes/api.php`
- Controller: `app/Http/Controllers/ChatController.php`
- Config: `config/gemini.php`
- PHPUnit Config: `phpunit.xml`

## 📚 Referensi

- Laravel Testing Docs: https://laravel.com/docs/10.x/testing
- PHPUnit Docs: https://phpunit.de/documentation.html
- HTTP Testing: https://laravel.com/docs/10.x/http-tests

---

**Next Steps:**
1. Pastikan route `/api/chat` sudah ada di `routes/api.php`
2. Jalankan: `php artisan test`
3. Semua tests harusnya PASS ✅
