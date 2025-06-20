<?php
require 'db.php';

try {
    $db->exec("ALTER TABLE users ADD COLUMN reset_code TEXT");
    echo "✅ تم إضافة العمود reset_code بنجاح.";
} catch (PDOException $e) {
    echo "❌ خطأ: " . $e->getMessage();
}
