<?php
require_once __DIR__ . '/../models/Messaging.php';
require_once __DIR__ . '/../core/Middleware.php';
require_once __DIR__ . '/../config/upload.php';

class GalleryController {

    private $model;
    private $agency;

    public function __construct() {
        $this->agency = Middleware::checkAuth();
        $this->model = new Messaging();
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
    }

    // =========================
    // LISTE
    // =========================

    public function index() {
        header('Content-Type: application/json; charset=utf-8');
        $id_agency = $this->agency->id_agency;
        $data = $this->model->getAllMessaging($id_agency);
        echo json_encode(['success'=>true,'data'=>$data]);
        exit;
    }

    // =========================
    // AFFICHER UN UTILISATEUR
    // =========================
    public function show($id) {
        header('Content-Type: application/json; charset=utf-8');
        $id_agency = $this->agency->id_agency;
        $e = $this->model->getByIdAndAgency($id, $id_agency);
        if ($e) {
            $e['files'] = json_decode($e['file'], true);
            echo json_encode(['success'=>true, 'data'=>$e]);
        } else {
            echo json_encode(['success'=>false, 'message'=>'Item not found']);
        }
        exit;
    }

    // =========================
    // CREER
    // =========================
    public function store($data) {
        header('Content-Type: application/json; charset=utf-8');

        if (!empty($_FILES['file'])) {
            $upload = uploadfile(
                ['png','jpg','jpeg','gif','ico','pdf','docx'],
                __DIR__ . '/../uploads/files/'
            );
            $data['files'] = json_encode($upload);
        }
        $id_agency = $this->agency->id_agency;
        $id = $this->model->create($data, $id_agency);
        $e = $this->model->getByIdAndAgency($id, $id_agency);
        $e['file'] = json_decode($e['file'], true);

        echo json_encode(['success'=>true,'data'=>$e]);
        exit;
    }

    // =========================
    // METTRE À JOUR
    // =========================
    public function update($id, $data) {
        header('Content-Type: application/json; charset=utf-8');
        $id_agency = $this->agency->id_agency;
        $e = $this->model->getByIdAndAgency($id, $id_agency);
        if (!$e) {
            echo json_encode(['success'=>false,'message'=>'Item not found']);
            exit;
        }

        if (!empty($_FILES['file']) && $_FILES['file']['error'] !== 4) {
            $upload = uploadfile(
                ['png','jpg','jpeg','gif','ico','pdf','docx'],
                __DIR__ . '/../uploads/files/'
            );
            $data['fils'] = json_encode($upload);
        } else {
            $data['file'] = $e['file']; // garder l'ancien
        }
        $this->model->update($id, $id_agency, $data);
        $e = $this->model->getByIdAndAgency($id, $id_agency);
        $e['files'] = json_decode($e['files'], true);

        echo json_encode(['success'=>true,'data'=>$e]);
        exit;
    }

    // =========================
    // SUPPRIMER UNE CATEGORIE
    // =========================
    public function delete($id) {
        header('Content-Type: application/json; charset=utf-8');
        $id_agency = $this->agency->id_agency;
        $e = $this->model->getByIdAndAgency($id, $id_agency);

        if (!$e) {
            echo json_encode(['success'=>false,'message'=>'Item not found']);
            exit;
        }

        $file = json_decode($e['file'], true);
        if ($file) {
            foreach ($file as $img) {
                $path = __DIR__ . '/../uploads/files/' . basename($img);
                if (file_exists($path)) unlink($path);
            }
        }

        $this->model->delete($id, $id_agency);

        echo json_encode(['success'=>true,'message'=>'Item removed']);
        exit;
    }

}
