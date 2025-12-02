<?php

require_once __DIR__ . '/../models/SpecialtyModel.php';
require_once __DIR__ . '/../models/OfficeModel.php';
require_once __DIR__ . '/../models/DoctorModel.php';

class PatientBrowseController extends Controller
{
    private SpecialtyModel $specialtyModel;
    private OfficeModel $officeModel;
    private DoctorModel $doctorModel;

    public function __construct()
    {
        $this->specialtyModel = new SpecialtyModel();
        $this->officeModel = new OfficeModel();
        $this->doctorModel = new DoctorModel();
    }

    public function specialties(): void
    {
        $specialties = $this->specialtyModel->getAll();
        $this->render('patient/specialties/index', [
            'specialties' => $specialties,
        ]);
    }

    public function officesBySpecialty($specialtyId): void
    {
        $id = (int) $specialtyId;
        $specialty = $this->specialtyModel->findById($id);
        if ($specialty === null) {
            $this->redirect('/');
            return;
        }

        $searchTerm = trim($_GET['q'] ?? '');
        $offices = $this->officeModel->getApprovedBySpecialty($id, $searchTerm ?: null);

        $this->render('patient/offices/index', [
            'offices' => $offices,
            'specialty' => $specialty,
            'searchTerm' => $searchTerm,
            'mode' => 'specialty',
        ]);
    }

    public function searchOffices(): void
    {
        $searchTerm = trim($_GET['q'] ?? '');
        $offices = $this->officeModel->getApprovedBySearch($searchTerm ?: null);

        $this->render('patient/offices/index', [
            'offices' => $offices,
            'specialty' => null,
            'searchTerm' => $searchTerm,
            'mode' => 'search',
        ]);
    }

    public function showOffice($officeId): void
    {
        $id = (int) $officeId;
        $office = $this->officeModel->findById($id);
        if ($office === null || $office['status'] !== 'approved') {
            $this->redirect('/patient/specialties');
            return;
        }

        $doctors = $this->doctorModel->getByOfficeId($id);

        $this->render('patient/offices/show', [
            'office' => $office,
            'doctors' => $doctors,
        ]);
    }
}
