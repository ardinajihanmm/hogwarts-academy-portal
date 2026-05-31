const RULES = {
  username: {
    validate: v => v.trim().length >= 3 && /^[a-zA-Z0-9_]+$/.test(v.trim()),
    messages: {
      empty:   '⚠ Nama wizard tidak boleh kosong.',
      short:   '⚠ Nama wizard minimal 3 karakter.',
      invalid: '⚠ Hanya huruf, angka, dan underscore yang diizinkan.'
    }
  },
  email: {
    validate: v => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim()),
    messages: {
      empty:   '⚠ Alamat owl-post (email) tidak boleh kosong.',
      invalid: '⚠ Format email tidak valid. Contoh: wizard@hogwarts.edu'
    }
  },
  password: {
    validate: v => v.length >= 8,
    messages: {
      empty: '⚠ Mantra rahasia (password) tidak boleh kosong.',
      weak:  '⚠ Password minimal 8 karakter.'
    }
  },
  confirm: {
    validate: (v, ref) => v === ref && v !== '',
    messages: {
      empty:    '⚠ Konfirmasi mantra tidak boleh kosong.',
      mismatch: '⚠ Mantra tidak cocok. Coba lagi, wizard!'
    }
  },
  role: {
    validate: v => v !== '',
    messages: { empty: '⚠ Pilih rumah / peran kamu terlebih dahulu.' }
  }
};
 
function getField(id)   { return document.getElementById(id); }
function getHint(id)    { return document.getElementById(id + 'Hint'); }
function getStatus(id)  { return document.getElementById(id + 'Status'); }
 
function setValid(id) {
  const el = getField(id);
  const hint = getHint(id);
  const status = getStatus(id);
  el.classList.remove('err'); el.classList.add('ok');
  if (hint)   { hint.style.display = 'none'; }
  if (status) { status.textContent = '✓'; status.className = 'field-status valid'; }
}
 
function setError(id, msg) {
  const el = getField(id);
  const hint = getHint(id);
  const status = getStatus(id);
  el.classList.remove('ok'); el.classList.add('err');
  if (hint)   { hint.textContent = msg; hint.style.display = 'block'; }
  if (status) { status.textContent = '✗'; status.className = 'field-status invalid'; }
}
 
function clearField(id) {
  const el = getField(id);
  const hint = getHint(id);
  const status = getStatus(id);
  el.classList.remove('ok', 'err');
  if (hint)   { hint.style.display = 'none'; }
  if (status) { status.textContent = ''; status.className = 'field-status'; }
}
 
function validateUsername(live = false) {
  const v = getField('username').value;
  if (!v && live) { clearField('username'); return true; }
  if (!v)         { setError('username', RULES.username.messages.empty);   return false; }
  if (v.trim().length < 3) { setError('username', RULES.username.messages.short);   return false; }
  if (!/^[a-zA-Z0-9_]+$/.test(v.trim())) { setError('username', RULES.username.messages.invalid); return false; }
  setValid('username'); return true;
}
 
function validateEmail(live = false) {
  const v = getField('email').value;
  if (!v && live) { clearField('email'); return true; }
  if (!v)         { setError('email', RULES.email.messages.empty);   return false; }
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim())) { setError('email', RULES.email.messages.invalid); return false; }
  setValid('email'); return true;
}
 
function validatePassword(live = false) {
  const v = getField('password').value;
  if (!v && live) { clearField('password'); updateStrength(''); return true; }
  if (!v)         { setError('password', RULES.password.messages.empty); return false; }
  if (v.length < 8) { setError('password', RULES.password.messages.weak); return false; }
  setValid('password');
  if (getField('confirm').value) validateConfirm(true);
  return true;
}
 
function validateConfirm(live = false) {
  const v   = getField('confirm').value;
  const ref = getField('password').value;
  if (!v && live) { clearField('confirm'); return true; }
  if (!v)         { setError('confirm', RULES.confirm.messages.empty);    return false; }
  if (v !== ref)  { setError('confirm', RULES.confirm.messages.mismatch); return false; }
  setValid('confirm'); return true;
}
 
function validateRole(live = false) {
  const v = getField('role').value;
  if (!v && live) { clearField('role'); return true; }
  if (!v)         { setError('role', RULES.role.messages.empty); return false; }
  setValid('role'); return true;
}
 
function updateStrength(v) {
  let score = 0;
  if (v.length >= 8)          score++;
  if (/[A-Z]/.test(v))        score++;
  if (/[0-9]/.test(v))        score++;
  if (/[^A-Za-z0-9]/.test(v)) score++;
 
  const labels = ['', 'Lemah', 'Cukup', 'Kuat', 'Sangat Kuat'];
  const colors = ['', '#ff4444', '#ff8800', '#ffc300', '#44ff88'];
 
  for (let i = 1; i <= 4; i++) {
    const seg = document.getElementById('s' + i);
    seg.style.background = i <= score ? colors[score] : 'rgba(246,233,200,0.15)';
    seg.style.boxShadow  = i <= score ? `0 0 6px ${colors[score]}55` : 'none';
  }
 
  const label = document.getElementById('strengthLabel');
  if (label) {
    label.textContent = v ? labels[score] : '';
    label.style.color = colors[score] || 'transparent';
  }
}
 
getField('username').addEventListener('input',  () => validateUsername(true));
getField('email').addEventListener('input',     () => validateEmail(true));
getField('password').addEventListener('input',  function() {
  updateStrength(this.value);
  if (this.value) validatePassword(true);
  else clearField('password');
});
getField('confirm').addEventListener('input',   () => validateConfirm(true));
getField('role').addEventListener('change',     () => validateRole(true));
 
getField('username').addEventListener('blur', () => { if (getField('username').value) validateUsername(); });
getField('email').addEventListener('blur',    () => { if (getField('email').value)    validateEmail(); });
getField('password').addEventListener('blur', () => { if (getField('password').value) validatePassword(); });
getField('confirm').addEventListener('blur',  () => { if (getField('confirm').value)  validateConfirm(); });
 
document.querySelectorAll('.toggle-pw').forEach(btn => {
  btn.addEventListener('click', () => {
    const inp = getField(btn.dataset.target);
    inp.type = inp.type === 'password' ? 'text' : 'password';
    btn.textContent = inp.type === 'password' ? '👁' : '✉';
  });
});
 
function handleRegister() {
  const v1 = validateUsername();
  const v2 = validateEmail();
  const v3 = validatePassword();
  const v4 = validateConfirm();
  const v5 = validateRole();
 
  if (!v1 || !v2 || !v3 || !v4 || !v5) {
    const errEl = document.querySelector('input.err, select.err');
    if (errEl) { errEl.classList.add('shake'); setTimeout(() => errEl.classList.remove('shake'), 500); errEl.focus(); }
    return;
  }
 
  const btn = getField('registerBtn');
  btn.innerHTML = '<span class="btn-spinner"></span> Sending acceptance letter…';
  btn.disabled = true;
  setTimeout(() => { window.location.href = 'login.php'; }, 1800);
}
 
document.addEventListener('keydown', e => { if (e.key === 'Enter') handleRegister(); });