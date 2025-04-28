<?php
require_once 'config.php';
$file = $filePath; // 使用从 config.php 中获取的文件路径
if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'. basename($file). '"');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: '. filesize($file));
    ob_clean();
    flush();
    readfile($file);
    exit;
} else {
    echo "文件不存在";
}
?>
