<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Claddagh | Reset Password</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
  <link rel="icon" href="<?php echo asset('images/favicon.ico'); ?>">
</head>

<body>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow-sm p-4 rounded-4">

          <div class="text-center mb-3">
            <img src="<?php echo asset('images/logo.png'); ?>" alt="Logo" style="max-width:150px;">
          </div>

          <h2 class="text-center mb-2 fw-bold">Reset Password</h2>
          <p class="text-center text-muted mb-4">Enter the token sent to your email along with your new password.</p>

          <div id="alertBox" class="d-none mb-3"></div>

          <form id="resetForm">

            <!-- Email -->
            <div class="mb-3">
              <label for="email" class="form-label">Email address</label>
              <input type="email" id="email" class="form-control form-control-lg" placeholder="you@example.com" required>
            </div>

            <!-- Token -->
            <div class="mb-3">
              <label for="token" class="form-label">Reset Token</label>
              <input type="text" id="token" class="form-control form-control-lg" placeholder="Paste the token from your email" required>
            </div>

            <!-- New Password -->
            <div class="mb-3">
              <label for="password" class="form-label">New Password</label>
              <div class="input-group">
                <input type="password" id="password" class="form-control form-control-lg" placeholder="Minimum 6 characters" required minlength="6">
                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">Show</button>
              </div>
            </div>

            <div class="text-center mt-4">
              <button type="submit" id="submitBtn" class="btn btn-primary btn-lg w-100 rounded-pill">Reset Password</button>
            </div>

          </form>

          <div class="text-center mt-3">
            <a href="<?php echo url('/forgot-password'); ?>" class="text-muted small">Request a new token</a>
          </div>

          <div class="text-center mt-2">
            <a href="<?php echo url('/login'); ?>" class="fw-semibold text-primary small">Back to Login</a>
          </div>

        </div>
      </div>
    </div>
  </div>

  <script>
    // Pre-fill email from query string if redirected from forgot-password page
    document.addEventListener('DOMContentLoaded', function () {
      const params = new URLSearchParams(window.location.search);
      const email  = params.get('email');
      if (email) {
        document.getElementById('email').value = decodeURIComponent(email);
      }
    });

    document.getElementById('resetForm').addEventListener('submit', function (e) {
      e.preventDefault();

      const email    = document.getElementById('email').value.trim();
      const token    = document.getElementById('token').value.trim();
      const password = document.getElementById('password').value;
      const btn      = document.getElementById('submitBtn');

      btn.disabled    = true;
      btn.textContent = 'Resetting…';

      fetch('/api/password/reset', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify({ email, token, password })
      })
      .then(res => res.json())
      .then(data => {
        btn.disabled    = false;
        btn.textContent = 'Reset Password';

        if (data.message && data.message.includes('successful')) {
          showAlert('Password reset successfully! Redirecting to login…', 'success');
          setTimeout(() => { window.location.href = '<?php echo url('/login'); ?>'; }, 2000);
        } else {
          showAlert(data.message || 'Reset failed. Please try again.', 'danger');
        }
      })
      .catch(() => {
        btn.disabled    = false;
        btn.textContent = 'Reset Password';
        showAlert('Could not connect to the server. Please try again.', 'danger');
      });
    });

    function togglePassword() {
      const input = document.getElementById('password');
      input.type  = input.type === 'password' ? 'text' : 'password';
    }

    function showAlert(msg, type) {
      const box       = document.getElementById('alertBox');
      box.className   = 'alert alert-' + type;
      box.textContent = msg;
    }
  </script>

  <?php @require resource_path('views/templates/footer.php'); ?>
</body>
</html>
