# Doctor Appointment Booking (Assignment 2)

This MVC scaffold is designed to run under XAMPP on `localhost`.

## Setup
1. Place the `doctor_appointment/` folder inside `xampp/htdocs` (already done).
2. Start Apache and MySQL via the XAMPP control panel.
3. Create a MySQL database named `doctor_booking` (matches `DB_NAME`).
4. Adjust `config/config.php` if your database credentials differ.
5. Point your browser to `http://localhost/doctor_appointment/public`.

## Routing & Architecture
All routes are handled by `public/index.php`, which boots up a simple router
and dispatches to controllers. Views are wrapped in `app/views/layouts/main.php`.
