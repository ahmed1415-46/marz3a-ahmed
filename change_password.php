
<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
require 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_SESSION['user'];
    $current = $_POST['current'];
    $new = $_POST['new'];
    $confirm = $_POST['confirm'];

    $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($current, $user['password'])) {
        if ($new === $confirm) {
            $new_hashed = password_hash($new, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE users SET password = ? WHERE username = ?");
            $stmt->execute([$new_hashed, $username]);
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
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>🔐 تغيير كلمة المرور</header>

<nav>
    <a href="index.php">الرئيسية</a>
    <a href="expenses.php">المصروفات</a>
    <a href="income.php">الإيرادات</a>
    <a href="sheep.php">الأغنام</a>
    <a href="change_password.php">كلمة المرور</a>
    <a href="logout.php" style="color:#ffc107;">خروج</a>
</nav>

<div class="container">

    <form method="post">
        <label>كلمة المرور الحالية:</label>
        <input type="password" name="current" required>

        <label>كلمة المرور الجديدة:</label>
        <input type="password" name="new" required>

        <label>تأكيد كلمة المرور الجديدة:</label>
        <input type="password" name="confirm" required>

        <button type="submit">تحديث كلمة المرور</button>
    </form>

    <div class="msg"><?= $message ?></div>

</div>

</body>
</html>
