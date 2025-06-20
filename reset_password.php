<?php
require 'db.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $code = $_POST['code'];
    $new_pass = $_POST['new_password'];
    $confirm = $_POST['confirm'];

    if ($new_pass !== $confirm) {
        $message = "❌ كلمة المرور غير متطابقة.";
    } else {
        // التحقق من الرمز واسم المستخدم
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ? AND reset_code = ?");
        $stmt->execute([$username, $code]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // تحديث كلمة المرور ومسح رمز التعيين
            $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE users SET password = ?, reset_code = NULL WHERE username = ?");
            $stmt->execute([$hashed, $username]);

            $message = "✅ تم تغيير كلمة المرور بنجاح. يمكنك الآن تسجيل الدخول.";
        } else {
            $message = "❌ الرمز أو اسم المستخدم غير صحيح.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إعادة تعيين كلمة المرور</title>
    <style>
        body { font-family: Arial; background: #f0f8ff; padding: 30px; text-align: center; direction: rtl; }
        form { display: inline-block; background: #fff; padding: 20px; border: 1px solid #ccc; }
        input { width: 100%; padding: 10px; margin-bottom: 10px; }
        button { padding: 10px 20px; background: green; color: white; border: none; }
        .msg { margin-top: 15px; color: #333; }
    </style>
</head>
<body>
    <h2>إعادة تعيين كلمة المرور</h2>
    <form method="post">
        <label>اسم المستخدم:</label><br>
        <input type="text" name="username" required>

        <label>رمز إعادة التعيين:</label><br>
        <input type="text" name="code" required>

        <label>كلمة المرور الجديدة:</label><br>
        <input type="password" name="new_password" required>

        <label>تأكيد كلمة المرور:</label><br>
        <input type="password" name="confirm" required>

        <button type="submit">تحديث كلمة المرور</button>
    </form>
    <div class="msg"><?= $message ?></div>
    <br><br>
    <a href="login.php">⬅️ الرجوع لتسجيل الدخول</a>
</body>
</html>
