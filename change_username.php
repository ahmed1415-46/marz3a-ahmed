<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
require 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old_username = $_SESSION['user'];
    $new_username = trim($_POST['new_username']);

    if ($new_username === '') {
        $message = "❌ لا يمكن ترك اسم المستخدم الجديد فارغاً.";
    } else {
        $stmt = $db->prepare("UPDATE users SET username = ? WHERE username = ?");
        $stmt->execute([$new_username, $old_username]);
        $_SESSION['user'] = $new_username;
        $message = "✅ تم تغيير اسم المستخدم بنجاح إلى: " . htmlspecialchars($new_username);
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تغيير اسم المستخدم - مزرعة أحمد</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>👤 تغيير اسم المستخدم</header>

<nav>
    <a href="index.php">الرئيسية</a>
    <a href="expenses.php">المصروفات</a>
    <a href="income.php">الإيرادات</a>
    <a href="sheep.php">الأغنام</a>
    <a href="change_password.php">كلمة المرور</a>
    <a href="change_username.php">تغيير الاسم</a>
    <a href="logout.php" style="color:#ffc107;">خروج</a>
</nav>

<div class="container">

    <form method="post">
        <label>اسم المستخدم الجديد:</label>
        <input type="text" name="new_username" required>

        <button type="submit">تحديث الاسم</button>
    </form>

    <div class="msg"><?= $message ?></div>

</div>

</body>
</html>
