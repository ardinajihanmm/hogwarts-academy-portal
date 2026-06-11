<?php
session_start();
require_once __DIR__ . '/../config/database.php';
if (empty($_SESSION['user_id'])) { header('Location: /pages/login.php'); exit; }

$user_id  = (int)$_SESSION['user_id'];
$username = trim($_POST['username'] ?? '');
$email    = trim($_POST['email'] ?? '');

if (!$username || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
  header('Location: ../pages/student/profile.php?msg=Data+tidak+valid'); exit;
}

try {
  $db = getDB();

  if (!empty($_FILES['photo']['name']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $allowed = ['image/jpeg','image/png','image/gif','image/webp'];
    $finfo   = finfo_open(FILEINFO_MIME_TYPE);
    $mime    = finfo_file($finfo, $_FILES['photo']['tmp_name']);
    finfo_close($finfo);
    if (in_array($mime, $allowed) && $_FILES['photo']['size'] <= 5*1024*1024) {
      $dir = __DIR__ . '/../assets/img/uploads/';
      if (!is_dir($dir)) mkdir($dir, 0755, true);
      $ext  = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
      $file = 'user_' . $user_id . '_' . time() . '.' . strtolower($ext);
      move_uploaded_file($_FILES['photo']['tmp_name'], $dir . $file);
      $photo_url = '../../assets/img/uploads/' . $file;
      $db->prepare('UPDATE users SET photo_url=? WHERE id=?')->execute([$photo_url, $user_id]);
      $_SESSION['photo_url'] = $photo_url;
    }
  }

  $db->prepare('UPDATE users SET username=?, email=? WHERE id=?')->execute([$username, $email, $user_id]);
  $_SESSION['username'] = $username;
  $_SESSION['email']    = $email;

  header('Location: ../pages/student/profile.php?msg=Profile+berhasil+diupdate'); exit;
} catch (Exception $e) {
  header('Location: ../pages/student/profile.php?msg=Error:+' . urlencode($e->getMessage())); exit;
}
