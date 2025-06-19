<?php
// اتصال بقاعدة البيانات SQLite
$db = new PDO('sqlite:project.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// إنشاء جدول المستخدمين
$db->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE,
    password TEXT
)");

// إنشاء مستخدم افتراضي إذا لم يوجد
$stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE username = 'admin'");
if ($stmt->execute() && $stmt->fetchColumn() == 0) {
    $hashed = password_hash('admin123', PASSWORD_DEFAULT);
    $db->exec("INSERT INTO users (username, password) VALUES ('admin', '$hashed')");
}

// إنشاء جدول المصروفات
$db->exec("CREATE TABLE IF NOT EXISTS expenses (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    type TEXT,
    amount REAL,
    date TEXT,
    notes TEXT
)");

// إنشاء جدول الإيرادات
$db->exec("CREATE TABLE IF NOT EXISTS income (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    type TEXT,
    amount REAL,
    date TEXT,
    notes TEXT
)");

// إنشاء جدول الأغنام
$db->exec("CREATE TABLE IF NOT EXISTS sheep (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    status TEXT,
    date TEXT,
    notes TEXT
)");
?>
