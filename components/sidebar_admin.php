<?php
$active = $active_page ?? 'dashboard';
$depth  = $sidebar_depth ?? '../../';
?>
<aside class="sidebar" id="sidebar">
  <img src="<?= $depth ?>assets/img/logo.png" alt="Hogwarts Crest" class="sidebar-crest" onerror="this.style.opacity='.3'">
  <p class="sidebar-brand">Hogwarts<br>Academy Portal</p>
  <div class="sidebar-divider"></div>
 
  <nav class="sidebar-nav">
    <a href="<?= $depth ?>pages/admin/dashboard.php"
       class="nav-item <?= $active==='dashboard'?'active':'' ?>">
       Dashboard
    </a>

    <a href="<?= $depth ?>pages/admin/manage_students.php"
       class="nav-item <?= $active==='students'?'active':'' ?>">
       Manage Students
    </a>

    <a href="<?= $depth ?>pages/admin/manage_courses.php"
       class="nav-item <?= $active==='courses'?'active':'' ?>">
       Manage Courses
    </a>

    <a href="<?= $depth ?>pages/admin/manage_spells.php"
       class="nav-item <?= $active==='spells'?'active':'' ?>">
       Manage Spells
    </a>

    <a href="<?= $depth ?>/actions/logout.php"
       class="nav-item logout"
       onclick="return confirm('Yakin mau logout?')">
       Logout
    </a>
</nav>
</aside>
 



