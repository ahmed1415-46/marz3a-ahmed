<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
require 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current'];
    $new = $_POST['new'];
    $confirm = $_POST['confirm'];

    $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$_SESSION['user']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($current, $user['password'])) {
        if ($new === $confirm) {
            $hashed = password_hash($new, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE users SET password = ? WHERE username = ?");
            $stmt->execute([$hashed, $_SESSION['user']]);
            $message = "✅ تم تغيير كلمة المرور بنجاح.";
        } else {
            $message = "❌ كلمة المرور الجديدة غير متطابقة.";
        }
    } else {
        $message = "❌ كلمة المرور الحالية غير صحيحة.";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تغيير كلمة المرور - مزرعة أحمد</title>
    <style>
        body { font-family: Arial; padding: 30px; background: #f0f0f0; direction: rtl; }
        form { background: #fff; padding: 20px; border: 1px solid #ccc; max-width: 400px; margin: auto; }
        input { width: 100%; padding: 10px; margin-bottom: 10px; }
        button { background: green; color: white; border: none; padding: 10px; width: 100%; }
        .msg { text-align: center; color: darkred; font-weight: bold; }
    </style>
</head>
<body>
    <h2>🔒 تغيير كلمة المرور</h2>
    <form method="post">
        <label>كلمة المرور الحالية:</label>
        <input type="password" name="current" required>

        <label>كلمة المرور الجديدة:</label>
        <input type="password" name="new" required>

        <label>تأكيد كلمة المرور:</label>
        <input type="password" name="confirm" required>

        <button type="submit">تحديث</button>
    </form>
    <div class="msg"><?= $message ?></div>
    <a href="index.php" style="display:inline-block; margin-top:20px; background:#ddd; padding:10px 20px; text-decoration:none; color:black; border-radius:5px;">⬅️ رجوع للرئيسية</a>
</body>
</html>
