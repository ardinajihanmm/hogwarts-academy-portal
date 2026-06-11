document.addEventListener('DOMContentLoaded', function(){
  
  const hamburger = document.getElementById('hamburgerBtn');
  const sidebar   = document.getElementById('sidebar');
  const overlay   = document.getElementById('sidebarOverlay');
  if(hamburger && sidebar){
    hamburger.addEventListener('click', ()=>{ sidebar.classList.toggle('open'); overlay?.classList.toggle('active'); });
    overlay?.addEventListener('click', ()=>{ sidebar.classList.remove('open'); overlay.classList.remove('active'); });
  }

 
  const sidebarPhotoInput = document.getElementById('sidebarPhotoInput');
  if(sidebarPhotoInput){
    sidebarPhotoInput.addEventListener('change', function(){
      const f=this.files[0]; if(!f) return;
      if(f.size>5*1024*1024){ showToast(' File max 5MB','error'); return; }
      const r=new FileReader();
      r.onload=e=>{ const img=document.getElementById('sidebarPhoto'); if(img) img.src=e.target.result; };
      r.readAsDataURL(f);
    
      const fd=new FormData(); fd.append('photo',f);
      fetch('../actions/update_profile.php',{method:'POST',body:fd})
        .then(r=>r.text()).catch(()=>{});
    });
  }


  document.addEventListener('keydown', e=>{
    if(e.key==='Escape') document.querySelectorAll('.modal-backdrop').forEach(m=>m.style.display='none');
  });

  // Animate stagger on load
  document.querySelectorAll('.stagger').forEach(container=>{
    [...container.children].forEach((child,i)=>{
      child.style.opacity='0';
      child.style.animation=`fadeInUp .45s ease ${i*0.07}s forwards`;
    });
  });
});

function showToast(message, type='success'){
  let container=document.getElementById('toastContainer');
  if(!container) return;
  const t=document.createElement('div');
  t.className=`toast ${type}`;
  t.innerHTML=`<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">${type==='success'?'<polyline points="20 6 9 17 4 12"/>':'<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/>'}</svg>${message}`;
  container.appendChild(t);
  setTimeout(()=>{ t.style.opacity='0'; t.style.transform='translateX(20px)'; t.style.transition='all .3s'; setTimeout(()=>t.remove(),300); },3200);
}
