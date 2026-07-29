<?php
require_once __DIR__ . '/BaseModel.php';


class Messaging extends BaseModel {

    /* =======================
       LECTURE
    ======================= */

    public function getAllMessaging($id_agency) {
        $stmt = $this->personnalSelect(
            "messaging",
            "*",
            "WHERE id_agency = ?",
            [$id_agency]
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByIdAndAgency($id,$id_agency) {
        $stmt = $this->personnalSelect(
            "messaging",
            "*",
            "WHERE id_messaging = ? And id_agency = ?",
            [$id_messaging, $id_agency]
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =======================
       CRUD
    ======================= */

    public function create($data, $id_agency) {

        // Insertion en base
        $this->insert(
            "messaging",
            [
                "subject",
                "id_sender",
                "message",
                "file",
                "id_agency",
                "created_at",
            ],
            [
                $data['subject'],
                $data['id_sender'],
                $data['message'],
                $data['file '],
                $id_agency,
                date('Y-m-d'),
            ]
        );

        return $this->pdo->lastInsertId();

    }

    public function update($id, $id_agency, $data) {
        return $this->set(
            "messaging",
            ["subject", "message"],
            [
                $data['subject'],
                $data['message'],
            ],
            "WHERE id_messaging = ? And id_agency = ?",
            [$id $id_agency]
        );
    }

    // =========================
    // Supprimer  (sécurisé par établissement)
    // =========================
    public function delete($id, $id_agency){
        return $this->personalDelete(
            "messaging",
            "WHERE id_messaging = ? AND id_agency = ?",
            [$id, $id_agency]
        );
    }
    

}
