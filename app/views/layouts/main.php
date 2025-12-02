<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? 'Doctor Appointment Booking' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
</head>
<body class="bg-white text-dark">
    <nav class="navbar navbar-expand-lg navbar-light bg-primary-light border-bottom">
        <div class="container">
            <a class="navbar-brand text-dark fw-bold" href="<?= BASE_URL ?>">MediBook</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <?php $role = $currentUserRole ?? ($_SESSION['user_role'] ?? 'guest'); ?>
                    <?php if ($role === 'guest'): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/patient/specialties">Specialties</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/auth/login">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/auth/register">Register</a></li>
                    <?php elseif ($role === 'patient'): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/patient/specialties">Specialties</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/patient/appointments">My Appointments</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/auth/logout">Logout</a></li>
                    <?php elseif ($role === 'office'): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/office/dashboard">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/office/doctors">Doctors</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/office/schedule">Schedule</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/office/profile/edit">Profile</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/auth/logout">Logout</a></li>
                    <?php elseif ($role === 'admin'): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/dashboard">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/users">Users</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/specialties">Specialties</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/offices">Offices</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/auth/logout">Logout</a></li>
                    <?php elseif ($role === 'staff'): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/staff/reports">Reports</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/staff/moderation/offices">Moderation</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/auth/logout">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>">Home</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        <?php if (!empty($_SESSION['flash_success'])): ?>
            <div class="alert alert-success" role="alert"><?= htmlspecialchars($_SESSION['flash_success']) ?></div>
            <?php unset($_SESSION['flash_success']); endif; ?>
        <?php if (!empty($_SESSION['flash_error'])): ?>
            <div class="alert alert-danger" role="alert"><?= htmlspecialchars($_SESSION['flash_error']) ?></div>
            <?php unset($_SESSION['flash_error']); endif; ?>

        <?= $content ?>
    </div>

    <footer class="text-center py-3 border-top bg-primary-light text-dark">
        <small class="text-muted">© <?= date('Y') ?> MediBook. All rights reserved.</small>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-" crossorigin="anonymous"></script>
    <script src="<?= BASE_URL ?>/js/app.js"></script>
</body>
</html>
