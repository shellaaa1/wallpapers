<?php
/**
 * 批量生成缩略图
 */

$config = require '/storage/emulated/0/ZeroTermux/开发/github-wallpaper-site/admin-tools/config/config.php';

$categories = ['fashion', 'pet', 'landscape', 'erciyuan'];
$thumbWidth = 400;
$quality = 85;

foreach ($categories as $category) {
    $sourceDir = __DIR__ . '/' . $category;
    $thumbDir = __DIR__ . '/thumbnails/' . $category;
    
    if (!is_dir($sourceDir)) continue;
    if (!is_dir($thumbDir)) mkdir($thumbDir, 0755, true);
    
    $files = glob($sourceDir . '/*.{jpg,jpeg,png,webp}', GLOB_BRACE);
    
    echo "【{$category}】找到 " . count($files) . " 张图片\n";
    
    foreach ($files as $file) {
        $filename = basename($file);
        $thumbPath = $thumbDir . '/' . $filename;
        
        // 如果缩略图已存在则跳过
        if (file_exists($thumbPath)) {
            continue;
        }
        
        // 生成缩略图
        list($width, $height) = getimagesize($file);
        $thumbHeight = intval($height * ($thumbWidth / $width));
        
        $source = createImageFromFile($file);
        if (!$source) continue;
        
        $thumb = imagecreatetruecolor($thumbWidth, $thumbHeight);
        imagealphablending($thumb, false);
        imagesavealpha($thumb, true);
        
        imagecopyresampled($thumb, $source, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $width, $height);
        
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        switch ($ext) {
            case 'jpg':
            case 'jpeg':
                imagejpeg($thumb, $thumbPath, $quality);
                break;
            case 'png':
                imagepng($thumb, $thumbPath, intval($quality / 10));
                break;
            case 'webp':
                imagewebp($thumb, $thumbPath, $quality);
                break;
        }
        
        imagedestroy($source);
        imagedestroy($thumb);
        
        echo "  ✅ {$filename}\n";
    }
}

echo "\n完成！\n";

function createImageFromFile($filepath) {
    $ext = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));
    switch ($ext) {
        case 'jpg':
        case 'jpeg':
            return imagecreatefromjpeg($filepath);
        case 'png':
            return imagecreatefrompng($filepath);
        case 'webp':
            return imagecreatefromwebp($filepath);
        default:
            return null;
    }
}
