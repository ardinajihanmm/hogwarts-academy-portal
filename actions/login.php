<?php
session_start();
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(['success'=>false,'message'=>'Invalid request']); exit;
}

$identifier = trim($_POST['identifier'] ?? '');
$password   = $_POST['password'] ?? '';

if (!$identifier || !$password) {
  echo json_encode(['success'=>false,'message'=>'Username/email dan password wajib diisi']); exit;
}

try {
  $db   = getDB();
  $stmt = $db->prepare('SELECT * FROM users WHERE email=? OR username=? LIMIT 1');
  $stmt->execute([$identifier, $identifier]);
  $user = $stmt->fetch();

  if (!$user || !password_verify($password, $user['password'])) {
    echo json_encode(['success'=>false,'message'=>'Username/email atau password salah']); exit;
  }

  // Stats
  $stat = $db->prepare('SELECT COUNT(DISTINCT course_id) c, COUNT(DISTINCT spell_id) s FROM progress WHERE user_id=?');
  $stat->execute([$user['id']]);
  $stats = $stat->fetch();

  session_regenerate_id(true);
  $_SESSION = [
    'user_id'          => $user['id'],
    'username'         => $user['username'],
    'email'            => $user['email'],
    'role'             => $user['role'],
    'house'            => $user['house'],
    'xp'               => (int)$user['xp'],
    'level'            => $user['level'],
    'photo_url'        => $user['photo_url'] ?? '',
    'created_at'       => $user['created_at'],
    'courses_explored' => (int)($stats['c'] ?? 0),
    'spells_learned'   => (int)($stats['s'] ?? 0),
  ];

  define('BASE_URL', '/hogwarts-academy-portal');
  $redirect = $user['role'] === 'admin' ? BASE_URL . '/pages/admin/dashboard.php' : BASE_URL . '/pages/student/dashboard.php';
  echo json_encode(['success'=>true,'redirect'=>$redirect]);

} catch (Exception $e) {
  error_log($e->getMessage());
  echo json_encode(['success'=>false,'message'=>'Server error']);
}
