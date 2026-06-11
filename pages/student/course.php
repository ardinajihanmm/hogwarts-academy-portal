<?php
session_start();
require_once '../../config/auth.php';
requireStudent();
require_once '../../config/database.php';

$user_id = $_SESSION['user_id'];
$search  = trim($_GET['search'] ?? '');
$diff    = $_GET['diff'] ?? '';

try {
  $db = getDB();

  $exp = $db->prepare('SELECT course_id FROM progress WHERE user_id=? AND course_id IS NOT NULL');
  $exp->execute([$user_id]);
  $explored = array_column($exp->fetchAll(), 'course_id');

  $where = 'WHERE 1=1';
  $params = [];
  if ($search) { $where .= ' AND course_name LIKE ?'; $params[] = "%$search%"; }
  if ($diff)   { $where .= ' AND difficulty = ?';     $params[] = $diff; }
  $stmt = $db->prepare("SELECT * FROM courses $where ORDER BY created_at DESC");
  $stmt->execute($params);
  $courses = $stmt->fetchAll();

} catch (Exception $e) { $courses = []; $explored = []; }

$active_page   = 'course';
$sidebar_depth = '../../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Courses – Hogwarts Academy</title>
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

      <div class="page-header page-in">
        <div>
          <div style="display:flex;align-items:center;gap:12px;">
          <img src="../../assets/img/quill.svg" class="recent-course-icon" alt="">
          <h1 class="page-title">Courses</h1>
        </div>

          <p class="page-subtitle">
            Explore magical subjects at Hogwarts
          </p>
        </div>
        <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap">
          <form method="GET" style="display:contents">
            <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
            <select name="diff" class="filter-select" onchange="this.form.submit()">
              <option value="" <?= !$diff?'selected':'' ?>>All Levels</option>
              <option value="Beginner"     <?= $diff==='Beginner'?'selected':'' ?>>Beginner</option>
              <option value="Intermediate" <?= $diff==='Intermediate'?'selected':'' ?>>Intermediate</option>
              <option value="Advanced"     <?= $diff==='Advanced'?'selected':'' ?>>Advanced</option>
            </select>
          </form>
          <form method="GET">
            <input type="hidden" name="diff" value="<?= htmlspecialchars($diff) ?>">
            <div class="search-bar">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
              <input type="text" name="search" placeholder="Search courses....." value="<?= htmlspecialchars($search) ?>">
            </div>
          </form>
        </div>
      </div>

      <div class="cards-grid stagger">
        <?php if (empty($courses)): ?>
        <div style="grid-column:1/-1;text-align:center;padding:60px;font-family:var(--font-cinzel);font-size:0.85rem;color:var(--tan)">No courses found.</div>
        <?php endif; ?>
        <?php foreach ($courses as $c):
          $done = in_array($c['id'], $explored);
          $lc   = strtolower($c['difficulty']);
          $topics = $c['topics'] ? array_slice(explode(',', $c['topics']), 0, 4) : [];
        ?>
        <div class="card course-card" onclick='openCourseModal(<?= json_encode(['id'=>(int)$c['id'],'name'=>$c['course_name'],'prof'=>$c['professor'],'diff'=>$c['difficulty'],'xp'=>(int)$c['xp_reward'],'desc'=>$c['description'],'topics'=>$topics,'done'=>$done]) ?>)'>
          <?php if ($done): ?>
          <div style="position:absolute;top:12px;right:12px;background:rgba(55,153,56,0.25);border:1px solid rgba(55,153,56,0.5);border-radius:20px;padding:3px 10px;font-family:var(--font-cinzel);font-size:0.6rem;color:#7dd87e;letter-spacing:.08em">✓ EXPLORED</div>
          <?php endif; ?>
          <h3 class="course-card-title"><?= htmlspecialchars($c['course_name']) ?></h3>
          <p class="course-card-prof"><?= htmlspecialchars($c['professor']) ?></p>
          <span class="badge <?= $lc ?>"><?= $c['difficulty'] ?></span>
          <p class="course-card-desc"><?= htmlspecialchars(substr($c['description'],0,90)).'...' ?></p>
          <p class="course-card-xp">+ <?= $c['xp_reward'] ?> XP</p>
        </div>
        <?php endforeach; ?>
      </div>

    </div>
  </main>
</div>

<!-- Modal -->
<div class="modal-backdrop" id="courseModal" style="display:none" onclick="if(event.target===this)closeModal()">
  <div class="modal-box wide">
    <button class="modal-close" onclick="closeModal()">✕</button>
    <p id="mProf" style="font-family:var(--font-pop);font-size:0.72rem;color:var(--tan);letter-spacing:.1em;text-transform:uppercase;margin-bottom:6px"></p>
    <h2 class="modal-title" id="mName"></h2>
    <span class="badge intermediate" id="mDiff" style="margin-bottom:14px"></span>
    <p id="mDesc" style="font-family:var(--font-pop);font-size:0.8rem;color:var(--cream);opacity:.8;line-height:1.6;margin-bottom:16px"></p>
    <div id="mTopicsWrap" style="margin-bottom:18px">
      <p style="font-family:var(--font-cinzel);font-size:0.68rem;color:var(--tan);letter-spacing:.1em;margin-bottom:8px">TOPICS COVERED</p>
      <div id="mTopics" style="display:flex;flex-wrap:wrap;gap:8px"></div>
    </div>
    <div style="display:flex;justify-content:space-between;align-items:center">
      <p id="mXp" style="font-family:var(--font-cinzel);font-size:0.88rem;font-weight:600;color:var(--gold)"></p>
      <button class="btn-primary" id="markBtn" onclick="markExplored()">Mark As Explored</button>
    </div>
  </div>
</div>

<div class="toast-container" id="toastContainer"></div>
<script src="../../assets/js/main.js"></script>
<script>
let currentCourse = null;
function openCourseModal(data) {
  currentCourse = data;
  document.getElementById('mName').textContent = data.name;
  document.getElementById('mProf').textContent = data.prof;
  document.getElementById('mDiff').textContent = data.diff;
  document.getElementById('mDiff').className   = 'badge ' + data.diff.toLowerCase();
  document.getElementById('mDesc').textContent = data.desc;
  document.getElementById('mXp').textContent   = '+ ' + data.xp + ' XP';
  
  const tWrap = document.getElementById('mTopics');
  tWrap.innerHTML = '';
  (data.topics||[]).forEach(t => {
    const span = document.createElement('span');
    span.textContent = t.trim();
    span.style.cssText = 'padding:4px 12px;background:rgba(202,181,140,0.12);border:1px solid rgba(202,181,140,0.25);border-radius:20px;font-family:var(--font-pop);font-size:0.7rem;color:var(--cream)';
    tWrap.appendChild(span);
  });
  const btn = document.getElementById('markBtn');
  if (data.done) {
    btn.textContent = '✓ Already Explored';
    btn.disabled = true; btn.style.opacity = '0.55';
  } else {
    btn.textContent = 'Mark As Explored'; btn.disabled = false; btn.style.opacity = '1';
  }
  document.getElementById('courseModal').style.display = 'flex';
}
function closeModal() { document.getElementById('courseModal').style.display = 'none'; }

async function markExplored() {
  if (!currentCourse || currentCourse.done) return;
  const btn = document.getElementById('markBtn');
  btn.textContent = 'Casting…'; btn.disabled = true;
  const fd = new FormData();
  fd.append('course_id', currentCourse.id);
  try {
    const r = await fetch('../../actions/add_progress.php', {method:'POST',body:fd});
    const d = await r.json();
    if (d.success) {
      showToast('yay +' + d.xp_earned + ' XP earned! Total: ' + d.total_xp + ' XP', 'success');
      currentCourse.done = true;
      btn.textContent = '✓ Explored!';
      setTimeout(()=>location.reload(), 1200);
    } else {
      showToast(d.already ? ' Already explored!' : ' '+d.message, d.already?'success':'error');
      if (d.already) { btn.textContent='✓ Already Explored'; }
      else { btn.textContent='Mark As Explored'; btn.disabled=false; }
    }
  } catch { showToast('Connection error','error'); btn.textContent='Mark As Explored'; btn.disabled=false; }
}
</script>
</body>
</html>
