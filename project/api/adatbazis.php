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
        
        return false;
    }
    public function getUserByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email); 
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $_SESSION['user'] = $user; // A felhasználói adatok sessionbe
            return $user;
        }
        
        return null; 
    }
    /*public function getProductByCart($product_id){
        $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
        $stmt -> bind_param('i', $product_id);
    }*/


    public function getCartByUser($user_id) {
        global $conn;
    
        // 1. Kosár lekérdezése user alapján
        $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($row = $result->fetch_assoc()) {
            $cart_id = $row['cart_id'];
        } else {
            echo "Nincs ilyen cart_id";
            return []; // vagy null
        }
    
        // 2. Kosár elemeinek lekérdezése
        $stmt = $conn->prepare("SELECT * FROM cart_items WHERE cart_id = ?");
        $stmt->bind_param("i", $cart_id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $cartItems = [];
    
        while ($item = $result->fetch_assoc()) {
            $cartItems[] = $item;
        }
    
        return $cartItems;
    }
    

    /*public function updateCartItem($cart_id, $quantity) {
        if ($quantity <= 0) {
            return $this->deleteCartItem($cart_id);
        }

        $sql = "UPDATE cart SET quantity = ? WHERE cart_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $quantity, $cart_id);
        
        return $stmt->execute();
    }*/

    public function deleteCartItem($cart_id, $product_id) {
        // Ellenőrizzük, hogy a kapcsolat érvényes
        if ($this->conn === null) {
            throw new Exception('Database connection is not established.');
        }
    
        // SQL lekérdezés előkészítése
        $sql = "DELETE FROM cart_items WHERE cart_id = ? AND product_id = ?";
        $stmt = $this->conn->prepare($sql);
    
        if ($stmt === false) {
            throw new Exception('Failed to prepare SQL query: ' . $this->conn->error);
        }
    
        // Paraméterek kötése
        $stmt->bind_param("ii", $cart_id, $product_id);
    
        // Lekérdezés végrehajtása
        if ($stmt->execute()) {
            return true;
        } else {
            throw new Exception('Error executing query: ' . $stmt->error);
        }
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
$_SESSION['products'] = $products;
if(isset($_SESSION['user_id'])){
    $_SESSION['cart'] = $database->getCartByUser($_SESSION['user_id']);
}else{

}



//print_r($products)


?>