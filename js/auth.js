// ============================================================
// Shared Auth & Navbar Logic
// Used by all pages to render the top bar and manage auth state.
// ============================================================

function initNavbar() {
  var navbar = document.getElementById('navbar');
  if (!navbar) return;

  var pages = [
    { label: 'Home', href: 'index.html' },
    { label: 'Login', href: 'login.html' },
    { label: 'Register', href: 'register.html' },
    { label: 'Leaderboard', href: 'leaderboard.html' },
    { label: 'Play', href: 'game.html' },
    { label: 'Profile', href: 'profile.html' },
    { label: 'Logout', href: '#', id: 'btn-logout' }
  ];

  var html = '<div class="button-container">';
  pages.forEach(function(p) {
    var idAttr = p.id ? ' id="' + p.id + '"' : '';
    html += '<button' + idAttr + ' onclick="window.location.href=\'' + p.href + '\'">' + p.label + '</button>';
  });

  html += '<div id="login-status" class="login-status" style="background-color: red;">Not logged in</div>';
  html += '</div>';

  navbar.innerHTML = html;

  var logoutBtn = document.getElementById('btn-logout');
  if (logoutBtn) {
    logoutBtn.addEventListener('click', function(e) {
      e.preventDefault();
      handleLogout();
    });
  }
}

function updateLoginStatus(loggedIn) {
  var statusEl = document.getElementById('login-status');
  if (!statusEl) return;
  if (loggedIn) {
    statusEl.textContent = 'Logged in';
    statusEl.style.backgroundColor = 'green';
  } else {
    statusEl.textContent = 'Not logged in';
    statusEl.style.backgroundColor = 'red';
  }
}

async function checkAuth() {
  if (typeof supabase === 'undefined') return;
  try {
    var _a = await supabase.auth.getSession();
    var session = _a.data.session;
    updateLoginStatus(!!session);
  } catch (e) {
    // supabase not configured yet — status remains "Not logged in"
  }
}

async function handleLogout() {
  if (typeof supabase === 'undefined') return;
  await supabase.auth.signOut();
  window.location.href = 'index.html';
}

async function requireAuth() {
  if (typeof supabase === 'undefined') {
    window.location.href = 'login.html';
    return null;
  }
  try {
    var _a = await supabase.auth.getSession();
    var session = _a.data.session;
    if (!session) {
      window.location.href = 'login.html';
      return null;
    }
    return session;
  } catch (e) {
    window.location.href = 'login.html';
    return null;
  }
}

// Listen for auth state changes
if (typeof supabase !== 'undefined') {
  try {
    supabase.auth.onAuthStateChange(function(event, session) {
      updateLoginStatus(!!session);
    });
  } catch (e) {}
}

// Initialize immediately (scripts are at bottom of body, DOM is ready)
initNavbar();
checkAuth();

// Fallback in case DOM wasn't ready yet
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', function() {
    initNavbar();
    checkAuth();
  });
}
