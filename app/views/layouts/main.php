<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Appointment Booking</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
    <script src="<?= BASE_URL ?>/js/app.js" defer></script>
</head>
<body>
    <!-- Shared header rendered for every page -->
    <header class="site-header">
        <div class="container">
            <h1>Doctor Appointment Booking</h1>
            <nav>
                <a href="<?= BASE_URL ?>">Home</a>
                <?php if (Auth::check() && ($_SESSION['user_role'] ?? '') === 'patient'): ?>
                    <a href="<?= BASE_URL ?>/patient/specialties">Specialties</a>
                    <a href="<?= BASE_URL ?>/patient/appointments">My Appointments</a>
                <?php endif; ?>
                <?php if (Auth::check()): ?>
                    <a href="<?= BASE_URL ?>/auth/logout">Logout</a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/auth/login">Login</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main class="site-content">
        <?= $content ?>
    </main>

    <footer class="site-footer">
        <div class="container">
            <p>Powered by XAMPP + PHP MVC</p>
        </div>
    </footer>
</body>
</html>
