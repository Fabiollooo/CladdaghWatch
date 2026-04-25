<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Claddagh | Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
    <link rel="icon" href="<?php echo asset('images/favicon.ico'); ?>">
</head>

 <!-- ************************ -->

 <!-- This page was Retired !!!!! -->

 <!-- ************************ -->



<body>
    <?php @require resource_path('views/templates/admin/adminHeader.php');?>

    <!-- Mobile Menu Offcanvas -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">
                <img src="<?php echo asset('images/logo.png'); ?>" alt="Logo" style="height: 40px;">
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <nav class="nav flex-column">
                <a class="nav-link active" href="<?php echo url('/home'); ?>">
                    <i class="bi bi-house-door"></i> Home
                </a>
                <a class="nav-link" href="<?php echo url('/schedules'); ?>">
                    <i class="bi bi-calendar-check"></i> Schedules
                </a>
                <a class="nav-link" href="<?php echo url('/about'); ?>">
                    <i class="bi bi-info-circle"></i> About
                </a>
                <hr class="my-2">
                <a class="nav-link text-danger" href="<?php echo url('/logout'); ?>">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </nav>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1>Claddagh Watch Patrol</h1>
            <p>Welcome admin {name} !!!</p>
            <a href="/patrolManagement" class="btn btn-light btn-lg px-5 rounded-pill">View Patrols</a>
        </div>
    </section>

    

    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<?php @require resource_path('views/templates/footer.php'); ?>
</body>
</html>
