(() => {
  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const table = document.querySelector('[data-software-table]');
  if (table) {
    const search = table.querySelector('[data-table-search]');
    const category = table.querySelector('[data-table-category]');
    const rows = [...table.querySelectorAll('.software-table__row')];
    const empty = table.querySelector('[data-table-empty]');
    const filter = () => {
      const query = (search?.value || '').trim().toLowerCase();
      const selected = category?.value || '';
      let visible = 0;
      rows.forEach((row) => {
        const show = (!query || row.dataset.name.includes(query)) && (!selected || row.dataset.category === selected);
        row.hidden = !show;
        if (show) visible += 1;
      });
      if (empty) empty.hidden = visible !== 0;
    };
    search?.addEventListener('input', filter);
    category?.addEventListener('change', filter);
  }

  if (!reduceMotion && 'IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-revealed');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12 });
    document.querySelectorAll('.software-card, .category-card, .feature-principle, .whats-new-panel, .product-section').forEach((element) => {
      element.classList.add('reveal-item');
      observer.observe(element);
    });
  }
})();
