<?php

/**
 * PWA Icon Generator
 * 
 * Script untuk generate berbagai ukuran icon PWA dari logo.png
 * Jalankan: php artisan tinker < scripts/generate-pwa-icons.php
 * Atau: php scripts/generate-pwa-icons.php
 */

$basePath = __DIR__ . '/../public';
$sourceLogo = $basePath . '/images/logo.png';
$iconsPath = $basePath . '/images/icons';

// Ukuran icon yang diperlukan
$sizes = [72, 96, 128, 144, 152, 192, 384, 512];

// Pastikan folder icons ada
if (!is_dir($iconsPath)) {
    mkdir($iconsPath, 0755, true);
    echo "Created directory: $iconsPath\n";
}

// Check if GD library available
if (!extension_loaded('gd')) {
    echo "ERROR: GD library tidak tersedia. Install dengan: sudo apt-get install php-gd\n";
    echo "\nAlternatif: Generate icon manual menggunakan:\n";
    echo "- https://www.pwabuilder.com/imageGenerator\n";
    echo "- https://realfavicongenerator.net/\n";
    exit(1);
}

// Check source logo
if (!file_exists($sourceLogo)) {
    echo "ERROR: Logo tidak ditemukan di: $sourceLogo\n";
    echo "Pastikan file logo.png ada di folder public/images/\n";
    exit(1);
}

echo "Generating PWA icons from: $sourceLogo\n\n";

// Load source image
$sourceImage = imagecreatefrompng($sourceLogo);
if (!$sourceImage) {
    echo "ERROR: Gagal load gambar. Pastikan format PNG valid.\n";
    exit(1);
}

$sourceWidth = imagesx($sourceImage);
$sourceHeight = imagesy($sourceImage);

echo "Source image size: {$sourceWidth}x{$sourceHeight}\n\n";

// Generate icons
foreach ($sizes as $size) {
    $outputFile = "$iconsPath/icon-{$size}x{$size}.png";
    
    // Create new image dengan background putih/transparan
    $newImage = imagecreatetruecolor($size, $size);
    
    // Enable alpha blending
    imagealphablending($newImage, false);
    imagesavealpha($newImage, true);
    
    // Fill with transparent background
    $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
    imagefill($newImage, 0, 0, $transparent);
    
    // Calculate dimensions to maintain aspect ratio with padding
    $padding = $size * 0.1; // 10% padding
    $availableSize = $size - ($padding * 2);
    
    $ratio = min($availableSize / $sourceWidth, $availableSize / $sourceHeight);
    $newWidth = $sourceWidth * $ratio;
    $newHeight = $sourceHeight * $ratio;
    
    $x = ($size - $newWidth) / 2;
    $y = ($size - $newHeight) / 2;
    
    // Copy and resize
    imagecopyresampled(
        $newImage, $sourceImage,
        $x, $y, 0, 0,
        $newWidth, $newHeight,
        $sourceWidth, $sourceHeight
    );
    
    // Save
    if (imagepng($newImage, $outputFile, 9)) {
        echo "✓ Generated: icon-{$size}x{$size}.png\n";
    } else {
        echo "✗ Failed: icon-{$size}x{$size}.png\n";
    }
    
    imagedestroy($newImage);
}

// Cleanup
imagedestroy($sourceImage);

echo "\n✅ PWA icons generated successfully!\n";
echo "Icons saved to: $iconsPath\n";
