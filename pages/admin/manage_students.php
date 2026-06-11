<?php
session_start();
require_once '../../config/auth.php';
requireAdmin();
require_once '../../config/database.php';

$search   = trim($_GET['search'] ?? '');
$page     = max(1, (int)($_GET['page'] ?? 1));
$per_page = 8;

try {
  $db = getDB();
  $where  = "WHERE role='student'";
  $params = [];
  if ($search) { $where .= ' AND (username LIKE ? OR email LIKE ?)'; $params = ["%$search%", "%$search%"]; }

  $total    = (int)$db->prepare("SELECT COUNT(*) FROM users $where")->execute($params) ? $db->prepare("SELECT COUNT(*) FROM users $where")->execute($params) : 0;
  $cnt_stmt = $db->prepare("SELECT COUNT(*) FROM users $where");
  $cnt_stmt->execute($params);
  $total    = (int)$cnt_stmt->fetchColumn();

  $total_pages = max(1, ceil($total / $per_page));
  $offset      = ($page - 1) * $per_page;
  $stmt = $db->prepare("SELECT * FROM users $where ORDER BY created_at DESC LIMIT $per_page OFFSET $offset");
  $stmt->execute($params);
  $students = $stmt->fetchAll();
} catch (Exception $e) { $students = []; $total = 0; $total_pages = 1; }

$open_add = isset($_GET['action']) && $_GET['action'] === 'add';

$active_page   = 'students';
$sidebar_depth = '../../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Manage Students – Hogwarts</title>
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
        <h1 class="page-title">Manage Students</h1>
        <p class="page-subtitle">View, edit, and manage all students</p>
      </div>

      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:22px;gap:16px;flex-wrap:wrap">
        <form method="GET">
          <div class="search-bar" style="min-width:280px">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            <input type="text" name="search" placeholder="Search Students..." value="<?= htmlspecialchars($search) ?>">
          </div>
        </form>
        <button class="btn-add" onclick="openAddModal()">+ Add student</button>
      </div>

      <div class="data-table-wrap">
        <table class="data-table">
          <thead>
            <tr>
              <th>No</th>
              <th>USERNAME</th>
              <th>HOUSE</th>
              <th>XP</th>
              <th>LEVEL</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($students)): ?>
            <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--tan);font-family:var(--font-cinzel);font-size:0.8rem">No students found.</td></tr>
            <?php else: foreach ($students as $i => $s): ?>
            <tr>
              <td style="color:var(--tan);font-size:0.78rem"><?= ($page-1)*$per_page + $i + 1 ?></td>
              <td>
                <div style="font-weight:500"><?= htmlspecialchars($s['username']) ?></div>
                <div style="font-size:0.72rem;color:var(--tan);opacity:.75"><?= htmlspecialchars($s['email']) ?></div>
              </td>
              <td>
                <?php if ($s['house']): ?>

                <?php
                $houseStyles = [
                  'Gryffindor' => ['bg' => '#BE4916', 'color' => '#F6E9C8'],
                 'Slytherin'  => ['bg' => '#379938', 'color' => '#F6E9C8'],
                 'Ravenclaw'  => ['bg' => '#4C5ED9', 'color' => '#F6E9C8'],
                 'Hufflepuff' => ['bg' => '#FBD747', 'color' => '#2B2B2B']
                ];

                  $house = $houseStyles[$s['house']] ?? ['bg' => '#555', 'color' => '#F6E9C8'];
                ?>

                  <span style=" font-family:var(--font-cinzel); font-size:1rem;font-weight:800;color:<?= $house['bg'] ?>;">
                  <?= $s['house'] ?>
                </span>
                <?php else: echo '<span style="color:var(--tan);opacity:.5;font-size:1rem">—</span>'; endif; ?>
              </td>
              <td style="color:var(--gold);font-family:var(--font-cinzel);font-size:1rem;font-weight:600"><?= number_format($s['xp']) ?></td>
              <td style="font-family:var(--font-cinzel);font-size:1rem;color:var(--cream);opacity:.8"><?= htmlspecialchars($s['level']) ?></td>
              <td>
                <button class="btn-edit" onclick="openEditModal(<?= $s['id'] ?>)">Edit</button>
                <button class="btn-del"  onclick="confirmDelete(<?= $s['id'] ?>, '<?= htmlspecialchars(addslashes($s['username'])) ?>')">Delete</button>
              </td>
            </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>

      <?php if ($total_pages > 1): ?>
      <div class="pagination" style="margin-top:20px">
        <p class="pagination-info">Total: <?= $total ?> students</p>
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

<div class="modal-backdrop" id="studentModal" style="display:none" onclick="if(event.target===this)closeModal()">
  <div class="modal-box">
    <button class="modal-close" onclick="closeModal()">✕</button>
    <h2 class="modal-title" id="modalTitle">Add New Student</h2>
    <p class="modal-subtitle" id="modalSubtitle">Fill in the information to add new student</p>

    <input type="hidden" id="studentId">
    <div class="form-group">
      <label class="form-label">Username</label>
      <input type="text" class="form-input" id="f_username" placeholder="Enter username">
    </div>
    <div class="form-group">
      <label class="form-label">Email</label>
      <input type="email" class="form-input" id="f_email" placeholder="Enter email">
    </div>
    <div class="form-group">
      <label class="form-label" id="passLabel">Password</label>
      <input type="password" class="form-input" id="f_password" placeholder="Min. 6 characters">
    </div>
    <div class="form-row">
      <div class="form-group">
        <label class="form-label">House</label>
        <select class="form-input" id="f_house">
          <option value="">— No House —</option>
          <option value="Gryffindor">Gryffindor</option>
          <option value="Slytherin">Slytherin</option>
          <option value="Ravenclaw">Ravenclaw</option>
          <option value="Hufflepuff">Hufflepuff</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Role</label>
        <select class="form-input" id="f_role">
          <option value="student">Student</option>
          <option value="admin">Admin</option>
        </select>
      </div>
    </div>
    <div class="form-btns">
      <button type="button" class="btn-primary" onclick="closeModal()">Cancel</button>
      <button type="button" class="btn-primary" id="saveBtn" onclick="saveStudent()">Save Student </button>
    </div>
  </div>
</div>


<div class="modal-backdrop" id="deleteModal" style="display:none" onclick="if(event.target===this)closeDeleteModal()">
  <div class="modal-box" style="max-width:380px;text-align:center">
    <button class="modal-close" onclick="closeDeleteModal()">✕</button>
    <div style="font-size:2.5rem;margin-bottom:12px">⚠️</div>
    <h2 class="modal-title">Delete Student?</h2>
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
  document.getElementById('modalTitle').textContent   = 'Add New Student';
  document.getElementById('modalSubtitle').textContent = 'Fill in the information to add new student';
  document.getElementById('studentId').value  = '';
  document.getElementById('f_username').value = '';
  document.getElementById('f_email').value    = '';
  document.getElementById('f_password').value = '';
  document.getElementById('f_house').value    = '';
  document.getElementById('f_role').value     = 'student';
  document.getElementById('passLabel').textContent = 'Password';
  document.getElementById('f_password').placeholder = 'Min. 6 characters';
  document.getElementById('studentModal').style.display = 'flex';
}

async function openEditModal(id) {
  document.getElementById('modalTitle').textContent    = 'Edit Student';
  document.getElementById('modalSubtitle').textContent = 'Update student information';
  document.getElementById('passLabel').textContent     = 'Password (kosong = tidak diganti)';
  document.getElementById('f_password').placeholder   = 'Leave empty to keep current';
  try {
    const r = await fetch('../../actions/student_crud.php?action=get&id=' + id);
    const d = await r.json();
    document.getElementById('studentId').value  = d.id;
    document.getElementById('f_username').value = d.username;
    document.getElementById('f_email').value    = d.email;
    document.getElementById('f_password').value = '';
    document.getElementById('f_house').value    = d.house || '';
    document.getElementById('f_role').value     = d.role;
    document.getElementById('studentModal').style.display = 'flex';
  } catch { showToast(' Gagal load data', 'error'); }
}

async function saveStudent() {
  const id  = document.getElementById('studentId').value;
  const btn = document.getElementById('saveBtn');
  btn.textContent = 'Saving...'; btn.disabled = true;

  const fd = new FormData();
  fd.append('action',   id ? 'edit' : 'add');
  if (id) fd.append('id', id);
  fd.append('username', document.getElementById('f_username').value);
  fd.append('email',    document.getElementById('f_email').value);
  fd.append('password', document.getElementById('f_password').value);
  fd.append('house',    document.getElementById('f_house').value);
  fd.append('role',     document.getElementById('f_role').value);

  try {
    const r = await fetch('../../actions/student_crud.php', {method:'POST', body:fd});
    const d = await r.json();
    if (d.success) { showToast(' ' + d.message, 'success'); closeModal(); setTimeout(()=>location.reload(), 900); }
    else { showToast('' + d.message, 'error'); btn.textContent='Save Student'; btn.disabled=false; }
  } catch { showToast(' Connection error','error'); btn.textContent='Save Student'; btn.disabled=false; }
}

function confirmDelete(id, name) {
  deleteId = id;
  document.getElementById('deleteMsg').textContent = 'Yakin hapus student "' + name + '"? Tidak bisa dibatalkan.';
  document.getElementById('deleteModal').style.display = 'flex';
}
async function doDelete() {
  const btn = document.getElementById('confirmDelBtn');
  btn.textContent = 'Deleting...'; btn.disabled = true;
  const fd = new FormData(); fd.append('action','delete'); fd.append('id', deleteId);
  try {
    const r = await fetch('../../actions/student_crud.php', {method:'POST', body:fd});
    const d = await r.json();
    if (d.success) { showToast(' ' + d.message, 'success'); closeDeleteModal(); setTimeout(()=>location.reload(), 900); }
    else { showToast(' ' + d.message,'error'); btn.textContent='Delete'; btn.disabled=false; }
  } catch { showToast(' Connection error','error'); btn.textContent='Delete'; btn.disabled=false; }
}

function closeModal()       { document.getElementById('studentModal').style.display='none'; }
function closeDeleteModal() { document.getElementById('deleteModal').style.display='none'; deleteId=null; }

<?php if ($open_add): ?>
window.addEventListener('DOMContentLoaded', () => openAddModal());
<?php endif; ?>
</script>
</body>
</html>
