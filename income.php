<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
require 'db.php';

// إضافة إيراد
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

// حذف إيراد
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $db->exec("DELETE FROM income WHERE id = $id");
    header("Location: income.php");
    exit;
}

// جلب الإيرادات
$incomes = $db->query("SELECT * FROM income ORDER BY date DESC")->fetchAll(PDO::FETCH_ASSOC);
$total = $db->query("SELECT SUM(amount) FROM income")->fetchColumn() ?: 0;
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إيرادات مزرعة أحمد</title>
    <style>
        body { font-family: Arial; padding: 30px; background: #f0fff0; direction: rtl; }
        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: right; }
        form { margin-bottom: 20px; background: #fff; padding: 20px; border: 1px solid #ccc; }
        input, select { padding: 10px; width: 100%; margin-bottom: 10px; }
        button { background: green; color: white; border: none; padding: 10px; width: 100%; }
        a { color: red; text-decoration: none; }
    </style>
</head>
<body>
    <h1>إدارة الإيرادات</h1>

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

    <h2>إجمالي الإيرادات: <?= number_format($total, 2) ?> ر.س</h2>

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
                <td><a href="?delete=<?= $i['id'] ?>" onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
