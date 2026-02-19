# Panduan Deploy ke Render

## Prasyarat
1. Akun Render (https://render.com)
2. Repository Git yang sudah di-push ke GitHub/GitLab
3. Render CLI (opsional untuk local testing)

## Langkah-langkah Deploy

### 1. Setup Database (PostgreSQL)
- Login ke Render Dashboard
- Buat PostgreSQL database baru
- Simpan kredensial: Host, User, Password, Database name

### 2. Deploy via Dashboard

#### Opsi A: Menggunakan render.yaml
1. Push `render.yaml` ke repository Anda
2. Di Render Dashboard, klik "New +"
3. Pilih "Blueprint"
4. Connect repository Anda
5. Render akan auto-detect `render.yaml`

#### Opsi B: Manual Setup
1. Di Render Dashboard, klik "New +" → "Web Service"
2. Connect ke repository Git Anda
3. Isi informasi:
   - **Name**: `ai-chatbot`
   - **Runtime**: Docker
   - **Build Command**: (kosongkan, Docker akan handle)
   - **Start Command**: (kosongkan, Docker akan handle)
   - **Region**: Singapore atau pilihan Anda

### 3. Environment Variables
Di Render Dashboard, set environment variables berikut:

```
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:xxxxx (generate dengan: php artisan key:generate --show)
LOG_CHANNEL=stderr
DB_CONNECTION=pgsql
DB_HOST=your-postgres-host-from-render
DB_PORT=5432
DB_DATABASE=your-db-name
DB_USERNAME=your-db-user
DB_PASSWORD=your-db-password
```

### 4. Generate APP_KEY
Jalankan di local:
```bash
php artisan key:generate --show
```
Copy nilai yang ditampilkan (tanpa `base64:`) dan paste ke Render Dashboard

### 5. Deploy
1. Push perubahan ke Git
2. Render akan auto-trigger deployment
3. Pantau di "Logs" untuk melihat progress
4. Tunggu sampai status menjadi "Live"

## File-file yang Telah Dibuat

- **Dockerfile**: Multi-stage build untuk optimasi ukuran image
- **docker/nginx.conf**: Konfigurasi Nginx server
- **docker/app.conf**: Virtual host untuk Laravel app
- **docker/supervisord.conf**: Process manager untuk menjalankan PHP-FPM, Nginx, dan queue worker
- **docker/start.sh**: Startup script untuk migrasi DB dan start services
- **render.yaml**: Konfigurasi deployment untuk Render
- **.dockerignore**: File/folder yang tidak perlu di-include dalam Docker image

## Troubleshooting

### Jika deployment gagal:
1. Cek "Deploy Logs" di Render Dashboard
2. Pastikan APP_KEY sudah ter-set
3. Pastikan database credentials sudah benar
4. Cek apakah migrations berjalan dengan sempurna

### Jika aplikasi loading lambat:
- Gunakan minimal instance (Render Standard untuk production)
- Pastikan database sudah optimal
- Setup caching (Redis jika tersedia)

### Memory atau CPU issue:
- Upgrade Render plan
- Optimize Laravel code (lazy loading, indexing, dll)

## Optimasi Lanjutan

### 1. Enable Auto-deploy
- Settings → Deploy Hook
- Setup auto-deploy saat push ke main branch

### 2. Setup Domain Custom
- Settings → Custom Domain
- Tambahkan domain Anda

### 3. SSL/TLS
- Automatically enabled oleh Render (gratis)

### 4. Queue Worker
- Supervisord sudah configured
- Pastikan QUEUE_CONNECTION di .env sesuai (default: sync, ubah ke database/redis)

## Tips

1. **Untuk development**: Gunakan plan Free Render (ada batasan resources)
2. **Untuk production**: Gunakan plan Starter atau lebih tinggi
3. **Database**: Jika data critical, jangan gunakan Free tier (auto-sleep)
4. **Backup**: Setup regular backup di Render PostgreSQL settings

## Next Steps

1. Setup `.env.example` dengan semua variable yang diperlukan
2. Pastikan semua migrations up-to-date
3. Test deployment di staging terlebih dahulu
4. Monitor aplikasi setelah live

Untuk pertanyaan lebih lanjut, buka dokumentasi Render: https://render.com/docs
