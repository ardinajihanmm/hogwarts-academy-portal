<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['success' => false, 'message' => 'Not authenticated']);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_FILES['photo'])) {
  echo json_encode(['success' => false, 'message' => 'No file received']);
  exit;
}

$file    = $_FILES['photo'];
$maxSize = 5 * 1024 * 1024; 
$allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

if ($file['error'] !== UPLOAD_ERR_OK) {
  echo json_encode(['success' => false, 'message' => 'Upload error']);
  exit;
}

if ($file['size'] > $maxSize) {
  echo json_encode(['success' => false, 'message' => 'File too large (max 5MB)']);
  exit;
}

$finfo    = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if (!in_array($mimeType, $allowed)) {
  echo json_encode(['success' => false, 'message' => 'Invalid file type']);
  exit;
}

$uploadDir = __DIR__ . '/../assets/img/uploads/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

$ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = 'user_' . $_SESSION['user_id'] . '_' . time() . '.' . strtolower($ext);
$dest     = $uploadDir . $filename;

if (!move_uploaded_file($file['tmp_name'], $dest)) {
  echo json_encode(['success' => false, 'message' => 'Could not save file']);
  exit;
}

if (!empty($_SESSION['photo_url']) && strpos($_SESSION['photo_url'], 'default') === false) {
  $oldFile = __DIR__ . '/../' . ltrim($_SESSION['photo_url'], '/');
  if (file_exists($oldFile)) @unlink($oldFile);
}

$photoUrl = '../../assets/img/uploads/' . $filename;
$_SESSION['photo_url'] = $photoUrl;



echo json_encode(['success' => true, 'photo_url' => $photoUrl]);
