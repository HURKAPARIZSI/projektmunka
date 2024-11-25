<?php
session_start();
require '../includes/db.php';

;//$data = json_decode(file_get_contents("php://input"), true);
$username = $_GET["username"];//$data['username'] ?? '';
$password = $_GET["password"];//$data['password'] ?? '';

$query = $pdo->prepare("SELECT * FROM Users WHERE username = ?");
$query->execute([$username]);
$user = $query->fetch();

if ($user &&$password == $user['password']) {
    $_SESSION['user_id'] = $user['user_id'];
    echo json_encode(["success" => true, "message" => "Bejelentkezés sikeres!"]);
} else {
    echo json_encode(["success" => false, "message" => "Hibás felhasználónév vagy jelszó!"]);
}
?>
