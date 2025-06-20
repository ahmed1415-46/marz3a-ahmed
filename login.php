<?php
session_start();
require 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user['username'];
        header('Location: index.php');
        exit;
    } else {
        $error = 'اسم المستخدم أو كلمة المرور غير صحيحة';
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول - مزرعة أحمد</title>
    <style>
        body { font-family: Arial; background: #f9f9f9; padding: 50px; direction: rtl; }
        form { background: #fff; padding: 20px; max-width: 400px; margin: auto; border: 1px solid #ccc; }
        input { width: 100%; padding: 10px; margin: 10px 0; }
        button { padding: 10px 20px; background: green; color: white; border: none; }
        .error { color: red; }
    </style>
</head>
<body>
    <h2>تسجيل الدخول إلى مزرعة أحمد</h2>
    <form method="post">
        <label>اسم المستخدم:</label>
        <input type="text" name="username" required>
        <label>كلمة المرور:</label>
        <input type="password" name="password" required>
        <button type="submit">دخول</button>
        <br><br>
<a href="forgot_password.php">هل نسيت كلمة المرور؟</a>
        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
    </form>
</body>
</html>
