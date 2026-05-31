function getField(id)  { return document.getElementById(id); }
function getHint(id)   { return document.getElementById(id + 'Hint'); }
function getStatus(id) { return document.getElementById(id + 'Status'); }
 
function setValid(id) {
  const el = getField(id), hint = getHint(id), status = getStatus(id);
  el.classList.remove('err'); el.classList.add('ok');
  if (hint)   hint.style.display = 'none';
  if (status) { status.textContent = '✓'; status.className = 'field-status valid'; }
}
 
function setError(id, msg) {
  const el = getField(id), hint = getHint(id), status = getStatus(id);
  el.classList.remove('ok'); el.classList.add('err');
  if (hint)   { hint.textContent = msg; hint.style.display = 'block'; }
  if (status) { status.textContent = '✗'; status.className = 'field-status invalid'; }
}
 
function clearField(id) {
  const el = getField(id), hint = getHint(id), status = getStatus(id);
  el.classList.remove('ok', 'err');
  if (hint)   hint.style.display = 'none';
  if (status) { status.textContent = ''; status.className = 'field-status'; }
}
 
function validateUsername(live = false) {
  const v = getField('username').value;
  if (!v && live) { clearField('username'); return true; }
  if (!v) { setError('username', '⚠ Nama wizard tidak boleh kosong.'); return false; }
  if (v.trim().length < 3) { setError('username', '⚠ Minimal 3 karakter.'); return false; }
  setValid('username'); return true;
}
 
function validatePassword(live = false) {
  const v = getField('password').value;
  if (!v && live) { clearField('password'); return true; }
  if (!v) { setError('password', '⚠ Mantra rahasia tidak boleh kosong.'); return false; }
  setValid('password'); return true;
}
 
// Live
getField('username').addEventListener('input', () => validateUsername(true));
getField('password').addEventListener('input', () => validatePassword(true));
getField('username').addEventListener('blur',  () => { if (getField('username').value) validateUsername(); });
getField('password').addEventListener('blur',  () => { if (getField('password').value) validatePassword(); });
 
// Toggle
document.querySelectorAll('.toggle-pw').forEach(btn => {
  btn.addEventListener('click', () => {
    const inp = getField(btn.dataset.target);
    inp.type = inp.type === 'password' ? 'text' : 'password';
    btn.textContent = inp.type === 'password' ? '👁' : '✉';
  });
});
 
// Submit
function handleLogin() {
  const v1 = validateUsername();
  const v2 = validatePassword();
 
  if (!v1 || !v2) {
    const card = getField('loginCard');
    card.classList.remove('shake'); void card.offsetWidth; card.classList.add('shake');
    const errEl = document.querySelector('input.err');
    if (errEl) errEl.focus();
    return;
  }
 
  const btn = getField('loginBtn');
  btn.innerHTML = '<span class="btn-spinner"></span> Opening the portal…';
  btn.disabled = true;
  setTimeout(() => { btn.innerHTML = 'Entering the castle…'; }, 1000);
}
 
document.addEventListener('keydown', e => { if (e.key === 'Enter') handleLogin(); });
 