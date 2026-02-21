<?php
// Cek apakah APP_KEY sudah di-set
if (empty($_ENV['APP_KEY']) || $_ENV['APP_KEY'] === '') {
    // Generate APP_KEY jika kosong
    require __DIR__ . '/../vendor/autoload.php';
    $key = 'base64:' . base64_encode(random_bytes(32));
    file_put_contents('/tmp/.env.key', $key);
}
echo "OK";
