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

    $stmt = $db->prepare("INSERT INTO expenses (type, amount, date, notes) VALUES (?, ?, ?, ?)");
    $stmt->execute([$type, $amount, $date, $notes]);
    header("Location: expenses.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $db->exec("DELETE FROM expenses WHERE id = $id");
    header("Location: expenses.php");
    exit;
}

$expenses = $db->query("SELECT * FROM expenses ORDER BY date DESC")->fetchAll(PDO::FETCH_ASSOC);
$total = $db->query("SELECT SUM(amount) FROM expenses")->fetchColumn() ?: 0;
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>المصروفات - مزرعة أحمد</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>💸 إدارة المصروفات</header>

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
        <label>نوع المصروف:</label>
        <select name="type" required>
            <option>علف</option>
            <option>دواء</option>
            <option>أجرة عمال</option>
            <option>صيانة</option>
            <option>نقل</option>
        </select>

        <label>المبلغ (ر.س):</label>
        <input type="number" name="amount" required>

        <label>التاريخ:</label>
        <input type="date" name="date" required>

        <label>ملاحظات:</label>
        <input type="text" name="notes">

        <button type="submit">حفظ المصروف</button>
    </form>

    <h3>إجمالي المصروفات: <?= number_format($total, 2) ?> ر.س</h3>

    <table>
        <tr>
            <th>النوع</th>
            <th>المبلغ</th>
            <th>التاريخ</th>
            <th>ملاحظات</th>
            <th>إجراء</th>
        </tr>
        <?php foreach ($expenses as $e): ?>
            <tr>
                <td><?= htmlspecialchars($e['type']) ?></td>
                <td><?= number_format($e['amount'], 2) ?></td>
                <td><?= $e['date'] ?></td>
                <td><?= htmlspecialchars($e['notes']) ?></td>
                <td><a href="?delete=<?= $e['id'] ?>" onclick="return confirm('هل أنت متأكد من الحذف؟')"><button class="delete">حذف</button></a></td>
            </tr>
        <?php endforeach; ?>
    </table>

</div>

</body>
</html>
