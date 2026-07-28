<?php
require_once __DIR__ . '/../models/Agency.php';
require_once __DIR__ . '/../core/Middleware.php';

class AgencyController {

    private $model;



    public function __construct() {
        $this->model = new Agency();
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
    }

    // =========================
    // LISTE
    // =========================

     public function index() {
        header('Content-Type: application/json; charset=utf-8');
        $data = $this->model->getAllAgency();
        echo json_encode(['success'=>true, 'data'=>$data]);
        
        exit;
    }
    // =========================
    // AFFICHER UN UTILISATEUR
    // =========================
    public function show($id) {
        header('Content-Type: application/json; charset=utf-8');
        $data = $this->model->getById($id);
        if ($data) {
            echo json_encode(['success'=>true, 'data'=>$data]);
        } else {
            echo json_encode(['success'=>false, 'message'=>'Agency  not found']);
        }
        exit;
    }

    // =========================
    // CREER
    // =========================
    public function store($data) {
        header('Content-Type: application/json; charset=utf-8');

        $id = $this->model->create($data);
        $data = $this->model->getById($id);
        echo json_encode(['success'=>true, 'data'=>$data]);
        exit;
    }

    // =========================
    // METTRE À JOUR
    // =========================
    public function update($id, $data) {
        header('Content-Type: application/json; charset=utf-8');
        $e = $this->model->getById($id);
        if (!$e) {
            echo json_encode(['success'=>false,'message'=>'Agency not found']);
            exit;
        }
        // Mise à jour
        $this->model->update($id, $data);

        // Relecture
        $e = $this->model->getById($id);

        echo json_encode(['success'=>true,'data'=>$e]);
        exit;
    }



}
