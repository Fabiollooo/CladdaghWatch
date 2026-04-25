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
<body>
<?php @require resource_path('views/templates/header.php'); ?>

    <!-- Hero -->
    <section class="hero-section">
        <div class="container">
            <h1>Claddagh Watch Patrol</h1>
            <p class="lead mb-4">Volunteers keeping Galway waterways safer through visible patrols, care, and quick response.</p>
            <?php if ($__isLoggedIn ?? false): ?>
                <a href="<?php echo url('/volunteer'); ?>" class="btn btn-light btn-lg px-5 rounded-pill me-2">
                    <i class="bi bi-hand-thumbs-up me-2"></i>Volunteer for a Patrol
                </a>
            <?php else: ?>
                <a href="<?php echo url('/register'); ?>" class="btn btn-light btn-lg px-5 rounded-pill me-2">Join Us</a>
                <a href="<?php echo url('/login'); ?>" class="btn btn-outline-light btn-lg px-5 rounded-pill">Login</a>
            <?php endif; ?>
        </div>
    </section>

    <!-- Our Focus -->
    <section class="features" id="features">
        <div class="container">
            <h2 class="text-center mb-5">Our Focus</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="bi bi-lightning-charge feature-icon"></i>
                        <h4>Rapid Response</h4>
                        <p>Timely patrol coverage along the river and nearby areas when it matters most.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="bi bi-shield-check feature-icon"></i>
                        <h4>Prevention First</h4>
                        <p>Visible presence that deters risk and supports safety around Galway waterways.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="bi bi-people feature-icon"></i>
                        <h4>Community Care</h4>
                        <p>Volunteer-led support, working with local partners to keep people safe.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About -->
    <section class="py-5" id="about" style="background:#fff;">
        <div class="container py-4">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <span class="badge bg-primary mb-3 px-3 py-2">Our Mission</span>
                    <h2 class="display-5 fw-bold mb-4" style="color:#1e3a5f;">About Claddagh Watch</h2>
                    <p class="lead text-muted mb-4">
                        Claddagh Watch Patrol is a registered charity founded in 2019, following four tragic
                        water-related deaths in Galway within the space of a single week. Our mission is to
                        create a safer environment around Galway city's waterways through volunteer patrols
                        and awareness, reducing deaths caused by suicide or accident.
                    </p>
                    <div class="d-flex mb-3">
                        <i class="bi bi-check-circle-fill fs-4 me-3" style="color:#1e3a5f;flex-shrink:0;"></i>
                        <p class="mb-0">Simplify volunteer scheduling and coordination</p>
                    </div>
                    <div class="d-flex mb-3">
                        <i class="bi bi-check-circle-fill fs-4 me-3" style="color:#1e3a5f;flex-shrink:0;"></i>
                        <p class="mb-0">Enhance community engagement and collaboration</p>
                    </div>
                    <div class="d-flex">
                        <i class="bi bi-check-circle-fill fs-4 me-3" style="color:#1e3a5f;flex-shrink:0;"></i>
                        <p class="mb-0">Provide reliable, people-centric support around our waterways</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="p-5 rounded-4 text-center" style="background:linear-gradient(135deg,#1e3a5f,#2563eb);box-shadow:0 10px 40px rgba(30,58,95,0.25);">
                        <i class="bi bi-water text-white" style="font-size:7rem;"></i>
                        <h4 class="text-white mt-4 mb-3">Galway Waterways</h4>
                        <p class="text-white opacity-75 mb-0">Making every patrol count since 2019</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values -->
    <section class="py-5" style="background:linear-gradient(135deg,#f8f9fa 0%,#e9ecef 100%);">
        <div class="container py-4">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold" style="color:#1e3a5f;">Our Core Values</h2>
                <p class="lead text-muted mt-2">The principles that guide everything we do</p>
            </div>
            <div class="row g-4">
                <?php
                $values = [
                    ['bi-heart-fill','#1e3a5f','Community First','Every decision is guided by what\'s best for the people of Galway and our waterways.'],
                    ['bi-shield-check','#2563eb','Integrity','Honesty and transparency in all our operations and volunteer management.'],
                    ['bi-people-fill','#1e3a5f','Collaboration','Strong partnerships with local emergency services, mental health groups, and volunteers.'],
                    ['bi-eye','#2563eb','Visibility','A consistent, reassuring presence along Galway\'s waterfront that deters risk.'],
                    ['bi-lightbulb-fill','#1e3a5f','Innovation','Continuously improving how we recruit, schedule, and support our volunteers.'],
                    ['bi-star-fill','#2563eb','Excellence','High standards in training, communication, and every patrol we carry out.'],
                ];
                foreach ($values as [$icon,$color,$title,$desc]):
                ?>
                <div class="col-md-4 col-sm-6">
                    <div class="h-100 p-4 rounded-3 bg-white shadow-sm text-center">
                        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle"
                             style="width:64px;height:64px;background:<?php echo $color; ?>1a;">
                            <i class="bi <?php echo $icon; ?> fs-3" style="color:<?php echo $color; ?>;"></i>
                        </div>
                        <h5 class="fw-bold mb-2"><?php echo $title; ?></h5>
                        <p class="text-muted small mb-0"><?php echo $desc; ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Volunteer CTA -->
    <section class="hero-section" id="volunteer-cta">
        <div class="container text-center">
            <i class="bi bi-hand-thumbs-up" style="font-size:4rem;opacity:0.8;"></i>
            <h2 class="mt-3 mb-3">Ready to Make a Difference?</h2>
            <p class="lead mb-4 opacity-90">Our patrols run every week. Sign up for an upcoming patrol and join the Claddagh community.</p>
            <?php if ($__isLoggedIn ?? false): ?>
                <a href="<?php echo url('/volunteer'); ?>" class="btn btn-light btn-lg px-5 rounded-pill">
                    <i class="bi bi-calendar-check me-2"></i>View Open Patrols
                </a>
            <?php else: ?>
                <a href="<?php echo url('/register'); ?>" class="btn btn-light btn-lg px-5 rounded-pill me-2">Create an Account</a>
                <a href="<?php echo url('/login'); ?>" class="btn btn-outline-light btn-lg px-4 rounded-pill">Login</a>
            <?php endif; ?>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<?php @require resource_path('views/templates/footer.php'); ?>
</body>
</html>
