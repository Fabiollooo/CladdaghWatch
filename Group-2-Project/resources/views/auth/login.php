<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Claddagh | Login</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
  <link rel="icon" href="<?php echo asset('images/favicon.ico'); ?>">
  <style>
    * {
      box-sizing: border-box;
    }

    html {
      width: 100%;
      overflow-x: hidden;
    }

    body.login-page {
      padding-top: 0;
      overflow-x: hidden;
      width: 100%;
      margin: 0;
    }

    .login-wrapper {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      width: 100vw;
      padding: 2rem 1.25rem;
      position: relative;
      z-index: 1;
      overflow: hidden;
    }

    .login-inner {
      width: 100%;
      max-width: 420px;
      padding: 0;
    }

    .login-card {
      background: rgba(255, 255, 255, 0.07);
      backdrop-filter: blur(24px);
      -webkit-backdrop-filter: blur(24px);
      border: 1px solid rgba(255, 255, 255, 0.13);
      border-radius: 22px;
      padding: 2.75rem 2.25rem 2.25rem;
      box-shadow: 0 28px 60px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255,255,255,0.04);
    }

    .login-logo {
      text-align: center;
      margin-bottom: 1.5rem;
    }

    .login-logo img {
      height: 88px;
      filter: drop-shadow(0 6px 14px rgba(0,0,0,0.35));
    }

    .login-title {
      text-align: center;
      color: #fff;
      font-size: 1.7rem;
      font-weight: 700;
      margin-bottom: 0.2rem;
      letter-spacing: -0.4px;
    }

    .login-subtitle {
      text-align: center;
      color: rgba(255,255,255,0.45);
      font-size: 0.875rem;
      margin-bottom: 2rem;
    }

    .login-divider {
      border: none;
      border-top: 1px solid rgba(255,255,255,0.1);
      margin-bottom: 1.75rem;
    }

    .login-error {
      background: rgba(220, 38, 38, 0.15);
      border: 1px solid rgba(220, 38, 38, 0.35);
      color: #fca5a5;
      border-radius: 10px;
      padding: 0.65rem 1rem;
      font-size: 0.85rem;
      margin-bottom: 1.25rem;
    }

    .login-field {
      margin-bottom: 1rem;
    }

    .login-label {
      display: block;
      color: rgba(255,255,255,0.6);
      font-size: 0.8rem;
      font-weight: 500;
      letter-spacing: 0.5px;
      text-transform: uppercase;
      margin-bottom: 0.4rem;
    }

    .login-input-wrap {
      position: relative;
      display: flex;
      align-items: center;
    }

    .login-icon {
      position: absolute;
      left: 1rem;
      color: rgba(255,255,255,0.35);
      font-size: 1rem;
      pointer-events: none;
      z-index: 1;
    }

    .login-input {
      width: 100%;
      background: rgba(255,255,255,0.06);
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: 12px;
      color: #fff;
      padding: 0.8rem 1rem 0.8rem 2.8rem;
      font-size: 16px;
      transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
      outline: none;
    }

    .login-input.has-eye {
      padding-right: 3rem;
    }

    .login-input::placeholder {
      color: rgba(255,255,255,0.25);
    }

    .login-input:focus {
      background: rgba(255,255,255,0.1);
      border-color: rgba(37, 99, 235, 0.75);
      box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
    }

    .login-eye-btn {
      position: absolute;
      right: 0.85rem;
      background: none;
      border: none;
      color: rgba(255,255,255,0.35);
      cursor: pointer;
      padding: 0.2rem;
      font-size: 1rem;
      line-height: 1;
      transition: color 0.2s;
    }

    .login-eye-btn:hover {
      color: rgba(255,255,255,0.8);
    }

    .login-meta {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin: 1.35rem 0 1.75rem;
    }

    .login-check {
      display: flex;
      align-items: center;
      gap: 0.45rem;
      color: rgba(255,255,255,0.55);
      font-size: 0.85rem;
      cursor: pointer;
      user-select: none;
    }

    .login-check input[type="checkbox"] {
      accent-color: #2563eb;
      width: 15px;
      height: 15px;
      cursor: pointer;
    }

    .login-forgot {
      color: rgba(255,255,255,0.5);
      font-size: 0.85rem;
      text-decoration: none;
      transition: color 0.2s;
    }

    .login-forgot:hover {
      color: #fff;
    }

    .login-btn {
      width: 100%;
      background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
      border: none;
      border-radius: 12px;
      color: #fff;
      font-size: 1rem;
      font-weight: 600;
      padding: 0.875rem;
      cursor: pointer;
      transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
      box-shadow: 0 4px 18px rgba(37, 99, 235, 0.45);
      letter-spacing: 0.3px;
    }

    .login-btn:hover:not(:disabled) {
      opacity: 0.92;
      transform: translateY(-1px);
      box-shadow: 0 7px 22px rgba(37, 99, 235, 0.55);
    }

    .login-btn:disabled {
      opacity: 0.55;
      cursor: not-allowed;
    }
  </style>
</head>

<script>
  // -- Cookie helpers ---------------------------------
  function getCookie(name) {
    const eq = name + '=';
    for (let c of document.cookie.split(';')) {
      c = c.trim();
      if (c.startsWith(eq)) return decodeURIComponent(c.slice(eq.length));
    }
    return null;
  }

  function setCookie(name, value, ms, sameSite = 'Lax') {
    const exp = new Date(Date.now() + ms).toUTCString();
    document.cookie = `${name}=${encodeURIComponent(value)}; expires=${exp}; path=/; SameSite=${sameSite}`;
  }

  // Session cookie — no expires means the browser deletes it on close
  function setSessionCookie(name, value, sameSite = 'Lax') {
    document.cookie = `${name}=${encodeURIComponent(value)}; path=/; SameSite=${sameSite}`;
  }

  function deleteCookie(name) {
    document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/`;
  }

  // -- JWT payload decode (no verification — just read claims) --------
  function jwtPayload(token) {
    try {
      const b64 = token.split('.')[1].replace(/-/g, '+').replace(/_/g, '/');
      return JSON.parse(atob(b64));
    } catch { return null; }
  }

  // -- On page load: auto-login if valid JWT cookie present -------
  document.addEventListener('DOMContentLoaded', function () {
    const existingJwt = getCookie('cw_jwt');
    if (existingJwt) {
      const payload = jwtPayload(existingJwt);
      if (payload && payload.exp && Date.now() / 1000 < payload.exp) {
        const landing = Number(payload.role) === 4
          ? '<?php echo url('/supervisor/calendar'); ?>'
          : '<?php echo url('/volunteer'); ?>';
        window.location.href = landing;
        return;
      } else {
        // Expired — clean up
        deleteCookie('cw_jwt');
        deleteCookie('cw_role');
      }
    }

    // -- Login form submit --------------------------------------------
    document.getElementById('loginForm').addEventListener('submit', function (e) {
      e.preventDefault();

      const email      = document.getElementById('email').value.trim();
      const password   = document.getElementById('password').value;
      const rememberMe = document.getElementById('remember').checked;
      const btn        = document.getElementById('loginBtn');

      btn.disabled = true;
      btn.textContent = 'Logging in…';

      fetch('<?php echo url('/api/login'); ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password, remember_me: rememberMe })
      })
      .then(async res => {
        const raw = await res.text();
        let data = null;

        try {
          data = raw ? JSON.parse(raw) : {};
        } catch {
          throw new Error(`Unexpected server response (${res.status}).`);
        }

        if (!res.ok) {
          throw new Error(data?.message || `Login failed (${res.status}).`);
        }

        return data;
      })
      .then(data => {
        btn.disabled = false;
        btn.textContent = 'Login';

        if (!data.token) {
          showError(data.message || 'Invalid email or password.');
          return;
        }

        // Store JWT: persistent 7-day cookie if remember me, otherwise a
        // session cookie (no expires) that the browser deletes on close.
        if (rememberMe) {
          setCookie('cw_jwt',  data.token, 7 * 24 * 3600 * 1000);
          setCookie('cw_role', data.user?.userTypeNr ?? 99, 7 * 24 * 3600 * 1000);
        } else {
          setSessionCookie('cw_jwt',  data.token);
          setSessionCookie('cw_role', data.user?.userTypeNr ?? 99);
        }

        const landing = Number(data.user?.userTypeNr) === 4
          ? '<?php echo url('/supervisor/calendar'); ?>'
          : '<?php echo url('/volunteer'); ?>';
        window.location.href = landing;
      })
      .catch(err => {
        btn.disabled = false;
        btn.textContent = 'Login';
        showError(err?.message || 'Could not connect to the server. Please try again.');
      });
    });
  });

  function showError(msg) {
    const el = document.getElementById('loginError');
    el.textContent = msg;
    el.classList.remove('d-none');
  }

  function togglePassword() {
    const input   = document.getElementById('password');
    const icon    = document.getElementById('eyeIcon');
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    icon.className = isHidden ? 'bi bi-eye-slash' : 'bi bi-eye';
  }
</script>

<body class="login-page">
  <div class="login-wrapper">
    <div class="login-inner">
    <form id="loginForm" method="get" action="">
      <div class="login-card">

        <!-- Logo -->
        <div class="login-logo">
          <img src="<?php echo asset('images/logo.png'); ?>" alt="Claddagh Watch Logo">
        </div>

        <h1 class="login-title">Welcome back</h1>
        <p class="login-subtitle">Sign in to Claddagh Watch</p>

        <hr class="login-divider">

        <!-- Error alert -->
        <div id="loginError" class="login-error d-none" role="alert"></div>

        <!-- Email input -->
        <div class="login-field">
          <label class="login-label" for="email">Email address</label>
          <div class="login-input-wrap">
            <i class="bi bi-envelope login-icon"></i>
            <input type="email" id="email" class="login-input" placeholder="you@example.com" required>
          </div>
        </div>

        <!-- Password input -->
        <div class="login-field">
          <label class="login-label" for="password">Password</label>
          <div class="login-input-wrap">
            <i class="bi bi-lock login-icon"></i>
            <input type="password" id="password" class="login-input has-eye" placeholder="Enter your password">
            <button class="login-eye-btn" type="button" id="togglePwBtn" onclick="togglePassword()" aria-label="Toggle password visibility">
              <i class="bi bi-eye" id="eyeIcon"></i>
            </button>
          </div>
        </div>

        <!-- Remember me & forgot password -->
        <div class="login-meta">
          <label class="login-check">
            <input type="checkbox" id="remember">
            <span>Remember me</span>
          </label>

        </div>

        <!-- Sign in button -->
        <button type="submit" id="loginBtn" class="login-btn">Sign In</button>

      </div>
    </form>
    </div>
  </div>

<?php @require resource_path('views/templates/footer.php'); ?>
</body>
</html>
