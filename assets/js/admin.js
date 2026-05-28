document.addEventListener('DOMContentLoaded', function() {

  /* Slug generator */
  const tituloInput = document.getElementById('titulo');
  const slugInput = document.getElementById('slug');
  if (tituloInput && slugInput) {
    tituloInput.addEventListener('input', function() {
      const slug = this.value
        .toLowerCase()
        .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');
      slugInput.value = slug;
    });
  }

  /* Image preview */
  const imagemInput = document.getElementById('imagem_capa');
  const previewDiv = document.getElementById('img-preview');
  const previewImg = document.getElementById('preview-img');
  if (imagemInput && previewDiv && previewImg) {
    imagemInput.addEventListener('change', function() {
      if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          previewImg.src = e.target.result;
          previewDiv.style.display = 'block';
        };
        reader.readAsDataURL(this.files[0]);
      }
    });
  }

  /* Character counter */
  const resumoField = document.getElementById('resumo');
  const resumoCount = document.getElementById('resumo-count');
  if (resumoField && resumoCount) {
    resumoField.addEventListener('input', function() {
      resumoCount.textContent = this.value.length + '/300';
    });
  }

  /* Delete confirmation */
  document.querySelectorAll('form[onsubmit*="confirm"]').forEach(function(form) {
    form.addEventListener('submit', function(e) {
      if (!confirm(this.getAttribute('onsubmit').replace('return confirm(\'', '').replace('\')', ''))) {
        e.preventDefault();
      }
    });
  });

  /* Auto-save draft to localStorage */
  const criarForm = document.querySelector('form[name="criar_noticia"], form[action*="criar"]');
  if (criarForm) {
    const formKey = 'draft_noticia';
    const inputs = criarForm.querySelectorAll('input, textarea, select');
    setInterval(function() {
      const data = {};
      inputs.forEach(function(input) { data[input.name] = input.value; });
      localStorage.setItem(formKey, JSON.stringify(data));
    }, 60000);

    const saved = localStorage.getItem(formKey);
    if (saved) {
      const data = JSON.parse(saved);
      inputs.forEach(function(input) {
        if (data[input.name] && !input.value) input.value = data[input.name];
      });
    }
  }

});

console.log('Admin IPIL carregado.');
