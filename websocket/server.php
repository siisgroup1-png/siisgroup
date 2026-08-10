<?php

require __DIR__ . '/../vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

class WebSocketServer implements MessageComponentInterface
{
    /**
     * Connexions WebSocket
     *
     * resourceId => [
     *     'connection' => ConnectionInterface,
     *     'id_agency' => int|null
     * ]
     */
    protected array $clients = [];


    /**
     * Nouvelle connexion
     */
    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients[$conn->resourceId] = [
            'connection' => $conn,
            'id_agency' => null
        ];

        echo "Client connecté : {$conn->resourceId}\n";
    }


    /**
     * Message reçu du navigateur
     */
    public function onMessage(
        ConnectionInterface $from,
        $msg
    ) {

        $data = json_decode(
            $msg,
            true
        );


        if (!is_array($data)) {

            echo "Message JSON invalide\n";

            return;
        }


        /**
         * =====================================================
         * AUTHENTIFICATION
         * =====================================================
         *
         * Le navigateur envoie :
         *
         * {
         *     "type": "authenticate",
         *     "id_agency": 2
         * }
         */

        if (
            ($data['type'] ?? '') ===
            'authenticate'
        ) {

            $idAgency =
                isset($data['id_agency'])
                    ? (int)$data['id_agency']
                    : 0;


            if ($idAgency <= 0) {

                $from->send(
                    json_encode([
                        'type' => 'error',
                        'message' =>
                            'ID agence invalide'
                    ])
                );

                return;
            }


            $this->clients[
                $from->resourceId
            ]['id_agency'] =
                $idAgency;


            echo
                "Agence {$idAgency} connectée " .
                "avec connexion {$from->resourceId}\n";


            $from->send(
                json_encode([
                    'type' =>
                        'authenticated',

                    'id_agency' =>
                        $idAgency
                ])
            );


            return;
        }


        /**
         * =====================================================
         * NOUVEAU MESSAGE
         * =====================================================
         */

        if (
            ($data['type'] ?? '') ===
            'new_message'
        ) {

            $idSender =
                $this->clients[
                    $from->resourceId
                ]['id_agency'] ?? null;


            $idReceive =
                isset($data['id_receive'])
                    ? (int)$data['id_receive']
                    : 0;


            if (
                !$idSender ||
                !$idReceive
            ) {

                $from->send(
                    json_encode([
                        'type' =>
                            'error',

                        'message' =>
                            'Expéditeur ou destinataire invalide'
                    ])
                );

                return;
            }


            /**
             * Données à transmettre
             */
            $messageData = [

                'type' =>
                    'new_message',

                'id_sender' =>
                    $idSender,

                'id_receive' =>
                    $idReceive,

                'message' =>
                    $data['message'] ?? '',

                'created_at' =>
                    $data['created_at'] ?? null

            ];


            /**
             * =================================================
             * ENVOYER AU DESTINATAIRE
             * =================================================
             */

            foreach (
                $this->clients
                as $client
            ) {

                if (
                    $client['id_agency'] ===
                    $idReceive
                ) {

                    $client['connection']->send(
                        json_encode(
                            $messageData
                        )
                    );

                    echo
                        "Message envoyé à " .
                        "l'agence {$idReceive}\n";
                }

            }


            return;
        }
    }


    /**
     * Client déconnecté
     */
    public function onClose(
        ConnectionInterface $conn
    ) {

        echo
            "Client déconnecté : " .
            $conn->resourceId .
            "\n";


        unset(
            $this->clients[
                $conn->resourceId
            ]
        );
    }


    /**
     * Erreur
     */
    public function onError(
        ConnectionInterface $conn,
        \Exception $e
    ) {

        echo
            "Erreur WebSocket : " .
            $e->getMessage() .
            "\n";


        $conn->close();
    }
}


/**
 * =========================================================
 * DÉMARRAGE
 * =========================================================
 */

$server = IoServer::factory(

    new HttpServer(

        new WsServer(

            new WebSocketServer()

        )

    ),

    8080
);


echo
    "Serveur WebSocket démarré " .
    "sur ws://localhost:8080\n";


$server->run();