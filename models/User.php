<?php
// User.php
include_once '../config/config.php';

class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Fetch a user by ID
    public function getUserById($user_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetch();
    }

    // Create a new user
    public function createUser($username, $password, $role) {
        $stmt = $this->pdo->prepare("INSERT INTO users (username, password, role, created_at) VALUES (:username, :password, :role, NOW())");
        $stmt->execute([
            'username' => $username,
            'password' => password_hash($password, PASSWORD_BCRYPT), // Hash the password
            'role' => $role
        ]);
    }
}
?>
