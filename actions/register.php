<?php
require_once __DIR__ . '/../config/auth.php';   // ✅ ganti session_start()
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']); exit;
}

$username = trim($_POST['username'] ?? '');
$email    = trim($_POST['email']    ?? '');
$password = $_POST['password']      ?? '';
$confirm  = $_POST['confirm_password'] ?? '';
$house    = $_POST['house']         ?? '';  // ✅ pastikan form kirim 'house'

$valid_houses = ['Gryffindor', 'Slytherin', 'Ravenclaw', 'Hufflepuff'];
$errors = [];

if (strlen($username) < 3)                          $errors[] = 'Username minimal 3 karakter';
if (!filter_var($email, FILTER_VALIDATE_EMAIL))     $errors[] = 'Email tidak valid';
if (strlen($password) < 6)                          $errors[] = 'Password minimal 6 karakter';
if ($password !== $confirm)                         $errors[] = 'Password tidak cocok';
if (!in_array($house, $valid_houses))               $errors[] = 'Pilih house yang valid';

if ($errors) {
    echo json_encode(['success' => false, 'message' => implode('. ', $errors)]); exit;
}

try {
    $db  = getDB();
    $chk = $db->prepare('SELECT id FROM users WHERE email = ? OR username = ?');
    $chk->execute([$email, $username]);

    if ($chk->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Username atau email sudah digunakan']); exit;
    }

    $stmt = $db->prepare(
        'INSERT INTO users (username, email, password, role, house, xp, level, created_at)
         VALUES (?, ?, ?, \'student\', ?, 0, \'Beginner Wizard\', NOW())'
    );
    $stmt->execute([
        $username,
        $email,
        password_hash($password, PASSWORD_BCRYPT),
        $house
    ]);

    echo json_encode(['success' => true, 'message' => 'Registrasi berhasil!']);

} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Server error']);
}