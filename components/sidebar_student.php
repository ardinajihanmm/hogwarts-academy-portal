<?php
$active = $active_page ?? 'dashboard';
$username  = $_SESSION['username']  ?? 'Harry Potter';
$house     = $_SESSION['house']     ?? 'Gryffindor';
$photo_url = !empty($_SESSION['photo_url']) ? $_SESSION['photo_url'] : '../../assets/img/default-avatar.png';
$depth = $sidebar_depth ?? '../../';
?>
<aside class="sidebar" id="sidebar">
  <div class="sidebar-logo-row">
    <img src="<?= $depth ?>assets/img/logo.png" alt="Hogwarts" onerror="this.style.display='none'">
    <span>Hogwarts</span>
  </div>
 
  <div class="sidebar-avatar" onclick="document.getElementById('sidebarPhotoInput').click()" title="Ganti foto">
    <img src="<?= htmlspecialchars($photo_url) ?>" alt="Avatar" id="sidebarPhoto" onerror="this.src='<?= $depth ?>assets/img/default-avatar.png'">
  </div>
  <input type="file" id="sidebarPhotoInput" accept="image/*" style="display:none">
 
  <p class="sidebar-username"><?= htmlspecialchars(strtoupper($username)) ?></p>
  <p class="sidebar-house"><?= htmlspecialchars(strtoupper($house)) ?></p>
  <div class="sidebar-divider"></div>
 
  <nav class="sidebar-nav">
    <a href="<?= $depth ?>pages/student/dashboard.php" class="nav-item <?= $active==='dashboard'?'active':'' ?>">
      Dashboard
    </a>
    <a href="<?= $depth ?>pages/student/spellbook.php" class="nav-item <?= $active==='spellbook'?'active':'' ?>">
      Spell Book
    </a>
    <a href="<?= $depth ?>pages/student/course.php" class="nav-item <?= $active==='course'?'active':'' ?>">
      Course
    </a>
    <a href="<?= $depth ?>pages/student/profile.php" class="nav-item <?= $active==='profile'?'active':'' ?>">
      Profile
    </a>
    <a href="<?= $depth ?>/actions/logout.php" class="nav-item logout" onclick="return confirm('Yakin mau logout?')">
      Logout
    </a>
  </nav>
</aside>
 