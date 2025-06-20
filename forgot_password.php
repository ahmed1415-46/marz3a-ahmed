<?php
require 'db.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];

    $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // إنشاء رمز إعادة تعيين عشوائي
        $code = substr(md5(uniqid()), 0, 6);

        // حفظ الرمز في قاعدة البيانات
        $stmt = $db->prepare("UPDATE users SET reset_code = ? WHERE username = ?");
        $stmt->execute([$code, $username]);

        $message = "🔐 رمز إعادة التعيين الخاص بك هو: <strong>$code</strong><br>احتفظ به واستخدمه في صفحة إعادة التعيين.";
    } else {
        $message = "❌ اسم المستخدم غير موجود.";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>نسيت كلمة المرور</title>
    <style>
        body { font-family: Arial; background: #f9f9f9; padding: 30px; text-align: center; direction: rtl; }
        form { display: inline-block; background: #fff; padding: 20px; border: 1px solid #ccc; }
        input { width: 100%; padding: 10px; margin-bottom: 10px; }
        button { padding: 10px 20px; background: green; color: white; border: none; }
        .msg { margin-top: 15px; color: #333; }
    </style>
</head>
<body>
    <h2>نسيت كلمة المرور؟</h2>
    <form method="post">
        <label>ادخل اسم المستخدم:</label><br>
        <input type="text" name="username" required>
        <button type="submit">توليد رمز إعادة التعيين</button>
    </form>
    <div class="msg"><?= $message ?></div>
    <br><br>
    <a href="login.php">⬅️ الرجوع لتسجيل الدخول</a>
</body>
</html>
