<?php

require_once __DIR__ . '/../models/SpecialtyModel.php';

class AdminSpecialtyController extends Controller
{
    private SpecialtyModel $specialtyModel;

    public function __construct()
    {
        Auth::requireRole(['admin']);
        $this->specialtyModel = new SpecialtyModel();
    }

    public function index(): void
    {
        $specialties = $this->specialtyModel->getAll();
        $flash = $_SESSION['admin_specialty_flash'] ?? null;
        unset($_SESSION['admin_specialty_flash']);

        $this->render('admin/specialties/index', [
            'specialties' => $specialties,
            'flash' => $flash,
        ]);
    }

    public function create(): void
    {
        $this->render('admin/specialties/create', [
            'errors' => [],
            'old' => [],
        ]);
    }

    public function store(): void
    {
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $errors = [];

        if ($name === '') {
            $errors['name'] = 'Name is required.';
        }

        if (!empty($errors)) {
            $this->render('admin/specialties/create', [
                'errors' => $errors,
                'old' => ['name' => $name, 'description' => $description],
            ]);
            return;
        }

        $created = $this->specialtyModel->create([
            'name' => $name,
            'description' => $description,
        ]);

        $_SESSION['admin_specialty_flash'] = $created ? 'Specialty created.' : 'Could not create specialty.';
        $this->redirect('/admin/specialties');
    }

    public function edit($id): void
    {
        $specialty = $this->specialtyModel->findById((int) $id);
        if ($specialty === null) {
            $_SESSION['admin_specialty_flash'] = 'Specialty not found.';
            $this->redirect('/admin/specialties');
        }

        $this->render('admin/specialties/edit', [
            'specialty' => $specialty,
            'errors' => [],
        ]);
    }

    public function update($id): void
    {
        $specialtyId = (int) $id;
        $specialty = $this->specialtyModel->findById($specialtyId);
        if ($specialty === null) {
            $_SESSION['admin_specialty_flash'] = 'Specialty not found.';
            $this->redirect('/admin/specialties');
        }

        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $errors = [];

        if ($name === '') {
            $errors['name'] = 'Name is required.';
        }

        if (!empty($errors)) {
            $specialty['name'] = $name;
            $specialty['description'] = $description;

            $this->render('admin/specialties/edit', [
                'specialty' => $specialty,
                'errors' => $errors,
            ]);
            return;
        }

        $updated = $this->specialtyModel->update($specialtyId, [
            'name' => $name,
            'description' => $description,
        ]);

        $_SESSION['admin_specialty_flash'] = $updated ? 'Specialty updated.' : 'Could not update specialty.';
        $this->redirect('/admin/specialties');
    }

    public function delete($id): void
    {
        $deleted = $this->specialtyModel->delete((int) $id);
        $_SESSION['admin_specialty_flash'] = $deleted ? 'Specialty removed.' : 'Unable to remove specialty.';
        $this->redirect('/admin/specialties');
    }
}
