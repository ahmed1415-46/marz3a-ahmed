
<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'];
    $date = $_POST['date'];
    $notes = $_POST['notes'];

    $stmt = $db->prepare("INSERT INTO sheep (status, date, notes) VALUES (?, ?, ?)");
    $stmt->execute([$status, $date, $notes]);
    header("Location: sheep.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $db->exec("DELETE FROM sheep WHERE id = $id");
    header("Location: sheep.php");
    exit;
}

$sheep = $db->query("SELECT * FROM sheep ORDER BY date DESC")->fetchAll(PDO::FETCH_ASSOC);
$total = $db->query("SELECT COUNT(*) FROM sheep WHERE status != 'تم البيع'")->fetchColumn() ?: 0;
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>الأغنام - مزرعة أحمد</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <img src="logo.png" alt="شعار مزرعة أحمد" style="height:40px; vertical-align: middle;">
    <span style="margin-right: 10px;">مزرعة أحمد - إدارة الأغنام</span>
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

    <h3>عدد الأغنام الحالي: <?= $total ?></h3>

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
                <td><a href="?delete=<?= $s['id'] ?>" onclick="return confirm('هل أنت متأكد من الحذف؟')"><button class="delete">حذف</button></a></td>
            </tr>
        <?php endforeach; ?>
    </table>

</div>

</body>
</html>
