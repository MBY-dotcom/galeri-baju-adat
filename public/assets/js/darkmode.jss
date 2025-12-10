// public/assets/js/darkmode.js
(function () {
  const ROOT = document.documentElement;

  function apply() {
    const mode = localStorage.getItem('darkMode');
    if (mode === 'on') {
      ROOT.classList.add('dark');
    } else {
      ROOT.classList.remove('dark');
    }
  }

  window.toggleDarkMode = function () {
    const isDark = ROOT.classList.contains('dark');
    localStorage.setItem('darkMode', isDark ? 'off' : 'on');
    apply();
  };

  // jalankan saat page load
  document.addEventListener('DOMContentLoaded', () => {
    apply();
    const btn = document.getElementById('darkModeToggle');
    if (btn) btn.textContent = ROOT.classList.contains('dark') ? '☀️' : '🌙';
  });
})();
