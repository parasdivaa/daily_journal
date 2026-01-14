// script.js — My Daily Journal (Monochrome Elegant)

/* --- Preloader / Logo fade-in --- */
document.addEventListener('DOMContentLoaded', () => {
  const pre = document.getElementById('preloader');
  if (pre) {
    setTimeout(() => {
      pre.style.opacity = '0';
      pre.style.pointerEvents = 'none';
      setTimeout(() => pre.remove(), 500);
    }, 700);
  }

  // set initial theme from localStorage
  applySavedTheme();

  // set active nav link based on filename
  setActiveNav();
});

/* --- Theme toggle (switch) --- */
const THEME_KEY = 'mdj_theme';

function applySavedTheme() {
  const saved = localStorage.getItem(THEME_KEY);
  if (saved === 'dark') {
    document.documentElement.classList.add('dark');
    updateSwitchThumb(true);
  } else {
    document.documentElement.classList.remove('dark');
    updateSwitchThumb(false);
  }
}

function updateSwitchThumb(isDark) {
  // update all switches on page
  document.querySelectorAll('.switch-thumb').forEach(t => {
    if (isDark) t.style.left = '27px';
    else t.style.left = '3px';
  });
  document.querySelectorAll('.theme-switch').forEach(btn => {
    btn.setAttribute('aria-pressed', isDark ? 'true' : 'false');
  });
}

document.querySelectorAll('.theme-switch').forEach(btn => {
  btn.addEventListener('click', () => {
    const nowDark = document.documentElement.classList.toggle('dark');
    localStorage.setItem(THEME_KEY, nowDark ? 'dark' : 'light');
    updateSwitchThumb(nowDark);
  });
});

/* --- Mark active nav link --- */
function setActiveNav() {
  const links = document.querySelectorAll('.nav-link');
  const path = location.pathname.split('/').pop() || 'index.html';
  links.forEach(a => {
    const href = a.getAttribute('href');
    if (href === path || (href === 'index.html' && path === '')) {
      a.classList.add('active');
    } else {
      a.classList.remove('active');
    }
  });
}