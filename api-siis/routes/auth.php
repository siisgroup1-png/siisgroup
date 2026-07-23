<?php
require_once __DIR__ . '/../core/Middleware.php';
require_once __DIR__ . '/../models/Agency.php';
require_once __DIR__ . '/../core/Auth.php';
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

$login = $data['login'] ?? '';
$password = $data['password'] ?? '';

if (!$login || !$password) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing field'
    ]);
    exit;
}


$agencyModel = new Agency();

$agency = $agencyModel->getByLogin($login);

if (!$agency || !password_verify($password, $agency['password'])) {
    echo json_encode([
        'success' => false,
    ]);
    exit;
}

$token = Auth::generateToken($agency);
echo json_encode([
    'success' => true,
    'token' => $token,
]);

