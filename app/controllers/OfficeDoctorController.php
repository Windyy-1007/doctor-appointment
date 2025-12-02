<?php

require_once __DIR__ . '/../models/DoctorModel.php';
require_once __DIR__ . '/../models/OfficeModel.php';
require_once __DIR__ . '/../models/SpecialtyModel.php';

class OfficeDoctorController extends Controller
{
    private DoctorModel $doctorModel;
    private OfficeModel $officeModel;
    private SpecialtyModel $specialtyModel;

    public function __construct()
    {
        Auth::requireRole(['office']);
        $this->doctorModel = new DoctorModel();
        $this->officeModel = new OfficeModel();
        $this->specialtyModel = new SpecialtyModel();
    }

    private function getOfficeId(): ?int
    {
        $userId = $_SESSION['user_id'] ?? null;
        if ($userId === null) {
            return null;
        }

        $office = $this->officeModel->getByUserId((int) $userId);
        return $office['id'] ?? null;
    }

    public function index(): void
    {
        $officeId = $this->getOfficeId();
        if ($officeId === null) {
            $this->redirect('/');
            return;
        }

        $doctors = $this->doctorModel->getByOfficeId($officeId);
        $flash = $_SESSION['office_doctors_flash'] ?? null;
        unset($_SESSION['office_doctors_flash']);

        $this->render('office/doctors/index', [
            'doctors' => $doctors,
            'flash' => $flash,
        ]);
    }

    public function create(): void
    {
        $specialties = $this->specialtyModel->getAll();
        $this->render('office/doctors/create', ['specialties' => $specialties, 'errors' => [], 'old' => []]);
    }

    public function store(): void
    {
        $officeId = $this->getOfficeId();
        if ($officeId === null) {
            $this->redirect('/');
            return;
        }

        $name = trim($_POST['name'] ?? '');
        $specialtyId = (int) ($_POST['specialty_id'] ?? 0);
        $bio = trim($_POST['bio'] ?? '');
        $errors = [];

        if ($name === '') {
            $errors['name'] = 'Doctor name is required.';
        }

        if ($specialtyId <= 0) {
            $errors['specialty_id'] = 'Select a specialty.';
        }

        if (!empty($errors)) {
            $specialties = $this->specialtyModel->getAll();
            $this->render('office/doctors/create', [
                'specialties' => $specialties,
                'errors' => $errors,
                'old' => ['name' => $name, 'specialty_id' => $specialtyId, 'bio' => $bio],
            ]);
            return;
        }

        $created = $this->doctorModel->create($officeId, [
            'name' => $name,
            'specialty_id' => $specialtyId,
            'bio' => $bio,
        ]);

        $_SESSION['office_doctors_flash'] = $created ? 'Doctor added.' : 'Unable to add doctor.';
        $this->redirect('/office/doctors');
    }

    public function edit($id): void
    {
        $officeId = $this->getOfficeId();
        if ($officeId === null) {
            $this->redirect('/');
            return;
        }

        $doctor = $this->doctorModel->findById((int) $id);
        if ($doctor === null || (int) $doctor['office_id'] !== $officeId) {
            $this->redirect('/office/doctors');
            return;
        }

        $specialties = $this->specialtyModel->getAll();
        $this->render('office/doctors/edit', [
            'doctor' => $doctor,
            'specialties' => $specialties,
            'errors' => [],
        ]);
    }

    public function update($id): void
    {
        $officeId = $this->getOfficeId();
        if ($officeId === null) {
            $this->redirect('/');
            return;
        }

        $doctor = $this->doctorModel->findById((int) $id);
        if ($doctor === null || (int) $doctor['office_id'] !== $officeId) {
            $this->redirect('/office/doctors');
            return;
        }

        $name = trim($_POST['name'] ?? '');
        $specialtyId = (int) ($_POST['specialty_id'] ?? 0);
        $bio = trim($_POST['bio'] ?? '');
        $errors = [];

        if ($name === '') {
            $errors['name'] = 'Doctor name is required.';
        }

        if ($specialtyId <= 0) {
            $errors['specialty_id'] = 'Select a specialty.';
        }

        if (!empty($errors)) {
            $specialties = $this->specialtyModel->getAll();
            $doctor = array_merge($doctor, ['name' => $name, 'specialty_id' => $specialtyId, 'bio' => $bio]);
            $this->render('office/doctors/edit', [
                'doctor' => $doctor,
                'specialties' => $specialties,
                'errors' => $errors,
            ]);
            return;
        }

        $updated = $this->doctorModel->update((int) $id, $officeId, [
            'name' => $name,
            'specialty_id' => $specialtyId,
            'bio' => $bio,
        ]);

        $_SESSION['office_doctors_flash'] = $updated ? 'Doctor updated.' : 'Unable to update doctor.';
        $this->redirect('/office/doctors');
    }

    public function delete($id): void
    {
        $officeId = $this->getOfficeId();
        if ($officeId === null) {
            $this->redirect('/');
            return;
        }

        $deleted = $this->doctorModel->delete((int) $id, $officeId);
        $_SESSION['office_doctors_flash'] = $deleted ? 'Doctor removed.' : 'Unable to remove doctor.';
        $this->redirect('/office/doctors');
    }
}
