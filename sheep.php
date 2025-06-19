<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
require 'db.php';

// إضافة سجل غنم
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'];
    $date = $_POST['date'];
    $notes = $_POST['notes'];

    $stmt = $db->prepare("INSERT INTO sheep (status, date, notes) VALUES (?, ?, ?)");
    $stmt->execute([$status, $date, $notes]);
    header("Location: sheep.php");
    exit;
}

// حذف سجل
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $db->exec("DELETE FROM sheep WHERE id = $id");
    header("Location: sheep.php");
    exit;
}

// جلب السجلات
$sheep = $db->query("SELECT * FROM sheep ORDER BY date DESC")->fetchAll(PDO::FETCH_ASSOC);
$total = $db->query("SELECT COUNT(*) FROM sheep WHERE status != 'تم البيع'")->fetchColumn() ?: 0;
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة الأغنام - مزرعة أحمد</title>
    <style>
        body { font-family: Arial; padding: 30px; background: #fffdf0; direction: rtl; }
        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: right; }
        form { margin-bottom: 20px; background: #fff; padding: 20px; border: 1px solid #ccc; }
        input, select { padding: 10px; width: 100%; margin-bottom: 10px; }
        button { background: green; color: white; border: none; padding: 10px; width: 100%; }
        a { color: red; text-decoration: none; }
    </style>
</head>
<body>
    <h1>إدارة الأغنام</h1>

    <form method="post">
        <label>الحالة:</label>
        <select name="status" required>
            <option>تم الشراء</option>
            <option>تم البيع</option>
            <option>نفق</option>
            <option>مولود جديد</option>
        </select>

        <label>التاريخ:</label>
        <input type="date" name="date" required>

        <label>ملاحظات:</label>
        <input type="text" name="notes">

        <button type="submit">حفظ السجل</button>
    </form>

    <h2>عدد الأغنام الحالي: <?= $total ?></h2>

    <table>
        <tr>
            <th>الحالة</th>
            <th>التاريخ</th>
            <th>ملاحظات</th>
            <th>إجراء</th>
        </tr>
        <?php foreach ($sheep as $s): ?>
            <tr>
                <td><?= htmlspecialchars($s['status']) ?></td>
                <td><?= $s['date'] ?></td>
                <td><?= htmlspecialchars($s['notes']) ?></td>
                <td><a href="?delete=<?= $s['id'] ?>" onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
