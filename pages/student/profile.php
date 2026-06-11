<?php
session_start();
require_once '../../config/auth.php';
requireStudent();
require_once '../../config/database.php';

$user_id = $_SESSION['user_id'];

try {
  $db = getDB();
  $u = $db->prepare('SELECT * FROM users WHERE id=?');
  $u->execute([$user_id]);
  $user = $u->fetch();

  $st = $db->prepare('SELECT COUNT(DISTINCT course_id) c, COUNT(DISTINCT spell_id) s, SUM(xp_earned) x FROM progress WHERE user_id=?');
  $st->execute([$user_id]);
  $stats = $st->fetch();
} catch (Exception $e) { $user = []; $stats = ['c'=>0,'s'=>0,'x'=>0]; }

$username    = $user['username']  ?? $_SESSION['username'];
$email       = $user['email']     ?? $_SESSION['email'] ?? '';
$house       = $user['house']     ?? $_SESSION['house'] ?? '';
$level       = $user['level']     ?? $_SESSION['level'] ?? '';
$xp          = $user['xp']        ?? $_SESSION['xp'] ?? 0;
$photo_url   = !empty($user['photo_url']) ? $user['photo_url'] : '../../assets/img/default-avatar.png';

$member_since= date('d F Y', strtotime($user['created_at'] ?? 'now'));

$active_page   = 'profile';
$sidebar_depth = '../../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Profile – Hogwarts Academy</title>
  <link rel="stylesheet" href="../../assets/css/main.css">
  <link rel="icon" href="../../assets/img/logo.png">
</head>
<body>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<button class="hamburger" id="hamburgerBtn"><span></span><span></span><span></span></button>

<div class="app-layout">
  <?php include '../../components/sidebar_student.php'; ?>
  <main class="main-content">
    <div class="bg-scene"></div>
    <div class="content-wrapper">

      <div class="page-in" style="margin-bottom:28px">
<h1 style="
font-family:'Cinzel Decorative' !important;
font-size:3rem;
font-weight:650;
color:var(--cream);
">
My Profile
</h1>
        <p style="font-family:'Cormorant Garamond', serif;font-size:1.2rem;color:var(--tan)">Manage your profile Information</p>
      </div>

      <?php if (!empty($_GET['msg'])): ?>
      <div style="padding:12px 18px;border-radius:8px;border:1px solid var(--green);background:rgba(55,153,56,0.15);color:#7dd87e;font-family:var(--font-pop);font-size:0.8rem;margin-bottom:20px">
         <?= htmlspecialchars($_GET['msg']) ?>
      </div>
      <?php endif; ?>

      <form method="POST" action="../../actions/update_profile.php" enctype="multipart/form-data" id="profileForm">

        <div class="profile-layout" style="margin-bottom:24px">
          <div style="display:flex;flex-direction:column;align-items:center;gap:16px">
            <div class="profile-photo-wrap" onclick="document.getElementById('photoInput').click()">
              <img src="<?= htmlspecialchars($photo_url) ?>" alt="Profile" id="photoPreview" onerror="this.src='../assets/img/default-avatar.png'">
              <div class="profile-photo-overlay">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
                <span>Change Photo</span>
              </div>
            </div>
            <input type="file" name="photo" id="photoInput" accept="image/*" style="display:none">
            <button type="button" class="profile-btn profile-photo-btn" onclick="document.getElementById('photoInput').click()">Change Profile</button>
          </div>

          <div class="card" style="padding:28px">
            <div class="form-group">
              <label class="form-label">Username</label>
              <input type="text" name="username" class="form-input" value="<?= htmlspecialchars($username) ?>" id="usernameInput" readonly>
            </div>
            <div class="form-group">
              <label class="form-label">Email</label>
              <div style="position:relative">
                <input type="email" name="email" class="form-input" value="<?= htmlspecialchars($email) ?>" id="emailInput" readonly style="padding-right:42px">
                <span onclick="toggleEdit('emailInput')" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);cursor:pointer;color:var(--tan);transition:color .2s" onmouseover="this.style.color='var(--gold)'" onmouseout="this.style.color='var(--tan)'">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </span>
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">Houses</label>
              <input type="text" class="form-input" value="<?= htmlspecialchars($house) ?>" readonly style="cursor:not-allowed">
            </div>
          </div>
        </div>

        <div class="profile-stats">
          <div class="pstat">
            <p class="pstat-label">Member Since</p>
            <p class="pstat-val" style="font-size:0.88rem"><?= $member_since ?></p>
          </div>
          <div class="pstat">
            <p class="pstat-label">Course Explored</p>
            <p class="pstat-val"><?= (int)$stats['c'] ?></p>
          </div>
          <div class="pstat">
            <p class="pstat-label">Spells Learn</p>
            <p class="pstat-val"><?= (int)$stats['s'] ?></p>
          </div>
          <div class="pstat">
            <p class="pstat-label">Total XP</p>
            <p class="pstat-val"><?= number_format($xp) ?></p>
          </div>
        </div>

        <div style="display:flex;justify-content:center;gap:12px;margin-top:24px">
          <button type="button" id="cancelBtn" class="profile-btn" style="display:none" onclick="cancelEdit()">CANCEL</button>
          <button type="button" id="editBtn" class="profile-btn" onclick="startEdit()">EDIT PROFILE</button>
          <button type="submit" id="saveBtn" class="profile-btn" style="display:none">SAVE CHANGES</button>
        </div>

      </form>

    </div>
  </main>
</div>

<div class="toast-container" id="toastContainer"></div>
<script src="../../assets/js/main.js"></script>
<script>
document.getElementById('photoInput').addEventListener('change', function(){
  const f = this.files[0]; if (!f) return;
  const r = new FileReader();
  r.onload = e => { document.getElementById('photoPreview').src = e.target.result; document.getElementById('sidebarPhoto').src = e.target.result; };
  r.readAsDataURL(f);
  startEdit();
});
function startEdit() {
  document.getElementById('usernameInput').readOnly = false;
  document.getElementById('emailInput').readOnly = false;

  document.getElementById('editBtn').style.display = 'none';
  document.getElementById('cancelBtn').style.display = 'inline-block';
  document.getElementById('saveBtn').style.display = 'inline-block';

  document.getElementById('usernameInput').focus();
}

function cancelEdit() {
  location.reload();
}
function toggleEdit(id) {
  const inp = document.getElementById(id);
  inp.readOnly = false;
  startEdit();
  inp.focus();
}
</script>
</body>
</html>
