<?php

require_once __DIR__ . '/BaseModel.php';

class Messaging extends BaseModel
{

    // =========================================================
    // LECTURE : CONVERSATION ENTRE DEUX AGENCES
    // =========================================================

    public function getAllMessaging($id_agency, $id_receive)
    {
        $stmt = $this->personnalSelect(
            "messaging",
            "*",
            "WHERE
                (id_sender = ? AND id_receive = ?)
                OR
                (id_sender = ? AND id_receive = ?)
             ORDER BY created_at ASC",
            [
                $id_agency,
                $id_receive,

                $id_receive,
                $id_agency
            ]
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // =========================================================
    // LECTURE : UN MESSAGE
    // =========================================================

    public function getByIdAndAgency($id, $id_agency)
    {
        $stmt = $this->personnalSelect(
            "messaging",
            "*",
            "WHERE id_messaging = ?
             AND id_sender = ?",
            [
                $id,
                $id_agency
            ]
        );

        // Un seul message
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // =========================================================
    // CREER / ENVOYER UN MESSAGE
    // =========================================================

    public function create($data, $id_agency)
    {

        $this->insert(
            "messaging",
            [
                "subject",
                "id_sender",
                "message",
                "file",
                "id_receive",
                "created_at"
            ],
            [
                $data['subject'] ?? null,

                // Agence connectée
                $id_agency,

                $data['message'] ?? null,

                $data['file'] ?? null,

                // Agence destinataire
                $data['id_receive'],

                gmdate('Y-m-d H:i:s')
            ]
        );

        return $this->pdo->lastInsertId();
    }


    // =========================================================
    // MODIFIER UN MESSAGE
    // =========================================================

    public function update($id, $id_agency, $data)
    {

        return $this->set(
            "messaging",
            [
                "subject",
                "message"
            ],
            [
                $data['subject'] ?? null,
                $data['message'] ?? null
            ],
            "WHERE id_messaging = ?
             AND id_sender = ?",
            [
                $id,
                $id_agency
            ]
        );
    }


    // =========================================================
    // SUPPRIMER UN MESSAGE
    // =========================================================

    public function delete($id, $id_agency)
    {

        return $this->personalDelete(
            "messaging",
            "WHERE id_messaging = ?
             AND id_sender = ?",
            [
                $id,
                $id_agency
            ]
        );
    }

}