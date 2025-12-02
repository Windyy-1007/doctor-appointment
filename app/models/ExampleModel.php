<?php

class ExampleModel extends Model
{
    // Provide sample text for the homepage
    public function getWelcomeMessage(): string
    {
        return 'Doctor Appointment Booking platform is ready.';
    }
}
