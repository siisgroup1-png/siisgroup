<?php
require_once __DIR__ . '/BaseModel.php';


class Gallery extends BaseModel {

    /* =======================
       LECTURE
    ======================= */

    public function getAllGallery() {
        $stmt = $this->getAll("gallery","");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================
    // Récupérer par ID et établissement (sécurisé)
    // =========================
    public function getById($id) {
        $stmt = $this->personnalSelect(
            "gallery",
            "*",
            "WHERE id_gallery = ?",
            [$id]
        );
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* =======================
       CRUD
    ======================= */

    public function create($data) {

        // Insertion en base
        $this->insert(
            "gallery",
            [
                "description",
                "picture",
            ],
            [
                $data['description'],
                $data['picture'],
            ]
        );

        return $this->pdo->lastInsertId();

    }

    public function update($id, $data) {
        return $this->set(
            "gallery",
            ["description", "picture"],
            [
                $data['description'],
                $data['picture'],
            ],
            "WHERE id_gallery = ?",
            [$id]
        );
    }

    // =========================
    // Supprimer  (sécurisé par établissement)
    // =========================
    public function delete($id){
        return $this->personalDelete(
            "gallery",
            "WHERE id_gallery = ?",
            [$id]
        );
    }
    

}
