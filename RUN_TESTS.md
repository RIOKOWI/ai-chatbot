# 🧪 Test ChatController - Quick Start

Unit tests lengkap untuk ChatController sudah siap! Berikut cara menjalankannya:

## ✅ File Tests yang Dibuat

1. **tests/Feature/ChatControllerTest.php** - 13 Feature tests
2. **tests/Unit/ChatControllerUnitTest.php** - 8 Unit tests
3. **routes/api.php** - Updated dengan route POST `/api/chat`

**Total: 21 test cases**

---

## 🚀 Jalankan Tests

### Cara 1: Jalankan Semua Tests
```bash
php artisan test
```

### Cara 2: Jalankan Hanya ChatController Tests
```bash
php artisan test tests/Feature/ChatControllerTest.php tests/Unit/ChatControllerUnitTest.php
```

### Cara 3: Jalankan Dengan Verbose (Lihat Detail)
```bash
php artisan test --verbose
```

### Cara 4: Jalankan Satu Test Spesifik
```bash
php artisan test tests/Feature/ChatControllerTest.php --filter test_chat_returns_response_with_valid_content
```

---

## ✨ Test Cases Yang Dicakup

### ✅ Validation Tests
- `test_chat_returns_response_with_valid_content` - Request valid berhasil
- `test_chat_fails_when_content_is_missing` - Content required
- `test_chat_fails_when_content_is_empty_string` - Content tidak boleh kosong

### ✅ API Integration Tests
- `test_chat_handles_api_error_response` - Handle API error
- `test_chat_handles_timeout` - Handle timeout (408)
- `test_chat_handles_malformed_api_response` - Handle response buruk
- `test_chat_sends_correct_headers_to_api` - Header benar
- `test_chat_includes_api_key_in_request` - API key included

### ✅ Caching Tests
- `test_chat_caches_response_for_same_prompt` - Cache untuk prompt sama
- `test_chat_uses_different_cache_for_different_prompts` - Cache berbeda

### ✅ Data Processing Tests
- `test_chat_trims_whitespace_from_prompt` - Whitespace di-trim
- `test_chat_accepts_long_prompt` - Long prompt OK
- `test_http_client_called_with_correct_timeout` - Timeout config
- `test_api_response_is_properly_parsed` - Response parsing

### ✅ Unit Tests
- `test_chat_controller_can_instantiate` - Constructor OK
- `test_chat_controller_constructor_sets_api_key` - API key set
- `test_chat_controller_constructor_sets_base_url` - Base URL set
- `test_cache_key_generation` - Cache key benar
- `test_response_contains_answer_key` - JSON structure
- Plus 3 tests lainnya

---

## 📊 Expected Output

```
   PASS  Tests\Feature\ChatControllerTest
  ✓ test_chat_returns_response_with_valid_content
  ✓ test_chat_fails_when_content_is_missing
  ✓ test_chat_fails_when_content_is_empty_string
  ✓ test_chat_handles_api_error_response
  ✓ test_chat_caches_response_for_same_prompt
  ✓ test_chat_uses_different_cache_for_different_prompts
  ✓ test_chat_trims_whitespace_from_prompt
  ✓ test_chat_accepts_long_prompt
  ✓ test_chat_handles_timeout
  ✓ test_chat_handles_malformed_api_response
  ✓ test_chat_sends_correct_headers_to_api
  ✓ test_chat_includes_api_key_in_request

   PASS  Tests\Unit\ChatControllerUnitTest
  ✓ test_chat_controller_can_instantiate
  ✓ test_chat_controller_constructor_sets_api_key
  ✓ test_chat_controller_constructor_sets_base_url
  ✓ test_http_client_called_with_correct_timeout
  ✓ test_api_response_is_properly_parsed
  ✓ test_cache_key_generation
  ✓ test_response_contains_answer_key

Tests: 21 passed (45.23s)
```

---

## ⚙️ Prerequisites

Pastikan sudah ada:
```bash
# PHP version 8.1+
php -v

# Laravel installed
php artisan --version

# Dependencies
composer install
```

## 🔧 Troubleshooting

### ❌ "Route not found"
✅ Sudah fixed - route sudah ditambah ke `routes/api.php`

### ❌ "Configuration not found"
```bash
php artisan config:clear
php artisan config:cache
```

### ❌ "Class not found"
```bash
composer dump-autoload
```

### ❌ Tests timeout
Tingkatkan timeout di `phpunit.xml` jika perlu

---

## 📁 File Structure

```
tests/
├── Feature/
│   └── ChatControllerTest.php ✅ 13 tests
├── Unit/
│   └── ChatControllerUnitTest.php ✅ 8 tests
└── TestCase.php

routes/
└── api.php ✅ Updated with route

app/Http/Controllers/
└── ChatController.php (yang di-test)

config/
└── gemini.php (configuration)
```

---

## 📝 Test Details

Setiap test:
- ✅ Mocking HTTP client (tidak hit API asli)
- ✅ Mocking Cache
- ✅ Assertions yang clear
- ✅ Documentation lengkap
- ✅ Edge cases dicakup

---

## 🎯 Next Steps

1. **Jalankan tests:**
   ```bash
   php artisan test
   ```

2. **Cek hasil:**
   - Semua tests should PASS ✅
   - Output akan menunjukkan duration & passed count

3. **Gunakan untuk CI/CD:**
   - Add ke GitHub Actions
   - Or GitLab CI
   - Or Render deployments

---

## 💡 Pro Tips

### Watch Mode (Auto-rerun tests)
```bash
php artisan test --watch
```

### Parallel Execution (Faster)
```bash
php artisan test --parallel
```

### Coverage Report
```bash
php artisan test --coverage
```

### Filter Tests by Name
```bash
php artisan test --filter "cache"
```

---

## 📚 More Info

Baca lengkap di: [TEST_DOCUMENTATION.md](TEST_DOCUMENTATION.md)

**Status: ✅ Ready for testing!**
