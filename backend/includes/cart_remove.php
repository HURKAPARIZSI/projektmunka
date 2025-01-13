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

$delete = $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
$delete->execute([$_SESSION['user_id'], $product_id]);

echo json_encode(["success" => true, "message" => "Termék eltávolítva a kosárból!"]);
?>
