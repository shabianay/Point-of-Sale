<?php
$host = '127.0.0.1';
$db = 'pos_app';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT id, name, image FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $storageDir = __DIR__ . '/storage/app/public/products/';
    if (!is_dir($storageDir)) {
        mkdir($storageDir, 0755, true);
    }

    $updated = 0;
    foreach ($products as $product) {
        $productName = $product['name'];
        $seed = urlencode($productName . ' food drink'); // Use product name for consistent seed
        $photoUrl = "https://picsum.photos/seed/{$seed}/400/400"; // Reliable generic images

        echo "[{$product['id']}] {$productName} -> fetching {$photoUrl}... ";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $photoUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For HTTPS, might need CA cert if true
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $imageData = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($imageData && $httpCode === 200 && strlen($imageData) > 1000) {
            // Delete old image if exists
            if ($product['image'] && file_exists(__DIR__ . '/storage/app/public/' . $product['image'])) {
                unlink(__DIR__ . '/storage/app/public/' . $product['image']);
            }

            $filename = 'products/' . uniqid() . '.jpg';
            $filePath = __DIR__ . '/storage/app/public/' . $filename;
            file_put_contents($filePath, $imageData);

            $updateStmt = $pdo->prepare("UPDATE products SET image = ? WHERE id = ?");
            $updateStmt->execute([$filename, $product['id']]);

            echo "OK -> {$filename}\n";
            $updated++;
        } else {
            echo "FAILED (HTTP: {$httpCode}, size: " . strlen($imageData ?? '') . ")\n";
        }

        usleep(500000); // Small delay to avoid rate limiting
    }

    echo "\nSelesai! {$updated} produk berhasil ditambahkan/diperbarui fotonya.\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
