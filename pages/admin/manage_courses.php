<?php
session_start();
require_once '../../config/auth.php';
requireAdmin();
require_once '../../config/database.php';

$search   = trim($_GET['search'] ?? '');
$page     = max(1,(int)($_GET['page'] ?? 1));
$per_page = 8;

try {
  $db = getDB();
  $where  = 'WHERE 1=1'; $params = [];
  if ($search) { $where .= ' AND course_name LIKE ?'; $params[] = "%$search%"; }

  $cnt = $db->prepare("SELECT COUNT(*) FROM courses $where");
  $cnt->execute($params);
  $total       = (int)$cnt->fetchColumn();
  $total_pages = max(1, ceil($total/$per_page));
  $offset      = ($page-1)*$per_page;

  $stmt = $db->prepare("SELECT * FROM courses $where ORDER BY created_at DESC LIMIT $per_page OFFSET $offset");
  $stmt->execute($params);
  $courses = $stmt->fetchAll();
} catch (Exception $e) { $courses=[]; $total=0; $total_pages=1; }

$open_add = isset($_GET['action']) && $_GET['action'] === 'add';

$active_page   = 'courses';
$sidebar_depth = '../../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Manage Courses – Hogwarts</title>
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

      <div class="page-in" style="margin-bottom:24px">
        <h1 class="page-title">Manage Courses</h1>
        <p class="page-subtitle">View, edit, and manage all courses</p>
      </div>

      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:22px;gap:16px;flex-wrap:wrap">
        <form method="GET">
          <div class="search-bar" style="min-width:280px">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            <input type="text" name="search" placeholder="Search Courses..." value="<?= htmlspecialchars($search) ?>">
          </div>
        </form>
        <button class="btn-add" onclick="openAddModal()">+ Add Course</button>
      </div>

      <div class="data-table-wrap">
        <table class="data-table">
          <thead>
            <tr><th>No</th><th>COURSE</th><th>PROFESSOR</th><th>DIFFICULTY</th><th>XP REWARD</th><th>Action</th></tr>
          </thead>
          <tbody>
            <?php if (empty($courses)): ?>
            <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--tan);font-family:var(--font-cinzel);font-size:1rem">No courses found.</td></tr>
            <?php else: foreach ($courses as $i => $c): ?>
            <tr>
              <td style="color:var(--tan);font-size:1rem"><?= ($page-1)*$per_page + $i + 1 ?></td>
              <td style="font-weight:500"><?= htmlspecialchars($c['course_name']) ?></td>
              <td style="color:var(--tan);font-size:1rem"><?= htmlspecialchars($c['professor']) ?></td>
              <td><span class="badge <?= strtolower($c['difficulty']) ?>"><?= $c['difficulty'] ?></span></td>
              <td style="color:var(--gold);font-family:var(--font-cinzel);font-weight:800">+<?= $c['xp_reward'] ?> XP</td>
              <td>
                <button class="btn-edit" onclick='openEditModal(<?= json_encode(['id'=>(int)$c['id'],'name'=>$c['course_name'],'prof'=>$c['professor'],'diff'=>$c['difficulty'],'xp'=>(int)$c['xp_reward'],'desc'=>$c['description']??'']) ?>)'>Edit</button>
                <button class="btn-del"  onclick="confirmDelete(<?= $c['id'] ?>,'<?= htmlspecialchars(addslashes($c['course_name'])) ?>')">Delete</button>
              </td>
            </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>

      <?php if ($total_pages > 1): ?>
      <div class="pagination" style="margin-top:20px">
        <p class="pagination-info">Total: <?= $total ?> courses</p>
        <div class="pagination-controls">
          <a href="?search=<?= urlencode($search) ?>&page=<?= max(1,$page-1) ?>" class="page-btn <?= $page===1?'disabled':'' ?>">&lt;</a>
          <?php for ($i=1;$i<=$total_pages;$i++): ?>
          <a href="?search=<?= urlencode($search) ?>&page=<?= $i ?>" class="page-btn <?= $i===$page?'active':'' ?>"><?= $i ?></a>
          <?php endfor; ?>
          <a href="?search=<?= urlencode($search) ?>&page=<?= min($total_pages,$page+1) ?>" class="page-btn <?= $page===$total_pages?'disabled':'' ?>">&gt;</a>
        </div>
      </div>
      <?php endif; ?>

    </div>
  </main>
</div>

<!-- Add/Edit Modal -->
<div class="modal-backdrop" id="courseModal" style="display:none" onclick="if(event.target===this)closeModal()">
  <div class="modal-box wide">
    <button class="modal-close" onclick="closeModal()">✕</button>
    <h2 class="modal-title" id="modalTitle">Add New Course</h2>
    <p class="modal-subtitle" id="modalSubtitle">Fill in the information to add new Course</p>

    <input type="hidden" id="courseId">
    <div class="form-group">
      <label class="form-label">Course Name</label>
      <input type="text" class="form-input" id="f_name" placeholder="e.g. Potions">
    </div>
    <div class="form-group">
      <label class="form-label">Professor</label>
      <input type="text" class="form-input" id="f_prof" placeholder="e.g. Professor Snape">
    </div>
    <div class="form-group">
      <label class="form-label">Description</label>
      <textarea class="form-input" id="f_desc" rows="3" placeholder="Course description..." style="resize:vertical"></textarea>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label class="form-label">Level</label>
        <select class="form-input" id="f_diff">
          <option value="Beginner">Beginner</option>
          <option value="Intermediate">Intermediate</option>
          <option value="Advanced">Advanced</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">XP Reward</label>
        <input type="number" class="form-input" id="f_xp" value="200" min="1" max="9999">
      </div>
    </div>
    <div class="form-btns">
      <button type="button" class="btn-primary" onclick="closeModal()">Cancel</button>
      <button type="button" class="btn-primary" id="saveBtn" onclick="saveCourse()">Save Course</button>
    </div>
  </div>
</div>

<!-- Delete Modal -->
<div class="modal-backdrop" id="deleteModal" style="display:none" onclick="if(event.target===this)closeDeleteModal()">
  <div class="modal-box" style="max-width:380px;text-align:center">
    <button class="modal-close" onclick="closeDeleteModal()">✕</button>
    <div style="font-size:2.5rem;margin-bottom:12px">⚠️</div>
    <h2 class="modal-title">Delete Course?</h2>
    <p id="deleteMsg" style="font-family:var(--font-pop);font-size:0.82rem;color:var(--tan);margin:12px 0 24px"></p>
    <div class="form-btns" style="justify-content:center">
      <button type="button" class="btn-primary" onclick="closeDeleteModal()">Cancel</button>
      <button type="button" class="btn-primary" id="confirmDelBtn" onclick="doDelete()">Delete</button>
    </div>
  </div>
</div>

<div class="toast-container" id="toastContainer"></div>
<script src="../../assets/js/main.js"></script>
<script>
let deleteId = null;

function openAddModal() {
  document.getElementById('modalTitle').textContent    = 'Add New Course';
  document.getElementById('modalSubtitle').textContent = 'Fill in the information to add new Course';
  document.getElementById('courseId').value = '';
  document.getElementById('f_name').value  = '';
  document.getElementById('f_prof').value  = '';
  document.getElementById('f_desc').value  = '';
  document.getElementById('f_diff').value  = 'Beginner';
  document.getElementById('f_xp').value    = '200';
  document.getElementById('courseModal').style.display = 'flex';
}
function openEditModal(d) {
  document.getElementById('modalTitle').textContent    = 'Edit Course';
  document.getElementById('modalSubtitle').textContent = 'Update course information';
  document.getElementById('courseId').value = d.id;
  document.getElementById('f_name').value  = d.name;
  document.getElementById('f_prof').value  = d.prof;
  document.getElementById('f_desc').value  = d.desc;
  document.getElementById('f_diff').value  = d.diff;
  document.getElementById('f_xp').value    = d.xp;
  document.getElementById('courseModal').style.display = 'flex';
}
async function saveCourse() {
  const id  = document.getElementById('courseId').value;
  const btn = document.getElementById('saveBtn');
  btn.textContent='Saving...'; btn.disabled=true;
  const fd = new FormData();
  fd.append('action',      id ? 'edit' : 'add');
  if (id) fd.append('id', id);
  fd.append('course_name', document.getElementById('f_name').value);
  fd.append('professor',   document.getElementById('f_prof').value);
  fd.append('description', document.getElementById('f_desc').value);
  fd.append('difficulty',  document.getElementById('f_diff').value);
  fd.append('xp_reward',   document.getElementById('f_xp').value);
  try {
    const r = await fetch('../../actions/course_crud.php', {method:'POST',body:fd});
    const d = await r.json();
    if (d.success) { showToast('yes'+d.message,'success'); closeModal(); setTimeout(()=>location.reload(),900); }
    else { showToast(''+d.message,'error'); btn.textContent='Save Course'; btn.disabled=false; }
  } catch { showToast(' Connection error','error'); btn.textContent='Save Course'; btn.disabled=false; }
}
function confirmDelete(id, name) {
  deleteId = id;
  document.getElementById('deleteMsg').textContent = 'Yakin hapus course "'+name+'"?';
  document.getElementById('deleteModal').style.display='flex';
}
async function doDelete() {
  const btn=document.getElementById('confirmDelBtn'); btn.textContent='Deleting...'; btn.disabled=true;
  const fd=new FormData(); fd.append('action','delete'); fd.append('id',deleteId);
  try {
    const r=await fetch('../../actions/course_crud.php',{method:'POST',body:fd});
    const d=await r.json();
    if (d.success){showToast('🗑️ '+d.message,'success');closeDeleteModal();setTimeout(()=>location.reload(),900);}
    else{showToast(' '+d.message,'error');btn.textContent='Delete';btn.disabled=false;}
  } catch{showToast(' Connection error','error');btn.textContent='Delete';btn.disabled=false;}
}
function closeModal(){document.getElementById('courseModal').style.display='none';}
function closeDeleteModal(){document.getElementById('deleteModal').style.display='none';deleteId=null;}
<?php if($open_add):?>window.addEventListener('DOMContentLoaded',()=>openAddModal());<?php endif;?>
</script>
</body>
</html>
