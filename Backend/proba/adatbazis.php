<?php
session_start();
require("../includes/db.php");

class Database {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getProducts() {
        $sql = "SELECT * FROM products";
        $result = $this->conn->query($sql);
        $products = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }
        
        $_SESSION['products'] = $products;
        return $products;
    }

    public function getUserById($user_id) {
        $sql = "SELECT * FROM users WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $_SESSION['user'] = $user;
            return $user;
        }
        
        return null;
    }
    public function getUserByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email); // 's' az email típus, mert string
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $_SESSION['user'] = $user; // A felhasználói adatokat session-be is tárolhatjuk
            return $user;
        }
        
        return null; // Ha nincs találat, akkor null-t adunk vissza
    }

    public function createCartToUser($user_id, $product_id) {
        // Először megnézzük, hogy a termék már szerepel-e a kosárban
        $sql = "SELECT cart_id, quantity FROM cart WHERE user_id = ? AND product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Ha már szerepel a kosárban, frissítjük a mennyiséget
            $row = $result->fetch_assoc();
            $newQuantity = $row['quantity'] + 1;
            return $this->updateCartItem($row['cart_id'], $newQuantity);
        } else {
            // Ha nincs még benne, új sort adunk hozzá
            $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $user_id, $product_id, 1);

            if ($stmt->execute()) {
                return $stmt->insert_id; // Új kosár ID visszaadása
            }
            return false;
        }
    }

    public function getCartByUser($user_id) {
        $sql = "SELECT *
                FROM cart c 
                JOIN products p ON c.product_id = p.product_id 
                WHERE c.user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $cartItems = [];
        while ($row = $result->fetch_assoc()) {
            $cartItems[] = $row;
        }

        return $cartItems;
    }

    public function updateCartItem($cart_id, $quantity) {
        if ($quantity <= 0) {
            return $this->deleteCartItem($cart_id);
        }

        $sql = "UPDATE cart SET quantity = ? WHERE cart_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $quantity, $cart_id);
        
        return $stmt->execute();
    }

    public function deleteCartItem($cart_id) {
        $sql = "DELETE FROM cart WHERE cart_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $cart_id);

        return $stmt->execute();
    }

    public function clearCart($user_id) {
        $sql = "DELETE FROM cart WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);

        return $stmt->execute();
    }
}


$database = new Database($conn);
$products = $database->getProducts();
//print_r($products);


?>