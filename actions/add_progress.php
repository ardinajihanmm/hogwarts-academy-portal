<?php
session_start();
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

if (empty($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(['success'=>false,'message'=>'Unauthorized']); exit;
}

$user_id   = (int)$_SESSION['user_id'];
$course_id = !empty($_POST['course_id']) ? (int)$_POST['course_id'] : null;
$spell_id  = !empty($_POST['spell_id'])  ? (int)$_POST['spell_id']  : null;

if (!$course_id && !$spell_id) {
  echo json_encode(['success'=>false,'message'=>'No target']); exit;
}

try {
  $db = getDB();

  if ($course_id) {
    $chk = $db->prepare('SELECT id FROM progress WHERE user_id=? AND course_id=?');
    $chk->execute([$user_id, $course_id]);
    if ($chk->fetch()) {
      echo json_encode(['success'=>false,'message'=>'Sudah di-explore','already'=>true]); exit;
    }
    $xp_stmt = $db->prepare('SELECT xp_reward FROM courses WHERE id=?');
    $xp_stmt->execute([$course_id]);
    $row = $xp_stmt->fetch();
    if (!$row) { echo json_encode(['success'=>false,'message'=>'Course not found']); exit; }
    $xp = (int)$row['xp_reward'];
    $db->prepare('INSERT INTO progress (user_id,course_id,xp_earned,created_at) VALUES (?,?,?,NOW())')
       ->execute([$user_id,$course_id,$xp]);
  } else {
    $chk = $db->prepare('SELECT id FROM progress WHERE user_id=? AND spell_id=?');
    $chk->execute([$user_id, $spell_id]);
    if ($chk->fetch()) {
      echo json_encode(['success'=>false,'message'=>'Sudah dipelajari','already'=>true]); exit;
    }
    $xp_stmt = $db->prepare('SELECT xp_reward FROM spells WHERE id=?');
    $xp_stmt->execute([$spell_id]);
    $row = $xp_stmt->fetch();
    if (!$row) { echo json_encode(['success'=>false,'message'=>'Spell not found']); exit; }
    $xp = (int)$row['xp_reward'];
    $db->prepare('INSERT INTO progress (user_id,spell_id,xp_earned,created_at) VALUES (?,?,?,NOW())')
       ->execute([$user_id,$spell_id,$xp]);
  }

  $db->prepare('UPDATE users SET xp = xp + ? WHERE id=?')->execute([$xp, $user_id]);

  $fresh = $db->prepare('SELECT xp, level FROM users WHERE id=?');
  $fresh->execute([$user_id]);
  $u = $fresh->fetch();
  $_SESSION['xp']    = (int)$u['xp'];
  $_SESSION['level'] = $u['level'];

  echo json_encode(['success'=>true,'xp_earned'=>$xp,'total_xp'=>$u['xp'],'level'=>$u['level']]);

} catch (Exception $e) {
  error_log($e->getMessage());
  echo json_encode(['success'=>false,'message'=>'Server error']);
}