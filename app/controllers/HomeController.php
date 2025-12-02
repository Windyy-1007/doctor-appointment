<?php

require_once __DIR__ . '/../models/ExampleModel.php';

class HomeController extends Controller
{
    // Default page for the application demo
    public function index(): void
    {
        $welcome = (new ExampleModel())->getWelcomeMessage();
        $this->render('home/index', ['welcome' => $welcome]);
    }
}
