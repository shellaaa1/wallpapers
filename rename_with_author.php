<?php
/**
 * 批量重命名图片，添加作者信息
 * 格式: 原文件名_QQ_昵称.扩展名
 */

$qq = '2840522462';
$nickname = '山海大玩家';
$directory = __DIR__ . '/fashion';

$extensions = ['jpg', 'jpeg', 'png', 'webp'];
$renamed = 0;
$skipped = 0;

echo "========================================\n";
echo "  批量添加作者信息\n";
echo "========================================\n\n";
echo "作者QQ: {$qq}\n";
echo "昵称: {$nickname}\n";
echo "目录: {$directory}\n\n";

foreach ($extensions as $ext) {
    $files = glob($directory . '/*.' . $ext);
    
    foreach ($files as $file) {
        $filename = basename($file);
        
        // 跳过已经包含作者信息的文件
        if (strpos($filename, $qq) !== false) {
            echo "⏭️  跳过: {$filename} (已包含作者信息)\n";
            $skipped++;
            continue;
        }
        
        // 构建新文件名
        $nameWithoutExt = pathinfo($filename, PATHINFO_FILENAME);
        $newFilename = "{$nameWithoutExt}_{$qq}_{$nickname}.{$ext}";
        $newPath = $directory . '/' . $newFilename;
        
        // 重命名
        if (rename($file, $newPath)) {
            echo "✅ {$filename} -> {$newFilename}\n";
            $renamed++;
        } else {
            echo "❌ 失败: {$filename}\n";
        }
    }
}

echo "\n========================================\n";
echo "  完成!\n";
echo "========================================\n";
echo "重命名: {$renamed} 个\n";
echo "跳过: {$skipped} 个\n";
echo "\n下一步: 运行 php sync-json.php --push 更新JSON并推送\n";
