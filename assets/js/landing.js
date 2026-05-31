(function initStars() {
  const container = document.getElementById('stars');
  if (!container) return;
  for (let i = 0; i < 90; i++) {
    const s = document.createElement('div');
    s.className = 'star';
    const sz = Math.random() * 2.2 + .8;
    s.style.cssText = `
      width:${sz}px; height:${sz}px;
      left:${Math.random() * 100}%;
      top:${Math.random() * 100}%;
      animation-duration:${Math.random() * 3 + 2}s;
      animation-delay:${Math.random() * 6}s;
    `;
    container.appendChild(s);
  }
})();
 
(function initParallax() {
  const heroBg = document.querySelector('.hero-bg');
  if (!heroBg) return;
  document.addEventListener('mousemove', e => {
    const xPct = (e.clientX / window.innerWidth  - 0.5) * 9;
    const yPct = (e.clientY / window.innerHeight - 0.5) * 5;
    heroBg.style.transform = `scale(1.08) translate(${xPct}px, ${yPct}px)`;
  });
})();
 
(function initScrollLink() {
  const aboutLink = document.querySelector('a[href="#about"]');
  if (!aboutLink) return;
  aboutLink.addEventListener('click', e => {
    e.preventDefault();
    document.getElementById('about').scrollIntoView({ behavior: 'smooth' });
  });
})();
 
(function initReveal() {
  const targets = document.querySelectorAll('.about-card, .house-card');
  if (!targets.length) return;
 
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.15 });
 
  targets.forEach(el => observer.observe(el));
})();