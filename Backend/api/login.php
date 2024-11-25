<?php
session_start();
require '../includes/db.php';

$data = json_decode(file_get_contents("php://input"), true);
$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

$query = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$query->execute([$username]);
$user = $query->fetch();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    echo json_encode(["success" => true, "message" => "Bejelentkezés sikeres!"]);
} else {
    echo json_encode(["success" => false, "message" => "Hibás felhasználónév vagy jelszó!"]);
}
?>
