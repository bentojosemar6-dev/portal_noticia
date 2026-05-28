</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  if(typeof lucide!=='undefined'){lucide.createIcons();}
  var toggle=document.getElementById('sidebar-toggle');
  var sidebar=document.getElementById('sidebar-admin');
  var overlay=document.getElementById('sidebar-overlay');
  if(toggle&&sidebar&&overlay){
    function closeSidebar(){sidebar.classList.remove('open');overlay.classList.remove('open');}
    toggle.addEventListener('click',function(){sidebar.classList.toggle('open');overlay.classList.toggle('open');});
    overlay.addEventListener('click',closeSidebar);
    document.addEventListener('keydown',function(e){if(e.key==='Escape')closeSidebar();});
  }
});
</script>
</body>
</html>
