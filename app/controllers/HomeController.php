<?php

require_once __DIR__ . '/../models/ExampleModel.php';
require_once __DIR__ . '/../models/SpecialtyModel.php';
require_once __DIR__ . '/../models/OfficeModel.php';

class HomeController extends Controller
{
    // Default page for the application demo
    public function index(): void
    {
        $welcome = (new ExampleModel())->getWelcomeMessage();
        $specialtyModel = new SpecialtyModel();
        $officeModel = new OfficeModel();

        $specialties = $specialtyModel->getAll();
        $offices = $officeModel->getApprovedBySearch(null);

        $this->render('home/index', [
            'welcome' => $welcome,
            'specialties' => $specialties,
            'offices' => $offices,
        ]);
    }
}
