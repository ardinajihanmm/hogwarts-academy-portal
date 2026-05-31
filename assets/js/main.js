(function initCursor() {
  const dot  = document.getElementById('cursorDot');
  const ring = document.getElementById('cursorRing');
  if (!dot || !ring) return;
 
  let mx = 0, my = 0, rx = 0, ry = 0;
 
  document.addEventListener('mousemove', e => { mx = e.clientX; my = e.clientY; });
 
  function animCursor() {
    dot.style.left = mx + 'px'; dot.style.top = my + 'px';
    rx += (mx - rx) * 0.13; ry += (my - ry) * 0.13;
    ring.style.left = rx + 'px'; ring.style.top = ry + 'px';
    requestAnimationFrame(animCursor);
  }
  animCursor();
 
  document.querySelectorAll('a, button, .btn, .house-card, label').forEach(el => {
    el.addEventListener('mouseenter', () => {
      ring.style.width = '46px'; ring.style.height = '46px';
      ring.style.borderColor = 'rgba(255,195,0,0.85)';
    });
    el.addEventListener('mouseleave', () => {
      ring.style.width = '30px'; ring.style.height = '30px';
      ring.style.borderColor = 'rgba(255,195,0,0.5)';
    });
  });
})();
 
// ── Magic Ripple on click ──
document.addEventListener('click', e => {
  const r = document.createElement('div');
  r.className = 'ripple';
  r.style.left = e.clientX + 'px';
  r.style.top  = e.clientY + 'px';
  document.body.appendChild(r);
  setTimeout(() => r.remove(), 700);
});
 
// ── Generate Floating Particles ──
(function initParticles() {
  const container = document.getElementById('particles');
  if (!container) return;
  for (let i = 0; i < 30; i++) {
    const p = document.createElement('div');
    p.className = 'particle';
    const sz = Math.random() * 3.5 + 1;
    p.style.cssText = `
      width:${sz}px; height:${sz}px;
      left:${Math.random() * 100}%;
      bottom:-5%;
      animation-duration:${Math.random() * 8 + 5}s;
      animation-delay:${Math.random() * 12}s;
    `;
    container.appendChild(p);
  }
})();