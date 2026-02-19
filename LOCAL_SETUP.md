# Quick Start Guide - Local Testing dengan Docker

## Prerequisites
- Docker & Docker Compose installed
- Git repository configured

## Local Development Setup

### 1. Clone dan Setup Repository
```bash
git clone <your-repo-url>
cd ai-chatbot
cp .env.example .env
```

### 2. Build dan Run dengan Docker Compose
```bash
# Build image dan start containers
docker-compose up --build

# Atau di background
docker-compose up -d --build
```

### 3. Setup Database
```bash
# Run migrations
docker-compose exec app php artisan migrate

# (Optional) Run seeders
docker-compose exec app php artisan db:seed
```

### 4. Test Aplikasi
- Buka browser: http://localhost:8080
- Aplikasi seharusnya berjalan

### 5. Lihat Logs
```bash
# Semua services
docker-compose logs -f

# Specific service
docker-compose logs -f app
docker-compose logs -f db
```

### 6. Masuk ke Container
```bash
# Artisan tinker (debugging)
docker-compose exec app php artisan tinker

# Bash shell
docker-compose exec app sh
```

## Build Docker Image untuk Production

### 1. Build Image Lokal
```bash
docker build -t ai-chatbot:latest .
```

### 2. Test Image Lokal
```bash
docker run -p 8080:8080 \
  -e APP_ENV=production \
  -e APP_DEBUG=false \
  -e DB_CONNECTION=pgsql \
  -e DB_HOST=host.docker.internal \
  -e DB_PORT=5432 \
  -e DB_DATABASE=chatbot_db \
  -e DB_USERNAME=chatbot_user \
  -e DB_PASSWORD=chatbot_password \
  ai-chatbot:latest
```

## Deploy ke Render

### 1. Push ke GitHub
```bash
git add .
git commit -m "Add Docker configuration for Render deployment"
git push origin main
```

### 2. Connect Repository ke Render
- Login ke Render Dashboard (https://render.com)
- Klik "New +" → "Web Service"
- Connect GitHub repository

### 3. Configure Deployment
- Runtime: Docker
- Region: Singapore (atau pilihan lain)
- Instance Type: Free/Starter

### 4. Set Environment Variables
Klik "Environment" dan tambahkan:
```
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:xxx (generate: php artisan key:generate --show)
DB_CONNECTION=pgsql
DB_HOST=<from Render PostgreSQL>
DB_PORT=5432
DB_DATABASE=<db name>
DB_USERNAME=<db user>
DB_PASSWORD=<db password>
LOG_CHANNEL=stderr
```

### 5. Deploy
- Klik "Create Web Service" 
- Tunggu build dan deployment selesai

## Common Commands

```bash
# Stop containers
docker-compose down

# Remove containers dan volumes
docker-compose down -v

# Rebuild image
docker-compose build --no-cache

# Run multiple commands
docker-compose exec app sh -c "php artisan migrate && php artisan db:seed"

# View resource usage
docker stats
```

## Troubleshooting

### Port already in use
```bash
# Change port in docker-compose.yml
# Atau kill process:
lsof -i :8080
kill -9 <PID>
```

### Database connection failed
```bash
# Check if db container is running
docker-compose ps

# Check db logs
docker-compose logs db
```

### Container exits immediately
```bash
# Check startup logs
docker-compose logs app

# Run container interactively
docker-compose run app sh
```

## Notes

- Local setup dapat digunakan untuk testing sebelum production deployment
- Pastikan semua migrations berjalan sukses sebelum push ke Render
- Render akan auto-deploy setiap kali push ke main branch (jika sudah connected)

## Next Steps

1. ✅ Setup Docker locally dengan `docker-compose up`
2. ✅ Test aplikasi lokal: http://localhost:8080
3. ✅ Push ke GitHub
4. ✅ Deploy ke Render via dashboard
5. ✅ Monitor aplikasi di Render dashboard
