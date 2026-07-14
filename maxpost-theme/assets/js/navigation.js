(() => {
  'use strict';

  const toggle = document.querySelector('.nav-toggle');
  const navigation = document.querySelector('.site-nav');

  if (!toggle || !navigation) {
    return;
  }

  const closeNavigation = () => {
    navigation.classList.remove('is-open');
    toggle.setAttribute('aria-expanded', 'false');
  };

  toggle.addEventListener('click', () => {
    const isOpen = navigation.classList.toggle('is-open');
    toggle.setAttribute('aria-expanded', String(isOpen));
  });

  navigation.addEventListener('click', (event) => {
    if (event.target.closest('a')) {
      closeNavigation();
    }
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
      closeNavigation();
      toggle.focus();
    }
  });

  document.addEventListener('click', (event) => {
    if (!navigation.contains(event.target) && !toggle.contains(event.target)) {
      closeNavigation();
    }
  });
})();
