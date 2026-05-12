// ============================================================
// Shared Auth & Navbar Logic
// Used by all pages to render the top bar and manage auth state.
// ============================================================

/**
 * Initialize the top navigation bar with buttons and login status.
 * Call this on every page after the DOM is ready.
 */
function initNavbar() {
  const navbar = document.getElementById('navbar');
  if (!navbar) return;

  const pages = [
    { label: 'Home', href: 'index.html' },
    { label: 'Login', href: 'login.html' },
    { label: 'Register', href: 'register.html' },
    { label: 'Leaderboard', href: 'leaderboard.html' },
    { label: 'Play', href: 'game.html' },
    { label: 'Profile', href: 'profile.html' },
    { label: 'Logout', href: '#', id: 'btn-logout' }
  ];

  let html = '<div class="button-container">';
  pages.forEach(p => {
    const idAttr = p.id ? ` id="${p.id}"` : '';
    html += `<button${idAttr} onclick="window.location.href='${p.href}'">${p.label}</button>`;
  });

  // Login status indicator (updated by checkAuth)
  html += '<div id="login-status" class="login-status" style="background-color: red;">Not logged in</div>';
  html += '</div>';

  navbar.innerHTML = html;

  // Attach logout handler
  const logoutBtn = document.getElementById('btn-logout');
  if (logoutBtn) {
    logoutBtn.addEventListener('click', function(e) {
      e.preventDefault();
      handleLogout();
    });
  }
}

/**
 * Check current auth state and update the navbar status indicator.
 */
async function checkAuth() {
  const statusEl = document.getElementById('login-status');
  if (!statusEl) return;

  const { data: { session } } = await supabase.auth.getSession();

  if (session) {
    statusEl.textContent = 'Logged in';
    statusEl.style.backgroundColor = 'green';
  } else {
    statusEl.textContent = 'Not logged in';
    statusEl.style.backgroundColor = 'red';
  }
}

/**
 * Sign out and redirect to homepage.
 */
async function handleLogout() {
  await supabase.auth.signOut();
  window.location.href = 'index.html';
}

/**
 * Redirect to login page if user is not authenticated.
 * Call this on pages that require auth.
 */
async function requireAuth() {
  const { data: { session } } = await supabase.auth.getSession();
  if (!session) {
    window.location.href = 'login.html';
    return null;
  }
  return session;
}

// Listen for auth state changes (login/logout in another tab, etc.)
supabase.auth.onAuthStateChange((event, session) => {
  const statusEl = document.getElementById('login-status');
  if (!statusEl) return;

  if (session) {
    statusEl.textContent = 'Logged in';
    statusEl.style.backgroundColor = 'green';
  } else {
    statusEl.textContent = 'Not logged in';
    statusEl.style.backgroundColor = 'red';
  }
});

// Init navbar and check auth on page load
document.addEventListener('DOMContentLoaded', function() {
  initNavbar();
  checkAuth();
});
