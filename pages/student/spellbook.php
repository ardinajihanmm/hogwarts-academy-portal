<?php
session_start();
require_once '../../config/auth.php';
requireStudent();
require_once '../../config/database.php';

$user_id = $_SESSION['user_id'];
$search  = trim($_GET['search'] ?? '');
$type    = $_GET['type'] ?? '';
$page    = max(1,(int)($_GET['page'] ?? 1));
$per_page = 8;

try {
  $db = getDB();
  $lrn = $db->prepare('SELECT spell_id FROM progress WHERE user_id=? AND spell_id IS NOT NULL');
  $lrn->execute([$user_id]);
  $learned = array_column($lrn->fetchAll(), 'spell_id');

  $where = 'WHERE 1=1'; $params = [];
  if ($search) { $where .= ' AND spell_name LIKE ?'; $params[] = "%$search%"; }
  if ($type)   { $where .= ' AND type = ?';          $params[] = $type; }

  $cnt = $db->prepare("SELECT COUNT(*) FROM spells $where");
  $cnt->execute($params);
  $total = (int)$cnt->fetchColumn();
  $total_pages = max(1, ceil($total / $per_page));
  $offset = ($page-1)*$per_page;

  $stmt = $db->prepare("SELECT * FROM spells $where ORDER BY created_at DESC LIMIT $per_page OFFSET $offset");
  $stmt->execute($params);
  $spells = $stmt->fetchAll();

  $from = $total > 0 ? $offset+1 : 0;
  $to   = min($offset+$per_page, $total);

  $types_stmt = $db->query('SELECT DISTINCT type FROM spells ORDER BY type');
  $types = array_column($types_stmt->fetchAll(), 'type');

} catch (Exception $e) { $spells=[]; $total=0; $total_pages=1; $from=0; $to=0; $types=[]; $learned=[]; $page=1; }

$active_page   = 'spellbook';
$sidebar_depth = '../../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Spell Book – Hogwarts Academy</title>
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
            <h1 class="page-title">Spell Book</h1>
          </div>
          <p class="page-subtitle">Explore magical subjects at Hogwarts</p>
        </div>
        <form method="GET">
          <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">
          <div class="search-bar">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            <input type="text" name="search" placeholder="Search spells....." value="<?= htmlspecialchars($search) ?>">
          </div>
        </form>
      </div>

      <div class="filter-tabs">
        <a href="?type=&search=<?= urlencode($search) ?>" class="filter-tab <?= !$type?'active':'' ?>">All</a>
        <?php foreach ($types as $t): ?>
        <a href="?type=<?= urlencode($t) ?>&search=<?= urlencode($search) ?>" class="filter-tab <?= $type===$t?'active':'' ?>"><?= htmlspecialchars($t) ?></a>
        <?php endforeach; ?>
      </div>

      <div class="cards-grid stagger" style="grid-template-columns:repeat(4,1fr)">
        <?php if (empty($spells)): ?>
        <div style="grid-column:1/-1;text-align:center;padding:60px;font-family:var(--font-cinzel);font-size:0.85rem;color:var(--tan)">No spells found.</div>
        <?php endif; ?>
        <?php foreach ($spells as $s):
          $done = in_array($s['id'], $learned);
          $lc   = strtolower($s['difficulty']);
        ?>
        <div class="card course-card" onclick='openSpellModal(<?= json_encode(['id'=>(int)$s['id'],'name'=>$s['spell_name'],'type'=>$s['type'],'diff'=>$s['difficulty'],'xp'=>(int)$s['xp_reward'],'desc'=>$s['description'],'done'=>$done]) ?>)'>
          <?php if ($done): ?>
          <div style="position:absolute;top:10px;right:10px;background:rgba(55,153,56,0.2);border:1px solid rgba(55,153,56,0.4);border-radius:20px;padding:2px 9px;font-family:var(--font-cinzel);font-size:0.58rem;color:#7dd87e">✓ LEARNED</div>
          <?php endif; ?>
          <h3 class="course-card-title"><?= htmlspecialchars($s['spell_name']) ?></h3>
          <p class="course-card-prof"><?= htmlspecialchars($s['type']) ?></p>
          <span class="badge <?= $lc ?>"><?= $s['difficulty'] ?></span>
          <p class="course-card-desc"><?= htmlspecialchars(substr($s['description'],0,80)).'...' ?></p>
          <p class="course-card-xp">+ <?= $s['xp_reward'] ?> XP</p>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="pagination">
        <p class="pagination-info">Showing <?= $from ?> to <?= $to ?> of <?= $total ?> spells</p>
        <div class="pagination-controls">
          <a href="?type=<?= urlencode($type) ?>&search=<?= urlencode($search) ?>&page=<?= max(1,$page-1) ?>" class="page-btn <?= $page===1?'disabled':'' ?>">&lt;</a>
          <?php for ($i=1;$i<=$total_pages;$i++): ?>
          <a href="?type=<?= urlencode($type) ?>&search=<?= urlencode($search) ?>&page=<?= $i ?>" class="page-btn <?= $i===$page?'active':'' ?>"><?= $i ?></a>
          <?php endfor; ?>
          <a href="?type=<?= urlencode($type) ?>&search=<?= urlencode($search) ?>&page=<?= min($total_pages,$page+1) ?>" class="page-btn <?= $page===$total_pages?'disabled':'' ?>">&gt;</a>
        </div>
      </div>

    </div>
  </main>
</div>

<div class="modal-backdrop" id="spellModal" style="display:none" onclick="if(event.target===this)closeModal()">
  <div class="modal-box">
    <button class="modal-close" onclick="closeModal()">✕</button>
    <p id="mType" style="font-family:var(--font-pop);font-size:0.72rem;color:var(--tan);letter-spacing:.1em;text-transform:uppercase;margin-bottom:6px"></p>
    <h2 class="modal-title" id="mName"></h2>
    <span id="mDiff" class="badge beginner" style="margin-bottom:14px"></span>
    <p id="mDesc" style="font-family:var(--font-pop);font-size:0.8rem;color:var(--cream);opacity:.8;line-height:1.6;margin-bottom:18px"></p>
    <div style="display:flex;justify-content:space-between;align-items:center">
      <p id="mXp" style="font-family:var(--font-cinzel);font-size:0.88rem;font-weight:600;color:var(--gold)"></p>
      <button class="btn-primary" id="learnBtn" onclick="learnSpell()">Learn Spell</button>
    </div>
  </div>
</div>

<div class="toast-container" id="toastContainer"></div>
<script src="../../assets/js/main.js"></script>
<script>
let currentSpell = null;
function openSpellModal(data) {
  currentSpell = data;
  document.getElementById('mName').textContent = data.name;
  document.getElementById('mType').textContent = data.type;
  document.getElementById('mDiff').textContent = data.diff;
  document.getElementById('mDiff').className   = 'badge ' + data.diff.toLowerCase();
  document.getElementById('mDesc').textContent = data.desc;
  document.getElementById('mXp').textContent   = '+ ' + data.xp + ' XP';
  const btn = document.getElementById('learnBtn');
  if (data.done) {
    btn.textContent='✓ Already Learned'; btn.disabled=true; btn.style.opacity='.55';
  } else {
    btn.textContent='Learn Spell'; btn.disabled=false; btn.style.opacity='1';
  }
  document.getElementById('spellModal').style.display='flex';
}
function closeModal() { document.getElementById('spellModal').style.display='none'; }

async function learnSpell() {
  if (!currentSpell || currentSpell.done) return;
  const btn = document.getElementById('learnBtn');
  btn.textContent='Casting…'; btn.disabled=true;
  const fd = new FormData();
  fd.append('spell_id', currentSpell.id);
  try {
    const r = await fetch('../../actions/add_progress.php', {method:'POST',body:fd});
    const d = await r.json();
    if (d.success) {
      showToast('yay+' + d.xp_earned + ' XP! Spell learned!', 'success');
      currentSpell.done = true; btn.textContent='✓ Learned!';
      setTimeout(()=>location.reload(), 1200);
    } else {
      showToast(d.already?' Already learned!':''+d.message, d.already?'success':'error');
      btn.textContent = d.already ? '✓ Already Learned' : 'Learn Spell';
      if (!d.already) btn.disabled = false;
    }
  } catch { showToast('Connection error','error'); btn.textContent='Learn Spell'; btn.disabled=false; }
}
</script>
</body>
</html>
