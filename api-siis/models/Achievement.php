<?php
require_once __DIR__ . '/BaseModel.php';


class Achievement extends BaseModel {

    /* =======================
       LECTURE
    ======================= */

    public function getAllAchievement() {
        $stmt = $this->getAll("achievement","");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================
    // Récupérer par ID et établissement (sécurisé)
    // =========================
    public function getById($id) {
        $stmt = $this->personnalSelect(
            "achievement",
            "*",
            "WHERE id_achievement = ?",
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
            "achievement",
            [
                "libel",
                "description",
                "picture",
            ],
            [
                $data['libel'],
                $data['description'],
                 $data['picture'] ?? null,
            ]
        );

        return $this->pdo->lastInsertId();

    }

    public function update($id, $data) {
        return $this->set(
            "achievement",
            ["libel", "description", "picture"],
            [
                $data['libel'],
                $data['description'],
                $data['picture'],
            ],
            "WHERE id_achievement = ?",
            [$id]
        );
    }

    // =========================
    // Supprimer  (sécurisé par établissement)
    // =========================
    public function delete($id){
        return $this->personalDelete(
            "achievement",
            "WHERE id_achievement = ?",
            [$id]
        );
    }
    

}
