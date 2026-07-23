<?php
require_once __DIR__ . '/BaseModel.php';
require_once __DIR__ . '/../utils/phpmailer/src/Exception.php';
require_once __DIR__ . '/../utils/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../utils/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class Agency extends BaseModel {

    /* =======================
       LECTURE
    ======================= */

    function generatePassword(string $login, int $randomLength = 8): string
    {
        // Nettoyer le nom
        $prefix = strtoupper(preg_replace('/[^A-Z0-9]/i', '', $login));
        $prefix = substr($prefix, 0, 6);

        // Partie aléatoire sécurisée
        $random = strtoupper(bin2hex(random_bytes(ceil($randomLength / 2))));
        $random = substr($random, 0, $randomLength);

        return $prefix . '-' . $random;
    }

    public function getAllAgency() {
        $stmt = $this->getAll("agency");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->personnalSelect(
            "agency",
            "*",
            "WHERE id_agency = ?",
            [$id]
        );
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    /* =======================
       CRUD
    ======================= */

    public function create($data) {

        // Génération automatique du mot de passe
        $password = $data['password'] ?? $this->generatePassword($data['login']);
        $data['password'] = $password;

        // Insertion en base
        $this->insert(
            "agency",
            [
                "login",
                "country",
                "city",
                "address",
                "phone",
                "email",
                "password",
                "create_at",
            ],
            [
                $data['login'],
                $data['country'],
                $data['city'],
                $data['address'],
                $data['phone'],
                $data['email'],
                password_hash($password, PASSWORD_DEFAULT),
                date('Y-m-d'),
            ]
        );

        $id = $this->pdo->lastInsertId();


        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; 
            $mail->SMTPAuth   = true;
            $mail->Username   = 'etablissementsiis.1@gmail.com';
            $mail->Password   = 'vvzm ioaa gckv vcze '; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('etablissementsiis.1@gmail.com', 'SIIS');
            $mail->addAddress($data['email'], $data['login']);

            $mail->isHTML(true);
            $mail->Subject = 'Information de connexion de SIIS ERP';
            $mail->Body    = "
                <h3>Hello</h3>
                <p>Votre compet a été creer avec succès</p>
                <p><strong>Login :</strong> {$data['login']}<br>
                   <strong>Password:</strong> {$password}</p>
                <p>You can change your password at any time</p>
            ";

            $mail->send();
        } catch (Exception $e) {
            error_log("Error sending email to {$data['email']} : " . $mail->ErrorInfo);
        }

        return $id;
    }

    public function update($id, $data) {
        return $this->set(
            "agency",
            ["login", "country", "city", "address", "phone", "email"],
            [
                $data['login'],
                $data['country'],
                $data['city'],
                $data['address'],
                $data['phone'],
                $data['email']
            ],
            "WHERE id_agency = ?",
            [$id]
        );
    }

    /* =======================
       AUTH
    ======================= */

    public function getByLogin($login) {
        $stmt = $this->personnalSelect(
            "agency",
            "*",
            "WHERE BINARY login = ?",
            [$login]
        );
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
