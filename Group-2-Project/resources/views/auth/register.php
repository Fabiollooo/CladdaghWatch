<?php
// No longer needed with API approach
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Claddagh | Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css\">
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
    <link rel="icon" href="<?php echo asset('images/favicon.ico'); ?>">

    <style>
        .logo { max-width: 150px; }
    </style>
</head>

<body>
    <div class="container mt-5">
        <form id="registerForm" method="POST" action="">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow-sm p-4 rounded-4">
                        <!-- Logo -->
                        <div class="text-center mb-3">
                            <img src="<?php echo asset('images/logo.png'); ?>" alt="Logo" class="logo">
                        </div>
                        
                        <h2 class="text-center mb-4 fw-bold">Register</h2>
                        
                        <!-- First Name -->
                        <div class="mb-3">
                            <label class="form-label">First Name</label>
                            <input type="text" id="firstName" class="form-control" required>
                        </div>
                        
                        <!-- Last Name -->
                        <div class="mb-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" id="lastName" class="form-control" required>
                        </div>
                        
                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" id="email" class="form-control" required>
                        </div>
                        
                        <!-- Mobile -->
                        <!-- 
                        <div class="mb-3">
                            <label class="form-label">Mobile</label>
                            <input type="tel" id="mobile" class="form-control" required>
                        </div>
                        -->
                        
                        <!-- Password -->
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" id="password" class="form-control" required minlength="6">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordField('password')">Show</button>
                            </div>
                            <small class="text-muted">Must be at least 6 characters</small>
                        </div>
                        
                        <!-- Confirm Password -->
                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" id="passwordConfirm" class="form-control" required minlength="6">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordField('passwordConfirm')">Show</button>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill">Register</button>
                        </div>
                        
                        <!-- Login Link -->
                        <div class="text-center mt-3">
                            <p class="mb-0">
                                Already have an account? 
                                <a href="<?php echo url('/login'); ?>" class="fw-semibold text-primary">Login</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function togglePasswordField(fieldId) {
            const input = document.getElementById(fieldId);
            input.type = input.type === 'password' ? 'text' : 'password';
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('registerForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const firstName = document.getElementById('firstName').value;
                const lastName = document.getElementById('lastName').value;
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;
                const passwordConfirm = document.getElementById('passwordConfirm').value;
                
                if (password !== passwordConfirm) {
                    alert('Passwords do not match');
                    return;
                }
                
                fetch('/api/register', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        first_name: firstName,
                        last_name: lastName,
                        email: email,
                        password: password
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.user) {
                        alert('Registration successful!');
                        window.location.href = '<?php echo url('/home'); ?>';
                    } else {
                        alert(data.message || 'Registration failed');
                    }
                })
                .catch(() => alert('Error connecting to server'));
            });
        });
    </script>

<?php @require resource_path('views/templates/footer.php'); ?>
</body>
</html>