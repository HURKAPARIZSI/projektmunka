<?php
session_start();
require("../includes/db.php");

class Database{
    private $conn;
    public function __construct($conn){
        $this->conn = $conn;
    }
    public function getProducts(){
        $sql = "SELECT * FROM products";
        $result = $this->conn->query($sql);
        $products = [];
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
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
}
$database = new Database($conn);
$products = $database->getProducts();
//print_r($products);





















?>