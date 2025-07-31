console.log("✅ scroll.js chargé !");

  
  const headerBg = document.querySelector('.header-bg');
  const header = document.querySelector('.header');

  window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
      headerBg.classList.add('scrolled');
      header.classList.add('scrolled');
    } else {
      headerBg.classList.remove('scrolled');
      header.classList.remove('scrolled');
    }
  });

