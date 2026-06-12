<?php

session_start();
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  echo json_encode(['success'=>false,'message'=>'Unauthorized']); exit;
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
  $db = getDB();

  if ($action === 'add') {
    $u = trim($_POST['username'] ?? '');
    $e = trim($_POST['email'] ?? '');
    $p = $_POST['password'] ?? '';
    $h = $_POST['house'] ?? '';
    $r = $_POST['role'] ?? 'student';
    if (!$u || !$e || !$p) { echo json_encode(['success'=>false,'message'=>'Field wajib diisi']); exit; }
    $chk = $db->prepare('SELECT id FROM users WHERE email=? OR username=?');
    $chk->execute([$e,$u]);
    if ($chk->fetch()) { echo json_encode(['success'=>false,'message'=>'Username/email sudah ada']); exit; }
    $house = in_array($h,['Gryffindor','Slytherin','Ravenclaw','Hufflepuff']) ? $h : null;
    $db->prepare('INSERT INTO users (username,email,password,role,house,xp,level,created_at) VALUES (?,?,?,?,?,0,\'Beginner Wizard\',NOW())')
       ->execute([$u,$e,password_hash($p,PASSWORD_BCRYPT),$r,$house]);
    echo json_encode(['success'=>true,'message'=>'Student berhasil ditambahkan']);

  } elseif ($action === 'edit') {
    $id = (int)($_POST['id'] ?? 0);
    $u  = trim($_POST['username'] ?? '');
    $e  = trim($_POST['email'] ?? '');
    $p  = $_POST['password'] ?? '';
    $h  = $_POST['house'] ?? '';
    $r  = $_POST['role'] ?? 'student';
    if (!$id || !$u || !$e) { echo json_encode(['success'=>false,'message'=>'Field wajib diisi']); exit; }
    $house = in_array($h,['Gryffindor','Slytherin','Ravenclaw','Hufflepuff']) ? $h : null;
    if ($p) {
      $db->prepare('UPDATE users SET username=?,email=?,password=?,role=?,house=? WHERE id=?')
         ->execute([$u,$e,password_hash($p,PASSWORD_BCRYPT),$r,$house,$id]);
    } else {
      $db->prepare('UPDATE users SET username=?,email=?,role=?,house=? WHERE id=?')
         ->execute([$u,$e,$r,$house,$id]);
    }
    echo json_encode(['success'=>true,'message'=>'Student berhasil diupdate']);

  } elseif ($action === 'delete') {
    $id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);
    if (!$id || $id === (int)$_SESSION['user_id']) {
      echo json_encode(['success'=>false,'message'=>'Tidak bisa hapus diri sendiri']); exit;
    }
    $db->prepare('DELETE FROM users WHERE id=?')->execute([$id]);
    echo json_encode(['success'=>true,'message'=>'Student berhasil dihapus']);

  } elseif ($action === 'get') {
    $id   = (int)($_GET['id'] ?? 0);
    $stmt = $db->prepare('SELECT id,username,email,role,house,xp,level FROM users WHERE id=?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    echo json_encode($row ?: ['error'=>'Not found']);

  } else {
    echo json_encode(['success'=>false,'message'=>'Unknown action']);
  }
} catch (Exception $e) {
  error_log($e->getMessage());
  echo json_encode(['success'=>false,'message'=>'Server error: '.$e->getMessage()]);
}