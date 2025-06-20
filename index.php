<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
require 'db.php';

// حساب الإجماليات
$total_expenses = $db->query("SELECT SUM(amount) FROM expenses")->fetchColumn() ?: 0;
$total_income = $db->query("SELECT SUM(amount) FROM income")->fetchColumn() ?: 0;
$profit = $total_income - $total_expenses;

// عدد الأغنام
$total_sheep = $db->query("SELECT COUNT(*) FROM sheep WHERE status != 'تم البيع'")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>مزرعة أحمد</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>📊 مزرعة أحمد - لوحة التحكم</header>

    <nav>
        <a href="index.php">الرئيسية</a>
        <a href="expenses.php">المصروفات</a>
        <a href="income.php">الإيرادات</a>
        <a href="sheep.php">الأغنام</a>
        <a href="change_password.php">كلمة المرور</a>
        <a href="logout.php" style="color:#ffc107;">خروج</a>
    </nav>

    <div class="container">
        <h2>مرحباً بك يا <?= $_SESSION['user'] ?> 👋</h2>

        <div class="box">
            <h3>إجمالي المصروفات: <?= number_format($total_expenses, 2) ?> ر.س</h3>
            <h3>إجمالي الإيرادات: <?= number_format($total_income, 2) ?> ر.س</h3>
            <h3>صافي الربح: <?= number_format($profit, 2) ?> ر.س</h3>
        </div>

        <div class="box">
            <h3>عدد الأغنام الحالي: <?= $total_sheep ?></h3>
        </div>
    </div>

</body>
</html>
