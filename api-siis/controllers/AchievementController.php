<?php
require_once __DIR__ . '/../models/Achievement.php';
require_once __DIR__ . '/../config/upload.php';

class AchievementController {

    private $model;

    public function __construct() {
        $this->model = new Achievement();
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
    }

    // =========================
    // LISTE
    // =========================

    public function index() {
        header('Content-Type: application/json; charset=utf-8');
        $data = $this->model->getAllAchievement();
        foreach ($data as &$e) {
            $e['picture'] = json_decode($e['picture'], true);
        }
        echo json_encode(['success'=>true,'data'=>$data]);
        exit;
    }

    // =========================
    // AFFICHER UN UTILISATEUR
    // =========================
    public function show($id) {
        header('Content-Type: application/json; charset=utf-8');
        $e = $this->model->getById($id);
        if ($e) {
            $e['picture'] = json_decode($e['picture'], true);
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

        if (!empty($_FILES['picture'])) {
            $upload = uploadfile(
                ['png','jpg','jpeg','gif','ico'],
                __DIR__ . '/../uploads/images/'
            );
            $data['picture'] = json_encode($upload);
        }

        $id = $this->model->create($data);
        $e  = $this->model->getById($id);
        $e['picture'] = json_decode($e['picture'], true);

        echo json_encode(['success'=>true,'data'=>$e]);
        exit;
    }

    // =========================
    // METTRE À JOUR
    // =========================
    public function update($id, $data) {
        header('Content-Type: application/json; charset=utf-8');
        $e = $this->model->getById($id);
        if (!$e) {
            echo json_encode(['success'=>false,'message'=>'Item not found']);
            exit;
        }
        if (!empty($_FILES['picture']) && $_FILES['picture']['error'] !== 4) {
            $upload = uploadfile(
                ['png','jpg','jpeg','gif','ico'],
                __DIR__ . '/../uploads/images/'
            );
            $data['picture'] = json_encode($upload);
        } else {
            $data['picture'] = $e['picture']; // garder l'ancien
        }
        $this->model->update($id, $data);
        $e = $this->model->getById($id);
        $e['picture'] = json_decode($e['picture'], true);

        echo json_encode(['success'=>true,'data'=>$e]);
        exit;
    }

    // =========================
    // SUPPRIMER UNE CATEGORIE
    // =========================
    public function delete($id) {
        header('Content-Type: application/json; charset=utf-8');

        $e = $this->model->getById($id);

        if (!$e) {
            echo json_encode(['success'=>false,'message'=>'Item not found']);
            exit;
        }

        $images = json_decode($e['picture'], true);
        if ($images) {
            foreach ($images as $img) {
                $path = __DIR__ . '/../uploads/images/' . basename($img);
                if (file_exists($path)) unlink($path);
            }
        }

        $this->model->delete($id);

        echo json_encode(['success'=>true,'message'=>'Item deleted']);
        exit;
    }

}
