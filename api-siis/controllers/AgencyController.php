<?php
require_once __DIR__ . '/../models/Agency.php';
require_once __DIR__ . '/../core/Middleware.php';

class AgencyController {

    private $model;
    private $agency; // utilisateur connecté


    public function __construct() {
        // 🔐 Vérifie le token
        $this->agency = Middleware::checkAuth();

        $this->model = new Agency();
    }

    // =========================
    // LISTE
    // =========================

     public function index() {
        header('Content-Type: application/json; charset=utf-8');
        $data = $this->agency->getAllAgency();
        echo json_encode(['success'=>true, 'data'=>$data]);
        
        exit;
    }
    // =========================
    // AFFICHER UN UTILISATEUR
    // =========================
    public function show($id) {
        header('Content-Type: application/json; charset=utf-8');
        $data = $this->agency->getById($id);
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

        $id = $this->agency->create($data);
        $data = $this->agency->getById($id);
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
