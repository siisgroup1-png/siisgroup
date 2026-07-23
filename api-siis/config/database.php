<?php
class Database {
    private static $servers = [];

    protected $pdo = null;
    private $initialized = false; // flag pour éviter réinitialisation

    public function __construct() {
        $this->connect();
    }

    private function createDatabaseIfNotExists(array $srv){
        try {

            // Connexion au serveur MySQL (sans sélectionner de base)
            $pdo = new PDO(
                "mysql:host={$srv['host']};charset=utf8mb4",
                $srv['user'],
                $srv['password']
            );

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $dbname = str_replace('`', '``', $srv['dbname']);

            $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`
                        CHARACTER SET utf8mb4
                        COLLATE utf8mb4_unicode_ci");

        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function connect() {

        self::$servers = [
            [
                'host' => 'localhost',
                'dbname' => 'siis',
                'user' => 'root',
                'password' => ''
            ],
             // [
             //   // autre connexion
                
                
             // ]
        ];
    
        foreach (self::$servers as $srv) {
            try {
                // Création automatique de la base si elle n'existe pas
                $this->createDatabaseIfNotExists($srv);
                $this->pdo = new PDO(
                    "mysql:host={$srv['host']};dbname={$srv['dbname']};charset=utf8mb4",
                    $srv['user'],
                    $srv['password'],
                    [PDO::ATTR_PERSISTENT => true]
                );
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                error_log("Connecté à la DB sur {$srv['host']}");
                break;
            } catch (PDOException $e) {
                error_log("Impossible de se connecter à {$srv['host']}: " . $e->getMessage());
            }
        }

        if (!$this->pdo) {
            die("Serveur de base de donnée indisponible !");
        }

        // ✅ Initialisation uniquement si pas déjà fait
        $lockFile = __DIR__ . '/db_initialized.lock';
        if (!$this->initialized && !file_exists($lockFile)) {
            $this->initDatabase();
            file_put_contents($lockFile, date('c')); // créer le lock
            $this->initialized = true;
        }

        return $this->pdo;
    }

    private function initDatabase() {
        try {

            $tables = [
                /*----------------------------------
                MESSAGERIE INSTENTANNEE (SIIS MESSENGER)
                 ----------------------------------*/
                "agency" => "
                    CREATE TABLE IF NOT EXISTS agency (
                        id_agency INT AUTO_INCREMENT PRIMARY KEY,
                        login VARCHAR(50) NOT NULL,
                        country VARCHAR(50) NOT NULL,
                        city VARCHAR(50) NOT NULL,
                        address VARCHAR(50) NOT NULL,
                        phone VARCHAR(50) NOT NULL,
                        email VARCHAR(50) NOT NULL,
                        password TEXT NOT NULL,
                        created_at DATE
                    )",
                "users" => "
                    CREATE TABLE IF NOT EXISTS users (
                        id_user INT AUTO_INCREMENT PRIMARY KEY,
                        avatar TEXT NOT NULL,
                        first_name VARCHAR(50) NOT NULL,
                        last_name VARCHAR(50) NOT NULL,
                        address VARCHAR(50) NOT NULL,
                        email VARCHAR(50) NOT NULL,
                        phone VARCHAR(50) NOT NULL,
                        status VARCHAR(50) NOT NULL, /* infographe, developpeur...*/
                        salary VARCHAR(50) NOT NULL, /* infographe, developpeur...*/
                        created_at DATE,
                        id_agency INT,
                        FOREIGN KEY (id_agency) REFERENCES agency(id_agency)
                        ON DELETE CASCADE ON UPDATE CASCADE
                    )",
                "permission" => "
                    CREATE TABLE IF NOT EXISTS permission (
                        id_permission INT AUTO_INCREMENT PRIMARY KEY,
                        motif TEXT,
                        start_permission DATE,
                        duration INT,
                        end_permission DATE,
                        id_agency INT,
                        id_user INT,
                        FOREIGN KEY (id_agency) REFERENCES agency(id_agency)
                        ON DELETE CASCADE ON UPDATE CASCADE,
                        FOREIGN KEY (id_user) REFERENCES user(id_user)
                        ON DELETE CASCADE ON UPDATE CASCADE
                    )",
                "salary_section" => "
                    CREATE TABLE IF NOT EXISTS salary_section (
                        id_salary_section INT AUTO_INCREMENT PRIMARY KEY,
                        libel VARCHAR(50) NOT NULL,
                        code VARCHAR(50) NOT NULL,
                        Type VARCHAR(50) NOT NULL, /*GAIN, REVENU*/
                        value INT,
                        id_user INT,
                        id_agency INT,
                        FOREIGN KEY (id_agency) REFERENCES agency(id_agency)
                        ON DELETE CASCADE ON UPDATE CASCADE,
                        FOREIGN KEY (id_user) REFERENCES user(id_user)
                        ON DELETE CASCADE ON UPDATE CASCADE
                    )",
                "paie" => "
                    CREATE TABLE IF NOT EXISTS paie (
                        id_paie INT AUTO_INCREMENT PRIMARY KEY,
                        id_agency INT,
                        id_user INT,
                        id_salary_section INT,
                        FOREIGN KEY (id_agency) REFERENCES agency(id_agency)
                        ON DELETE CASCADE ON UPDATE CASCADE,
                        FOREIGN KEY (id_user) REFERENCES user(id_user)
                        ON DELETE CASCADE ON UPDATE CASCADE,
                        FOREIGN KEY (id_salary_section) REFERENCES salary_section(id_salary_section)
                        ON DELETE CASCADE ON UPDATE CASCADE
                    )",
                "conversation" => "
                    CREATE TABLE IF NOT EXISTS conversation (
                        id_conversation INT AUTO_INCREMENT PRIMARY KEY,
                        subject VARCHAR(50) NOT NULL,
                        id_sender INT,
                        massage TEXT,
                        id_agency INT, /*receipt*/
                        created_at DATETIME,
                        FOREIGN KEY (id_agency) REFERENCES agency(id_agency)
                        ON DELETE CASCADE ON UPDATE CASCADE /* lorsque j'envoie je recupere l'id de l'entreprise qui envoie je le met dans sender et l'id de l'ntreprise qui recoit dans id agency*/
                    )",
                "achievement" => "
                    CREATE TABLE IF NOT EXISTS achievement (
                        id_achievement INT AUTO_INCREMENT PRIMARY KEY,
                        libel VARCHAR(50) NOT NULL,
                        description VARCHAR(50) NOT NULL,
                        picture TEXT
                    )",
                "gallery" => "
                    CREATE TABLE IF NOT EXISTS gallery (
                        id_gallery INT AUTO_INCREMENT PRIMARY KEY,
                        description VARCHAR(50) NOT NULL,
                        picture TEXT
                    )",
                "client" => "
                    CREATE TABLE IF NOT EXISTS client (
                        id_client INT AUTO_INCREMENT PRIMARY KEY,
                        name VARCHAR(50) NOT NULL,
                        email VARCHAR(50) NOT NULL,
                        phone VARCHAR(50) NOT NULL,
                        need VARCHAR(50) NOT NULL,
                        hope_return VARCHAR(50) NOT NULL,
                        created_at DATE,
                        id_agency INT,
                        FOREIGN KEY (id_agency) REFERENCES agency(id_agency)
                        ON DELETE CASCADE ON UPDATE CASCADE
                    )",
                    /*----------------------------------
                    APPLICATION DE co-voiturage (SIIS CO-DRIVE)
                    ----------------------------------*/
                "chauffeur" => "
                    CREATE TABLE IF NOT EXISTS chauffeur (
                        id_ INT AUTO_INCREMENT PRIMARY KEY,
                        avatar TEXT NOT NULL,
                        first_name VARCHAR(50) NOT NULL,
                        last_name VARCHAR(50) NOT NULL,
                        address VARCHAR(50) NOT NULL,
                        email VARCHAR(50) NOT NULL,
                        phone VARCHAR(50) NOT NULL,
                        id_card VARCHAR(50) NOT NULL,
                        licence_drive_id VARCHAR(50) NOT NULL,
                        type_vehicule VARCHAR(50) NOT NULL,
                        photo_vehicule VARCHAR(50) NOT NULL,
                        Matricul VARCHAR(50) NOT NULL,
                        available_place INT,
                        starting_point VARCHAR(50) NOT NULL,
                        end_point VARCHAR(50) NOT NULL,
                        created_at DATE
                    )",
                "passager" => "
                    CREATE TABLE IF NOT EXISTS passager (
                        id_passager INT AUTO_INCREMENT PRIMARY KEY,
                        avatar TEXT NOT NULL,
                        first_name VARCHAR(50) NOT NULL,
                        last_name VARCHAR(50) NOT NULL,
                        address VARCHAR(50) NOT NULL,
                        email VARCHAR(50) NOT NULL,
                        phone VARCHAR(50) NOT NULL,
                        id_card VARCHAR(50) NOT NULL,
                        number_of_person VARCHAR(50) NOT NULL,
                        created_at DATE
                    )",
            
            ];

            foreach ($tables as $sql) {
                $this->pdo->exec($sql);
            }

            $this->seedAgency();
            

        } catch (PDOException $e) {
            die("Erreur initialisation DB : " . $e->getMessage());
        }
    }


     private function seedAgency()
    {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM agency");
            $stmt->execute();

            if ($stmt->fetchColumn() == 0) {

                $stmt = $this->pdo->prepare("
                    INSERT INTO agency
                    (login, country, city, address, phone, email, password, created_at)
                    VALUES
                    (:login, :country, :city, :address, :phone, :email, :password, :created_at)
                ");

                $stmt->execute([
                    ':login' => 'siis',
                    ':country' => 'Cameroun',
                    ':city' => 'Yaoundé',
                    ':address' => 'Monté anne rouge',
                    ':phone' => '678833252',
                    ':email' => 'siisgroup1@gmail.com',
                    ':password' => password_hash('siis', PASSWORD_DEFAULT),
                    ':created_at' => date('Y-m-d')
                ]);
            }

        } catch (PDOException $e) {
            die("Une Erreur c'est produite : " . $e->getMessage());
        }
    }


    public function disconnect() {
        $this->pdo = null;
    }
}
?>