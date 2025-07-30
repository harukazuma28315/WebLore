<?php
// File mẫu - không nên dùng trong thực tế nếu có thông tin nhạy cảm

$conn = new mysqli("localhost", "root", "", "webnovel");
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>
