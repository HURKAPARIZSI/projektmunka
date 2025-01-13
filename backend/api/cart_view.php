<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Bejelentkezés szükséges!"]);
    exit;
}

$query = $pdo->prepare("
    SELECT p.name, p.price, c.quantity 
    FROM cart c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = ?
");
$query->execute([$_SESSION['user_id']]);
$cartItems = $query->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($cartItems);
?>
