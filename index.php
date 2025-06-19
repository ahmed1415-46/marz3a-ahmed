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

// عدد الأغنام الحالي
$total_sheep = $db->query("SELECT COUNT(*) FROM sheep WHERE status != 'تم البيع'")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>مزرعة أحمد - الرئيسية</title>
    <style>
        body { font-family: Arial; background: #eef; padding: 30px; direction: rtl; }
        .box { background: #fff; border: 1px solid #ccc; padding: 20px; margin-bottom: 15px; }
        h2 { margin-top: 0; }
        a { margin-left: 10px; color: green; text-decoration: none; }
    </style>
</head>
<body>
    <h1>مرحباً بك في مزرعة أحمد</h1>
    
    <div class="box">
        <h2>إجمالي المصروفات: <?= number_format($total_expenses, 2) ?> ر.س</h2>
        <h2>إجمالي الإيرادات: <?= number_format($total_income, 2) ?> ر.س</h2>
        <h2>صافي الربح: <?= number_format($profit, 2) ?> ر.س</h2>
    </div>

    <div class="box">
        <h2>عدد الأغنام الحالي: <?= $total_sheep ?></h2>
    </div>

    <div class="box">
        <a href="expenses.php">📊 إدارة المصروفات</a>
        <a href="income.php">💰 إدارة الإيرادات</a>
        <a href="sheep.php">🐑 إدارة الأغنام</a>
        <a href="change_password.php">🔒 تغيير كلمة المرور</a>
        <a href="logout.php" style="color:red;">🚪 تسجيل الخروج</a>
    </div>
</body>
</html>
