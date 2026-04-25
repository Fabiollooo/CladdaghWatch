<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Claddagh | Forgot Password</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="<?php echo asset("css/style.css"); ?>">
  <link rel="icon" href="<?php echo asset("images/favicon.ico"); ?>">
</head>



<!-- 

This page was retired and should have no functionality.

-->






<body>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow-sm p-4 rounded-4">

          <div class="text-center mb-3">
            <img src="<?php echo asset(
                "images/logo.png",
            ); ?>" alt="Logo" style="max-width:150px;">
          </div>

          <h2 class="text-center mb-2 fw-bold">Forgot Password</h2>
          <p class="text-center text-muted mb-4">Enter your email address and we will send you a reset token.</p>

          <div id="alertBox" class="d-none mb-3"></div>

          <form id="forgotForm">
            <div class="mb-3">
              <label for="email" class="form-label">Email address</label>
              <input type="email" id="email" class="form-control form-control-lg" placeholder="you@example.com" required>
            </div>

            <div class="text-center mt-4">
              <button type="submit" id="submitBtn" class="btn btn-primary btn-lg w-100 rounded-pill">Send Reset Token</button>
            </div>
          </form>

          <div class="text-center mt-3">
            <a href="<?php echo url(
                "/reset-password",
            ); ?>" class="text-muted small">Already have a token? Enter it here</a>
          </div>

          <div class="text-center mt-2">
            <a href="<?php echo url(
                "/login",
            ); ?>" class="fw-semibold text-primary small">Back to Login</a>
          </div>

        </div>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('forgotForm').addEventListener('submit', function (e) {
      e.preventDefault();

      const email = document.getElementById('email').value.trim();
      const btn   = document.getElementById('submitBtn');
      btn.disabled = true;
      btn.textContent = 'Sending…';

      fetch('/api/password/forgot', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email })
      })
      .then(res => res.json())
      .then(data => {
        btn.disabled = false;
        btn.textContent = 'Send Reset Token';
        showAlert(data.message || 'Request sent.', data.message && !data.errors ? 'success' : 'danger');
        if (data.message && !data.errors) {
          // Redirect to token entry page after a short delay
          setTimeout(() => {
            window.location.href = '<?php echo url(
                "/reset-password",
            ); ?>?email=' + encodeURIComponent(email);
          }, 1500);
        }
      })
      .catch(() => {
        btn.disabled = false;
        btn.textContent = 'Send Reset Token';
        showAlert('Could not connect to the server. Please try again.', 'danger');
      });
    });

    function showAlert(msg, type) {
      const box = document.getElementById('alertBox');
      box.className = 'alert alert-' + type;
      box.textContent = msg;
    }
  </script>

  <?php @require resource_path("views/templates/footer.php"); ?>
</body>
</html>
