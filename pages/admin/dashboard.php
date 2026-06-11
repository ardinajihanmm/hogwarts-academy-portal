kalo yang ini<?php
session_start();
require_once '../../config/auth.php';
requireAdmin();
require_once '../../config/database.php';

try {
  $db = getDB();
  $total_students = (int)$db->query("SELECT COUNT(*) FROM users WHERE role='student'")->fetchColumn();
  $total_courses  = (int)$db->query("SELECT COUNT(*) FROM courses")->fetchColumn();
  $total_spells   = (int)$db->query("SELECT COUNT(*) FROM spells")->fetchColumn();
  $total_houses   = 4;
} catch (Exception $e) {
  $total_students = $total_courses = $total_spells = 0; $total_houses = 4;
}

$active_page   = 'dashboard';
$sidebar_depth = '../../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Admin Dashboard – Hogwarts Academy</title>
  <link rel="stylesheet" href="../../assets/css/main.css">
  <link rel="icon" href="../../assets/img/logo.png">
</head>
<body>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<button class="hamburger" id="hamburgerBtn"><span></span><span></span><span></span></button>

<div class="app-layout">
  <?php include '../../components/sidebar_admin.php'; ?>
  <main class="main-content">
    <div class="bg-scene"></div>
    <div class="content-wrapper">

      <div class="page-in" style="margin-top:30px;"margin-bottom:130px">
        <h1 class="welcome-title">Welcome Back, Professor!</h1>
        <p class="admin-subtitle">
            Administrator Panel
        </p>

        <p class="admin-subtitle">
          Manage Hogwarts Academy Portal
        </p>  
      </div>

     
      <div class="stats-row stagger">
       
        <div class="card stat-card">
          <p class="stat-card-label">Total Student</p>
          <div class="stat-card-body">
            <img src="../../assets/img/Group.svg" class="quick-action-icon" alt="Student">
            <span class="stat-card-num"><?= $total_students ?></span>
          </div>
          <a href="manage_students.php" class="stat-card-link">View all students &gt;&gt;</a>
        </div>
      
        <div class="card stat-card">
          <p class="stat-card-label">Total Courses</p>
          <div class="stat-card-body">
            <img src="../../assets/img/auto_stories.svg" class="quick-action-icon" alt="Courses">
            <span class="stat-card-num"><?= $total_courses ?></span>
          </div>
          <a href="manage_courses.php" class="stat-card-link">View all courses &gt;&gt;</a>
        </div>
       
        <div class="card stat-card">
          <p class="stat-card-label">Total Spells</p>
          <div class="stat-card-body">
            <img src="../../assets/img/playing_cards.svg" class="quick-action-icon" alt="Spells">
            <span class="stat-card-num"><?= $total_spells ?></span>
          </div>
          <a href="manage_spells.php" class="stat-card-link">View all Spells &gt;&gt;</a>
        </div>
       
        <div class="card stat-card">
          <p class="stat-card-label">Total Houses</p>
          <div class="stat-card-body">
            <img src="../../assets/img/flag.svg" class="quick-action-icon" alt="Houses">
            <span class="stat-card-num"><?= $total_houses ?></span>
          </div>
          <span class="stat-card-link" style="cursor:default">View all Houses &gt;&gt;</span>
        </div>
      </div>

      <div class="section-header">
        <h2 class="section-title">Quick Actions</h2>
      </div>
      <div class="quick-actions-row stagger">
        <div class="card quick-action-card" onclick="location.href='manage_courses.php?action=add'">
          <img src="../../assets/img/add_circle.svg" class="quick-action-icon"alt="Course">
          <p class="quick-action-title">Add Course</p>
          <p class="quick-action-desc">Create new course</p>
        </div>
        <div class="card quick-action-card" onclick="location.href='manage_spells.php?action=add'">
          <img src="../../assets/img/wand_stars.svg" class="quick-action-icon" alt="Spell">
          <p class="quick-action-title">Add Spell</p>
          <p class="quick-action-desc">Create new spell</p>
        </div>
        <div class="card quick-action-card" onclick="location.href='manage_students.php?action=add'">
          <img src="../../assets/img/account_circle.svg" class="quick-action-icon" alt="Student">
          <p class="quick-action-title">Add Student</p>
          <p class="quick-action-desc">Create new student</p>
        </div>
      </div>

    </div>
  </main>
</div>

<div class="toast-container" id="toastContainer"></div>
<script src="../../assets/js/main.js"></script>
</body>
</html>
