<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Claddagh | Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
    <link rel="icon" href="<?php echo asset('images/favicon.ico'); ?>">
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .dashboard-container {
            text-align: center;
        }
        .logo {
            max-width: 200px;
            margin-bottom: 30px;
        }
        h1 {
            color: white;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .btn-group-custom {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn-custom {
            padding: 12px 40px;
            font-size: 18px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-login {
            background-color: white;
            color: #1e3a5f;
            border: 2px solid white;
        }
        .btn-login:hover {
            background-color: transparent;
            color: white;
            transform: translateY(-2px);
        }
        .btn-register {
            background-color: #2563eb;
            color: white;
            border: 2px solid #2563eb;
        }
        .btn-register:hover {
            background-color: #1d4ed8;
            color: white;
            border-color: #1d4ed8;
            transform: translateY(-2px);
        }
    </style>
</head>

<!-- ************************ -->

 <!-- This page was Retired !!!!! -->

<!-- ************************ -->

<body>
    
    <div class="dashboard-container">
        <img src="<?php echo asset('images/logo.png'); ?>" alt="Logo" class="logo">
        <h1>Welcome to Claddagh</h1>
        <div class="btn-group-custom">
            <a href="<?php echo url('/login'); ?>" class="btn btn-custom btn-login">
                <i class="bi bi-box-arrow-in-right"></i> Login
            </a>
            <a href="<?php echo url('/register'); ?>" class="btn btn-custom btn-register">
                <i class="bi bi-person-plus"></i> Register
            </a>
        </div>
    </div>
</body>
</html>