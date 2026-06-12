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
    $name = trim($_POST['course_name'] ?? '');
    $prof = trim($_POST['professor'] ?? '');
    $diff = $_POST['difficulty'] ?? 'Beginner';
    $xp   = (int)($_POST['xp_reward'] ?? 200);
    $desc = trim($_POST['description'] ?? '');
    if (!$name || !$prof) { echo json_encode(['success'=>false,'message'=>'Nama dan profesor wajib diisi']); exit; }
    $db->prepare('INSERT INTO courses (course_name,professor,difficulty,xp_reward,description,created_at) VALUES (?,?,?,?,?,NOW())')
       ->execute([$name,$prof,$diff,$xp,$desc]);
    echo json_encode(['success'=>true,'message'=>'Course berhasil ditambahkan']);

  } elseif ($action === 'edit') {
    $id   = (int)($_POST['id'] ?? 0);
    $name = trim($_POST['course_name'] ?? '');
    $prof = trim($_POST['professor'] ?? '');
    $diff = $_POST['difficulty'] ?? 'Beginner';
    $xp   = (int)($_POST['xp_reward'] ?? 200);
    $desc = trim($_POST['description'] ?? '');
    if (!$id || !$name || !$prof) { echo json_encode(['success'=>false,'message'=>'Field wajib diisi']); exit; }
    $db->prepare('UPDATE courses SET course_name=?,professor=?,difficulty=?,xp_reward=?,description=? WHERE id=?')
       ->execute([$name,$prof,$diff,$xp,$desc,$id]);
    echo json_encode(['success'=>true,'message'=>'Course berhasil diupdate']);

  } elseif ($action === 'delete') {
    $id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);
    if (!$id) { echo json_encode(['success'=>false,'message'=>'ID tidak valid']); exit; }
    $db->prepare('DELETE FROM courses WHERE id=?')->execute([$id]);
    echo json_encode(['success'=>true,'message'=>'Course berhasil dihapus']);

  } elseif ($action === 'get') {
    $id   = (int)($_GET['id'] ?? 0);
    $stmt = $db->prepare('SELECT * FROM courses WHERE id=?');
    $stmt->execute([$id]);
    echo json_encode($stmt->fetch() ?: ['error'=>'Not found']);

  } else {
    echo json_encode(['success'=>false,'message'=>'Unknown action']);
  }
} catch (Exception $e) {
  error_log($e->getMessage());
  echo json_encode(['success'=>false,'message'=>'Server error']);
}