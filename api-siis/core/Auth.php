<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth {

    public static function generateToken($ageny) {
        $config = require __DIR__ . '/../config/config.php';

        $payload = [
            "iss" => "api-siis",
            "iat" => time(),
            "exp" => time() + $config['JWT_EXPIRE'],
            "data" => [
                "id"    => $ageny['id_agency'],
                "login" => $ageny['login'],
                "country" => $ageny['country']
            ]
        ];

        return JWT::encode(
            $payload,
            $config['JWT_SECRET'],
            $config['JWT_ALGO']
        );
    }

    public static function verifyToken($token) {
        $config = require __DIR__ . '/../config/config.php';

        return JWT::decode(
            $token,
            new Key($config['JWT_SECRET'], $config['JWT_ALGO'])
        );
    }
}
