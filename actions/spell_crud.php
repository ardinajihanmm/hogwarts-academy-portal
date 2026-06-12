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
    $name = trim($_POST['spell_name'] ?? '');
    $type = $_POST['type'] ?? 'Charm';
    $diff = $_POST['difficulty'] ?? 'Beginner';
    $xp   = (int)($_POST['xp_reward'] ?? 50);
    $desc = trim($_POST['description'] ?? '');
    if (!$name) { echo json_encode(['success'=>false,'message'=>'Nama spell wajib diisi']); exit; }
    $db->prepare('INSERT INTO spells (spell_name,type,difficulty,xp_reward,description,created_at) VALUES (?,?,?,?,?,NOW())')
       ->execute([$name,$type,$diff,$xp,$desc]);
    echo json_encode(['success'=>true,'message'=>'Spell berhasil ditambahkan']);

  } elseif ($action === 'edit') {
    $id   = (int)($_POST['id'] ?? 0);
    $name = trim($_POST['spell_name'] ?? '');
    $type = $_POST['type'] ?? 'Charm';
    $diff = $_POST['difficulty'] ?? 'Beginner';
    $xp   = (int)($_POST['xp_reward'] ?? 50);
    $desc = trim($_POST['description'] ?? '');
    if (!$id || !$name) { echo json_encode(['success'=>false,'message'=>'Field wajib diisi']); exit; }
    $db->prepare('UPDATE spells SET spell_name=?,type=?,difficulty=?,xp_reward=?,description=? WHERE id=?')
       ->execute([$name,$type,$diff,$xp,$desc,$id]);
    echo json_encode(['success'=>true,'message'=>'Spell berhasil diupdate']);

  } elseif ($action === 'delete') {
    $id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);
    if (!$id) { echo json_encode(['success'=>false,'message'=>'ID tidak valid']); exit; }
    $db->prepare('DELETE FROM spells WHERE id=?')->execute([$id]);
    echo json_encode(['success'=>true,'message'=>'Spell berhasil dihapus']);

  } elseif ($action === 'get') {
    $id   = (int)($_GET['id'] ?? 0);
    $stmt = $db->prepare('SELECT * FROM spells WHERE id=?');
    $stmt->execute([$id]);
    echo json_encode($stmt->fetch() ?: ['error'=>'Not found']);

  } else {
    echo json_encode(['success'=>false,'message'=>'Unknown action']);
  }
} catch (Exception $e) {
  error_log($e->getMessage());
  echo json_encode(['success'=>false,'message'=>'Server error']);
}