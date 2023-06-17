<?php
include 'database.php';

// Getting POST data
$username = $_POST['username'] ?? null;
$password = $_POST['password'] ?? null;
$email = $_POST['email'] ?? null;

if ($username && $password && $email) {
    try {
        $db = Database::getConnection();
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert the user into the database
        $stmt = $db->prepare("INSERT INTO users (username, password, email) VALUES (:username, :password, :email)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        echo "User registered successfully";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Please fill in all the fields";
}
?>