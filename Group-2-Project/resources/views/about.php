<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Claddagh | About Us</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
    <link rel="icon" href="<?php echo asset('images/favicon.ico'); ?>">
</head>
<body>
    <?php @require resource_path('views/templates/header.php');?>#


 <!-- ************************ -->

<!-- This page was Retired !!!!! -->

 <!-- ************************ -->

   
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
                <a class="nav-link" href="<?php echo url('/home'); ?>">
                    <i class="bi bi-house-door"></i> Home
                </a>
                <a class="nav-link" href="<?php echo url('/schedules'); ?>">
                    <i class="bi bi-calendar-check"></i> Schedules
                </a>
                <a class="nav-link active" href="<?php echo url('/about'); ?>">
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
    <section class="about-hero-section py-5">
        <div class="container text-center">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h1 class="display-3 fw-bold mb-4" style="color: white;">About Claddagh</h1>
                    <p class="lead fs-4" style="color: rgba(255,255,255,0.95);">Empowering communities through innovative volunteer management</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission Section -->
    <section class="py-5" style="background: #ffffff;">
        <div class="container py-4">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="pe-lg-5">
                        <span class="badge bg-primary mb-3 px-3 py-2">Our Mission</span>
                        <h2 class="display-5 fw-bold mb-4" style="color: #1e3a5f;">Building Better Communities Together</h2>
                        <p class="lead text-muted mb-4">At Claddagh, we're committed to delivering innovative solutions that empower volunteers and organizations to achieve their goals.</p>
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <i class="bi bi-check-circle-fill fs-4" style="color: #1e3a5f;"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="mb-0">Simplify volunteer scheduling and coordination</p>
                            </div>
                        </div>
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <i class="bi bi-check-circle-fill fs-4" style="color: #1e3a5f;"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="mb-0">Enhance community engagement and collaboration</p>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="bi bi-check-circle-fill fs-4" style="color: #1e3a5f;"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="mb-0">Provide reliable, user-centric solutions</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="position-relative">
                        <div class="about-mission-visual p-5 rounded-4 text-center" style="background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%); box-shadow: 0 10px 40px rgba(30,58,95,0.2);">
                            <i class="bi bi-bullseye text-white" style="font-size: 7rem;"></i>
                            <h4 class="text-white mt-4 mb-3">Focused on Impact</h4>
                            <p class="text-white opacity-75 mb-0">Every feature designed to make a difference</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="py-5" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
        <div class="container py-4">
            <div class="text-center mb-5">
                <span class="badge" style="background-color: #7A1F2B; color: white;" class="mb-3 px-3 py-2">What We Stand For</span>
                <h2 class="display-5 fw-bold mt-3" style="color: #1e3a5f;">Our Core Values</h2>
                <p class="lead text-muted mt-3">The principles that guide everything we do</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="about-value-card h-100 p-4 rounded-3 bg-white border-0 shadow-sm">
                        <div class="about-value-icon mb-3 d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 64px; height: 64px; background: rgba(0,75,73,0.1);">
                            <i class="bi bi-heart-fill fs-3" style="color: #1e3a5f;"></i>
                        </div>
                        <h4 class="fw-bold mb-3" style="color: #1f1f1f;">User-Focused</h4>
                        <p class="text-muted mb-0">We put our users at the heart of everything we do, ensuring every feature serves a real need.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="about-value-card h-100 p-4 rounded-3 bg-white border-0 shadow-sm">
                        <div class="about-value-icon mb-3 d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 64px; height: 64px; background: rgba(122,31,43,0.1);">
                            <i class="bi bi-lightbulb-fill fs-3" style="color: #7A1F2B;"></i>
                        </div>
                        <h4 class="fw-bold mb-3" style="color: #1f1f1f;">Innovation</h4>
                        <p class="text-muted mb-0">We continuously evolve and adapt to provide cutting-edge solutions that stay ahead of the curve.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="about-value-card h-100 p-4 rounded-3 bg-white border-0 shadow-sm">
                        <div class="about-value-icon mb-3 d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 64px; height: 64px; background: rgba(0,75,73,0.1);">
                            <i class="bi bi-shield-check fs-3" style="color: #1e3a5f;"></i>
                        </div>
                        <h4 class="fw-bold mb-3" style="color: #1f1f1f;">Integrity</h4>
                        <p class="text-muted mb-0">We maintain the highest standards of honesty and transparency in all our interactions.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="about-value-card h-100 p-4 rounded-3 bg-white border-0 shadow-sm">
                        <div class="about-value-icon mb-3 d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 64px; height: 64px; background: rgba(122,31,43,0.1);">
                            <i class="bi bi-people-fill fs-3" style="color: #7A1F2B;"></i>
                        </div>
                        <h4 class="fw-bold mb-3" style="color: #1f1f1f;">Collaboration</h4>
                        <p class="text-muted mb-0">We believe in the power of teamwork and building strong partnerships with our community.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="about-value-card h-100 p-4 rounded-3 bg-white border-0 shadow-sm">
                        <div class="about-value-icon mb-3 d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 64px; height: 64px; background: rgba(0,75,73,0.1);">
                            <i class="bi bi-star-fill fs-3" style="color: #1e3a5f;"></i>
                        </div>
                        <h4 class="fw-bold mb-3" style="color: #1f1f1f;">Excellence</h4>
                        <p class="text-muted mb-0">We strive for excellence in every aspect of our platform, from design to performance.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="about-value-card h-100 p-4 rounded-3 bg-white border-0 shadow-sm">
                        <div class="about-value-icon mb-3 d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 64px; height: 64px; background: rgba(122,31,43,0.1);">
                            <i class="bi bi-award-fill fs-3" style="color: #7A1F2B;"></i>
                        </div>
                        <h4 class="fw-bold mb-3" style="color: #1f1f1f;">Quality</h4>
                        <p class="text-muted mb-0">We're committed to delivering high-quality products that exceed expectations.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    
    <!-- Story Section -->
    <section class="py-5" style="background: #ffffff;">
        <div class="container py-4">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="row g-5 align-items-center">
                        <div class="col-lg-5">
                            <div class="position-relative">
                                <div class="about-story-badge p-4 rounded-4 text-center" style="background: linear-gradient(135deg, #7A1F2B 0%, #8B2C3A 100%); box-shadow: 0 10px 40px rgba(122,31,43,0.2);">
                                    <i class="bi bi-calendar-event text-white" style="font-size: 4rem;"></i>
                                    <div class="mt-4">
                                        <h3 class="text-white mb-2 fw-bold">Since 2026</h3>
                                        <p class="text-white opacity-75 mb-0">Serving communities with passion</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <span class="badge bg-primary mb-3 px-3 py-2">Our Journey</span>
                            <h2 class="display-5 fw-bold mb-4" style="color: #1e3a5f;">The Story Behind Claddagh</h2>
                            <div class="about-story-content">
                                <p class="lead text-muted mb-4">Claddagh was founded with a simple vision: to create a platform that makes volunteer coordination easier and more enjoyable.</p>
                                <p class="text-muted mb-4">What started as a small project has grown into a comprehensive solution trusted by organizations and volunteers worldwide. Our journey has been driven by feedback from our amazing community who continue to inspire us to push boundaries and deliver better experiences every day.</p>
                                <p class="text-muted mb-0">Today, we're proud to serve a diverse range of individuals and organizations, helping them streamline their workflows, enhance community engagement, and achieve remarkable results together.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="py-5" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
        <div class="container py-4">
            <div class="text-center mb-5">
                <span class="badge" style="background-color: #7A1F2B; color: white;" class="mb-3 px-3 py-2">The People Behind It</span>
                <h2 class="display-5 fw-bold mt-3" style="color: #1e3a5f;">Meet Our Team</h2>
                <p class="lead text-muted mt-3">Dedicated professionals working to make a difference</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="about-team-card h-100 bg-white rounded-4 overflow-hidden border-0 shadow-sm">
                        <div class="about-team-header p-4 text-center" style="background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);">
                            <div class="about-team-icon d-inline-flex align-items-center justify-content-center rounded-circle bg-white" style="width: 80px; height: 80px;">
                                <i class="bi bi-person-circle" style="font-size: 3.5rem; color: #1e3a5f;"></i>
                            </div>
                        </div>
                        <div class="p-4 text-center">
                            <h4 class="fw-bold mb-3" style="color: #1f1f1f;">Leadership Team</h4>
                            <p class="text-muted mb-0">Guiding our vision and strategy with passion and purpose</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="about-team-card h-100 bg-white rounded-4 overflow-hidden border-0 shadow-sm">
                        <div class="about-team-header p-4 text-center" style="background: linear-gradient(135deg, #7A1F2B 0%, #8B2C3A 100%);">
                            <div class="about-team-icon d-inline-flex align-items-center justify-content-center rounded-circle bg-white" style="width: 80px; height: 80px;">
                                <i class="bi bi-code-slash" style="font-size: 3.5rem; color: #7A1F2B;"></i>
                            </div>
                        </div>
                        <div class="p-4 text-center">
                            <h4 class="fw-bold mb-3" style="color: #1f1f1f;">Development Team</h4>
                            <p class="text-muted mb-0">Building innovative solutions that empower communities</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="about-team-card h-100 bg-white rounded-4 overflow-hidden border-0 shadow-sm">
                        <div class="about-team-header p-4 text-center" style="background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);">
                            <div class="about-team-icon d-inline-flex align-items-center justify-content-center rounded-circle bg-white" style="width: 80px; height: 80px;">
                                <i class="bi bi-headset" style="font-size: 3.5rem; color: #1e3a5f;"></i>
                            </div>
                        </div>
                        <div class="p-4 text-center">
                            <h4 class="fw-bold mb-3" style="color: #1f1f1f;">Support Team</h4>
                            <p class="text-muted mb-0">Always here to help you succeed and thrive</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Ready to get started Section -->
    <section class="py-5">
        <div class="container">
            <div class="cta-section">
                <h2>Ready to Get Started?</h2>
                <p class="mb-4">Join thousands of users who trust Claddagh for their needs</p>
                <a href="<?php echo url('/dashboard'); ?>" class="btn btn-cta">Go to Dashboard</a>
            </div>
        </div>
    </section>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<?php @require resource_path('views/templates/footer.php'); ?>
</body>
</html>
