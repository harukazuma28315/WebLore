<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index/index.php?login=1");
    exit();
}

$user_id = $_SESSION['user_id'];

// Nếu là POST -> xử lý đổi mật khẩu
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password     = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($new_password !== $confirm_password) {
        $message = "❌ Mật khẩu mới và xác nhận không khớp!";
    } else {
        $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user || !password_verify($current_password, $user['password'])) {
            $message = "❌ Mật khẩu hiện tại không đúng!";
        } else {
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
            $stmt->bind_param("si", $hashed, $user_id);
            if ($stmt->execute()) {
                header("Location: profile.php?password_changed=1");
                exit();
            } else {
                $message = "❌ Có lỗi xảy ra khi cập nhật!";
            }
        }
    }
}
?>

<!-- Giao diện HTML form -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Đổi mật khẩu</title>
</head>
<body>
    <h2>Đổi mật khẩu</h2>

    <?php if (!empty($message)): ?>
        <p style="color:red;"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST" action="change_password.php">
        <label>Mật khẩu hiện tại:</label><br>
        <input type="password" name="current_password" required><br><br>

        <label>Mật khẩu mới:</label><br>
        <input type="password" name="new_password" required><br><br>

        <label>Xác nhận mật khẩu mới:</label><br>
        <input type="password" name="confirm_password" required><br><br>

        <button type="submit">Xác nhận đổi mật khẩu</button>
    </form>
</body>
</html>
