
<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $notes = $_POST['notes'];

    $stmt = $db->prepare("INSERT INTO income (type, amount, date, notes) VALUES (?, ?, ?, ?)");
    $stmt->execute([$type, $amount, $date, $notes]);
    header("Location: income.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $db->exec("DELETE FROM income WHERE id = $id");
    header("Location: income.php");
    exit;
}

$incomes = $db->query("SELECT * FROM income ORDER BY date DESC")->fetchAll(PDO::FETCH_ASSOC);
$total = $db->query("SELECT SUM(amount) FROM income")->fetchColumn() ?: 0;
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>الإيرادات - مزرعة أحمد</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <img src="logo.png" alt="شعار مزرعة أحمد" style="height:40px; vertical-align: middle;">
    <span style="margin-right: 10px;">مزرعة أحمد - إدارة الإيرادات</span>
</header>

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
        <label>نوع الإيراد:</label>
        <select name="type" required>
            <option>بيع</option>
            <option>منحة</option>
            <option>دخل آخر</option>
        </select>

        <label>المبلغ (ر.س):</label>
        <input type="number" name="amount" required>

        <label>التاريخ:</label>
        <input type="date" name="date" required>

        <label>ملاحظات:</label>
        <input type="text" name="notes">

        <button type="submit">حفظ الإيراد</button>
    </form>

    <h3>إجمالي الإيرادات: <?= number_format($total, 2) ?> ر.س</h3>

    <table>
        <tr>
            <th>النوع</th>
            <th>المبلغ</th>
            <th>التاريخ</th>
            <th>ملاحظات</th>
            <th>إجراء</th>
        </tr>
        <?php foreach ($incomes as $i): ?>
            <tr>
                <td><?= htmlspecialchars($i['type']) ?></td>
                <td><?= number_format($i['amount'], 2) ?></td>
                <td><?= $i['date'] ?></td>
                <td><?= htmlspecialchars($i['notes']) ?></td>
                <td><a href="?delete=<?= $i['id'] ?>" onclick="return confirm('هل أنت متأكد من الحذف؟')"><button class="delete">حذف</button></a></td>
            </tr>
        <?php endforeach; ?>
    </table>

</div>

</body>
</html>
