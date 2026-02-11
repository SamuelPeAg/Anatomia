<?php
$path = __DIR__ . '/storage/test_symlink.txt';
if (file_exists($path)) {
    echo "Symlink works! Content: " . file_get_contents($path) . "\n";
} else {
    echo "Symlink failed. Path: $path\n";
}

$images = glob(__DIR__ . '/storage/informes/*/*/*');
if ($images) {
    echo "Found images:\n";
    foreach ($images as $img) {
        echo $img . "\n";
    }
} else {
    echo "No images found via glob.\n";
}
