document.addEventListener('DOMContentLoaded', function() {

  /* Reading progress bar */
  const progressBar = document.getElementById('readingProgressBar');
  if (progressBar) {
    window.addEventListener('scroll', function() {
      const scrollTop = window.scrollY;
      const docHeight = document.documentElement.scrollHeight - window.innerHeight;
      const progress = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
      progressBar.style.width = Math.min(progress, 100) + '%';
    });
  }

  /* Lazy loading images */
  const lazyImages = document.querySelectorAll('img[loading="lazy"]');
  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          const img = entry.target;
          img.src = img.dataset.src || img.src;
          observer.unobserve(img);
        }
      });
    }, { rootMargin: '200px' });
    lazyImages.forEach(function(img) { observer.observe(img); });
  }

  /* Mobile nav toggle */
  const toggle = document.querySelector('.navbar-toggle');
  const links = document.querySelector('.navbar-links');
  if (toggle && links) {
    toggle.addEventListener('click', function() {
      links.classList.toggle('active');
      if (links.classList.contains('active')) {
        links.style.display = 'flex';
        links.style.flexDirection = 'column';
        links.style.position = 'absolute';
        links.style.top = '60px';
        links.style.left = '0';
        links.style.right = '0';
        links.style.background = 'var(--color-bg)';
        links.style.padding = 'var(--space-md)';
        links.style.boxShadow = 'var(--shadow-nav)';
        links.style.zIndex = '99';
      } else {
        links.style.display = '';
      }
    });

    window.addEventListener('resize', function() {
      if (window.innerWidth > 768) {
        links.style.display = '';
        links.style.position = '';
        links.style.top = '';
        links.style.left = '';
        links.style.right = '';
        links.style.background = '';
        links.style.padding = '';
        links.style.boxShadow = '';
        links.style.zIndex = '';
        links.style.flexDirection = '';
      }
    });
  }

  /* Auto-hide alerts */
  document.querySelectorAll('.msg-sucesso, .msg-erro').forEach(function(el) {
    setTimeout(function() { el.style.opacity = '0'; el.style.transition = 'opacity 300ms'; }, 4000);
  });

  /* Lucide icons */
  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }

});

console.log('Portal IPIL carregado com sucesso.');
