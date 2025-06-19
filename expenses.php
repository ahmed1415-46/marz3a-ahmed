<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
require 'db.php';

// إضافة مصروف جديد
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

// حذف مصروف
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $db->exec("DELETE FROM expenses WHERE id = $id");
    header("Location: expenses.php");
    exit;
}

// جلب المصروفات
$expenses = $db->query("SELECT * FROM expenses ORDER BY date DESC")->fetchAll(PDO::FETCH_ASSOC);
$total = $db->query("SELECT SUM(amount) FROM expenses")->fetchColumn() ?: 0;
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>مصروفات مزرعة أحمد</title>
    <style>
        body { font-family: Arial; padding: 30px; background: #f9f9f9; direction: rtl; }
        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: right; }
        form { margin-bottom: 20px; background: #fff; padding: 20px; border: 1px solid #ccc; }
        input, select { padding: 10px; width: 100%; margin-bottom: 10px; }
        button { background: green; color: white; border: none; padding: 10px; width: 100%; }
        a { color: red; text-decoration: none; }
    </style>
</head>
<body>
    <h1>إدارة المصروفات</h1>

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

    <h2>إجمالي المصروفات: <?= number_format($total, 2) ?> ر.س</h2>

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
                <td><a href="?delete=<?= $e['id'] ?>" onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="index.php" style="display:inline-block; margin-top:20px; background:#ddd; padding:10px 20px; text-decoration:none; color:black; border-radius:5px;">⬅️ رجوع للرئيسية</a>
</body>
</html>
