<?php

require_once __DIR__ . '/../models/Messaging.php';
require_once __DIR__ . '/../core/Middleware.php';
require_once __DIR__ . '/../config/upload.php';

class MessagingController {

    private $model;
    private $agency;


    // =========================================================
    // CONSTRUCTEUR
    // =========================================================

    public function __construct() {

        $this->agency = Middleware::checkAuth();

        $this->model = new Messaging();

        error_reporting(
            E_ALL & ~E_NOTICE & ~E_WARNING
        );
    }


    // =========================================================
    // LISTE DES MESSAGES D'UNE CONVERSATION
    // =========================================================

    public function index() {

        header(
            'Content-Type: application/json; charset=utf-8'
        );


        // =====================================================
        // AGENCE CONNECTÉE
        // =====================================================

        $id_sender = (int) $this->agency->id;


        // =====================================================
        // AGENCE DESTINATAIRE
        // =====================================================

        $id_receive =
            isset($_GET['id_agency'])
                ? (int) $_GET['id_agency']
                : 0;


        if ($id_receive <= 0) {

            echo json_encode([
                'success' => false,
                'message' =>
                    'Agence destinataire manquante'
            ]);

            exit;
        }


        // =====================================================
        // RÉCUPÉRER LA CONVERSATION
        // =====================================================

        $data =
            $this->model->getAllMessaging(
                $id_sender,
                $id_receive
            );


        echo json_encode([
            'success' => true,
            'data' => $data
        ]);

        exit;
    }


    // =========================================================
    // AFFICHER UN MESSAGE
    // =========================================================

    public function show($id) {

        header(
            'Content-Type: application/json; charset=utf-8'
        );


        $id_agency =
            (int) $this->agency->id;


        $e =
            $this->model->getByIdAndAgency(
                (int) $id,
                $id_agency
            );


        if (!$e) {

            echo json_encode([
                'success' => false,
                'message' => 'Message introuvable'
            ]);

            exit;
        }


        // Transformer le JSON du fichier
        if (!empty($e['file'])) {

            $e['file'] =
                json_decode(
                    $e['file'],
                    true
                );

        } else {

            $e['file'] = null;

        }


        echo json_encode([
            'success' => true,
            'data' => $e
        ]);

        exit;
    }


    // =========================================================
    // CRÉER / ENVOYER UN MESSAGE
    // =========================================================

    public function store($data) {

        header(
            'Content-Type: application/json; charset=utf-8'
        );


        // =====================================================
        // EXPÉDITEUR
        // =====================================================

        $id_sender = (int) $this->agency->id;


        // =====================================================
        // DESTINATAIRE
        // =====================================================

        $id_receive =
            isset($data['id_receive'])
                ? (int) $data['id_receive']
                : 0;


        if ($id_receive <= 0) {

            echo json_encode([
                'success' => false,
                'message' =>
                    'Agence destinataire manquante'
            ]);

            exit;
        }


        // =====================================================
        // MESSAGE
        // =====================================================

        $message =
            trim(
                $data['message'] ?? ''
            );


        if ($message === '') {

            echo json_encode([
                'success' => false,
                'message' =>
                    'Le message ne peut pas être vide'
            ]);

            exit;
        }


        // =====================================================
        // SUJET
        // =====================================================

        $subject =
            trim(
                $data['subject'] ?? ''
            );


        // =====================================================
        // FICHIER
        // =====================================================

        $data['file'] = null;


        if (
            isset($_FILES['file']) &&
            $_FILES['file']['error'] !==
                UPLOAD_ERR_NO_FILE
        ) {

            $upload =
                uploadfile(
                    [
                        'png',
                        'jpg',
                        'jpeg',
                        'gif',
                        'ico',
                        'pdf',
                        'docx'
                    ],
                    __DIR__ .
                    '/../uploads/files/'
                );


            $data['file'] =
                json_encode($upload);
        }


        // =====================================================
        // DONNÉES À ENREGISTRER
        // =====================================================

        $messageData = [

            'subject' =>
                $subject,

            'message' =>
                $message,

            'file' =>
                $data['file'],

            'id_receive' =>
                $id_receive
        ];


        // =====================================================
        // INSERTION
        // =====================================================

        $id =
            $this->model->create(
                $messageData,
                $id_sender
            );


        // =====================================================
        // RÉCUPÉRER LE MESSAGE CRÉÉ
        // =====================================================

        $e =
            $this->model->getByIdAndAgency(
                $id,
                $id_sender
            );


        if ($e && !empty($e['file'])) {

            $e['file'] =
                json_decode(
                    $e['file'],
                    true
                );
        }


        echo json_encode([
            'success' => true,
            'message' =>
                'Message envoyé avec succès',
            'data' => $e
        ]);

        exit;
    }


    // =========================================================
    // MODIFIER
    // =========================================================

    public function update($id, $data) {

        header(
            'Content-Type: application/json; charset=utf-8'
        );


        $id_agency =
            (int) $this->agency->id;


        $e =
            $this->model->getByIdAndAgency(
                (int) $id,
                $id_agency
            );


        if (!$e) {

            echo json_encode([
                'success' => false,
                'message' => 'Message introuvable'
            ]);

            exit;
        }


        $this->model->update(
            (int) $id,
            $id_agency,
            $data
        );


        $e =
            $this->model->getByIdAndAgency(
                (int) $id,
                $id_agency
            );


        if ($e && !empty($e['file'])) {

            $e['file'] =
                json_decode(
                    $e['file'],
                    true
                );
        }


        echo json_encode([
            'success' => true,
            'data' => $e
        ]);

        exit;
    }


    // =========================================================
    // SUPPRIMER
    // =========================================================

    public function delete($id) {

        header(
            'Content-Type: application/json; charset=utf-8'
        );


        $id_agency =
            (int) $this->agency->id;


        $e =
            $this->model->getByIdAndAgency(
                (int) $id,
                $id_agency
            );


        if (!$e) {

            echo json_encode([
                'success' => false,
                'message' => 'Message introuvable'
            ]);

            exit;
        }


        // =====================================================
        // SUPPRIMER LE FICHIER PHYSIQUE
        // =====================================================

        if (!empty($e['file'])) {

            $files =
                json_decode(
                    $e['file'],
                    true
                );


            if (is_array($files)) {

                foreach ($files as $file) {

                    $path =
                        __DIR__ .
                        '/../uploads/files/' .
                        basename($file);


                    if (file_exists($path)) {

                        unlink($path);

                    }
                }
            }
        }


        // =====================================================
        // SUPPRIMER LE MESSAGE
        // =====================================================

        $this->model->delete(
            (int) $id,
            $id_agency
        );


        echo json_encode([
            'success' => true,
            'message' =>
                'Message supprimé avec succès'
        ]);

        exit;
    }

}