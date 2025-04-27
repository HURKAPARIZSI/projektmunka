<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Bejelentkezés szükséges!"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$product_id = $data['product_id'];
$quantity = $data['quantity'] ?? 1;

$query = $pdo->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
$query->execute([$_SESSION['user_id'], $product_id]);
$cartItem = $query->fetch();

if ($cartItem) {
    $update = $pdo->prepare("UPDATE cart SET quantity = quantity + ? WHERE id = ?");
    $update->execute([$quantity, $cartItem['id']]);
} else {
    $insert = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
    $insert->execute([$_SESSION['user_id'], $product_id, $quantity]);
}

echo json_encode(["success" => true, "message" => "Termék a kosárhoz adva!"]);
?>
