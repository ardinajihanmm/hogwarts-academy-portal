<?php
session_start();
require_once '../../config/auth.php';
requireStudent();
require_once '../../config/database.php';

$user_id  = $_SESSION['user_id'];
$username = $_SESSION['username'];
$house    = $_SESSION['house'] ?? 'Gryffindor';
$xp       = $_SESSION['xp'];
$level    = $_SESSION['level'];
$first    = explode(' ', $username)[0];

try {
  $db = getDB();

  $st = $db->prepare('SELECT COUNT(DISTINCT course_id) c, COUNT(DISTINCT spell_id) s FROM progress WHERE user_id=?');
  $st->execute([$user_id]);
  $stats = $st->fetch();
  $courses_explored = (int)$stats['c'];
  $spells_learned   = (int)$stats['s'];

  // XP to next level
  $next_level = ''; $xp_needed = 0; $xp_progress = 100;
  if ($xp < 500)       { $next_level='Advanced Wizard'; $xp_needed=500-$xp; $xp_progress=round(($xp/500)*100); }
  elseif ($xp < 1500)  { $next_level='Expert Wizard';   $xp_needed=1500-$xp; $xp_progress=round((($xp-500)/1000)*100); }
  else                 { $next_level='MAX LEVEL';        $xp_progress=100; }

  // Recent courses
  $rc = $db->prepare('SELECT c.course_name, c.professor, p.xp_earned FROM progress p JOIN courses c ON p.course_id=c.id WHERE p.user_id=? AND p.course_id IS NOT NULL ORDER BY p.created_at DESC LIMIT 3');
  $rc->execute([$user_id]);
  $recent_courses = $rc->fetchAll();

  // Recent spells
  $rs = $db->prepare('SELECT s.spell_name, s.type, s.description FROM progress p JOIN spells s ON p.spell_id=s.id WHERE p.user_id=? AND p.spell_id IS NOT NULL ORDER BY p.created_at DESC LIMIT 4');
  $rs->execute([$user_id]);
  $recent_spells = $rs->fetchAll();

} catch (Exception $e) {
  $courses_explored=$spells_learned=0; $xp_progress=0; $next_level=''; $xp_needed=0;
  $recent_courses=[]; $recent_spells=[];
}

$house_data = [
  'Gryffindor'=>['traits'=>'Bravery • Courage • Determination','founder'=>'Godric Gryffindor','crest'=>'../../assets/img/gryffindor.png'],
  'Slytherin' =>['traits'=>'Ambition • Cunning • Leadership',  'founder'=>'Salazar Slytherin', 'crest'=>'../../assets/img/slytherin.png'],
  'Ravenclaw' =>['traits'=>'Intelligence • Wit • Wisdom',      'founder'=>'Rowena Ravenclaw',  'crest'=>'../../assets/img/ravenclaw.png'],
  'Hufflepuff'=>['traits'=>'Loyalty • Patience • Hard Work',   'founder'=>'Helga Hufflepuff',  'crest'=>'../../assets/img/hufflepuff.png'],
];
$hd = $house_data[$house] ?? $house_data['Gryffindor'];

$active_page = 'dashboard';
$sidebar_depth = '../../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Dashboard – Hogwarts Academy</title>
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

      <div style="margin-bottom:28px" class="page-in">
        <h1 class="welcome-title">Welcome Back Mr. <?= htmlspecialchars($first) ?>!</h1>
        <blockquote class="welcome-quote">
          "Help will always be given at Hogwarts to those who ask for it."
          <cite>— Albus Dumbledore</cite>
        </blockquote>
      </div>

      <div class="house-row">
        <div class="card house-card">
          <img src="<?= $hd['crest'] ?>" alt="Crest" class="house-crest" onerror="this.style.opacity='.15'">
          <div>
            <h3 style="font-family:var(--font-poppins);font-size:2.3rem;font-weight:600;color:var(--cream);margin-bottom:3px">House of <?= htmlspecialchars($house) ?></h3>
            <p style="font-family:var(--font-corm);font-size:0.8rem;color:#BA8F5B;margin-bottom:12px">Founded by <?= htmlspecialchars($hd['founder']) ?></p>
            <p style="font-family:var(--font-corm);font-size:1rem;color:var(--cream);margin-bottom:5px">Traits:</p>
            <p style="font-family:var(--font-corm);font-size:1rem;color:#BA8F5B;font-weight:500;margin-bottom:14px"><?= htmlspecialchars($hd['traits']) ?></p>

            <div style="margin-bottom:6px;display:flex;justify-content:space-between">
              <span style="font-family:var(--font-cinzel);font-size:0.68rem;color:var(--tan)"><?= htmlspecialchars($level) ?></span>
              <span style="font-family:var(--font-cinzel);font-size:0.68rem;color:var(--gold)"><?= number_format($xp) ?> XP</span>
            </div>
            <div style="height:6px;background:rgba(202,181,140,0.2);border-radius:3px;overflow:hidden;margin-bottom:6px">
              <div style="height:100%;width:<?= $xp_progress ?>%;background:linear-gradient(90deg,var(--amber),var(--gold));border-radius:3px;transition:width 1s ease"></div>
            </div>
            <?php if ($next_level && $next_level !== 'MAX LEVEL'): ?>
            <p style="font-family:var(--font-pop);font-size:0.65rem;color:#BA8F5B;opacity:0.75">Next: <?= $next_level ?> (<?= number_format($xp_needed) ?> XP left)</p>
            <?php endif; ?>
          </div>
        </div>

        <div class="card recent-course-card">
          <div class="section-header recent-course-header" style="margin-bottom:14px">
            <div style="display:flex;align-items:center;gap:8px;font-family:var(--font-poppins);font-size:1.82rem;color:var(--cream)">
              <img src="../../assets/img/quill.svg" class="recent-course-icon" alt="">
              Recent Course
            </div>
            <a href="course.php" class="view-all-link">View all &gt;&gt;</a>
          </div>
          <?php if (empty($recent_courses)): ?>
          <p style="font-family:var(--font-cinzel);font-size:0.75rem;color:var(--tan);text-align:center;padding:20px">No courses explored yet.</p>
          <?php else: foreach ($recent_courses as $rc): ?>
          <div class="rci">
            <div>
              <p class="rci-name"><?= htmlspecialchars($rc['course_name']) ?></p>
              <p class="rci-prof"><?= htmlspecialchars($rc['professor']) ?></p>
            </div>
            <span class="rci-xp">+ <?= $rc['xp_earned'] ?> XP</span>
          </div>
          <?php endforeach; endif; ?>
        </div>
      </div>

      <div class="cards-grid-4 stagger" style="margin-bottom:28px">
        <div class="card" style="padding:20px;text-align:center">
          <p style="font-family:var(--font-cinzel);font-size:1rem;color:#BA8F5B;letter-spacing:.12em;margin-bottom:8px">CURRENT LEVEL</p>
          <p style="font-family:var(--font-cinzel);font-size:0.9rem;font-weight:700;color:var(--cream)"><?= htmlspecialchars($level) ?></p>
        </div>
        <div class="card" style="padding:20px;text-align:center">
          <p style="font-family:var(--font-cinzel);font-size:1rem;color:#BA8F5B;letter-spacing:.12em;margin-bottom:8px">TOTAL XP</p>
          <p style="font-family:var(--font-cinzel);font-size:1.4rem;font-weight:700;color:var(--cream)"><?= number_format($xp) ?></p>
        </div>
        <div class="card" style="padding:20px;text-align:center">
          <p style="font-family:var(--font-cinzel);font-size:1rem;color:#BA8F5B;letter-spacing:.12em;margin-bottom:8px">COURSES EXPLORED</p>
          <p style="font-family:var(--font-cinzel);font-size:1.4rem;font-weight:700;color:var(--cream)"><?= $courses_explored ?></p>
        </div>
        <div class="card" style="padding:20px;text-align:center">
          <p style="font-family:var(--font-cinzel);font-size:1rem;color:#BA8F5B;letter-spacing:.12em;margin-bottom:8px">SPELLS LEARNED</p>
          <p style="font-family:var(--font-cinzel);font-size:1.4rem;font-weight:700;color:var(--cream)"><?= $spells_learned ?></p>
        </div>
      </div>

      <!-- Recent Spells -->
      <div class="section-header">
        <h2 class="section-title">Recent Spells</h2>
        <a href="spellbook.php" class="view-all-link">View all &gt;&gt;</a>
      </div>

      <div class="cards-grid-4 stagger">
        <?php if (empty($recent_spells)): ?>
        <div style="grid-column:1/-1;text-align:center;padding:40px;font-family:var(--font-cinzel);font-size:0.8rem;color:#BA8F5B">No spells learned yet. Visit Spell Book!</div>
        <?php else: foreach ($recent_spells as $sp): ?>
        <div class="card course-card">
          <h3 class="course-card-title"><?= htmlspecialchars($sp['spell_name']) ?></h3>
          <p class="course-card-prof"><?= htmlspecialchars($sp['type']) ?></p>
          <p class="course-card-desc"><?= htmlspecialchars(substr($sp['description'],0,80)).'...' ?></p>
        </div>
        <?php endforeach; endif; ?>
      </div>

    </div>
  </main>
</div>

<div class="toast-container" id="toastContainer"></div>
<script src="../../assets/js/main.js"></script>
<script src="../../assets/js/dashboard.js"></script>
</body>
</html>
