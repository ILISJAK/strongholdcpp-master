<?php
include 'database.php';

// Getting POST data
$username = $_POST['username'] ?? null;
$password = $_POST['password'] ?? null;

if ($username && $password) {
    try {
        $db = Database::getConnection();

        // Select user with the provided username
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Check if user exists and the password is correct
        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $user['password'])) {
                // Password is correct. Log the user in
                session_start();
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_id'] = $user['id'];
                echo "success"; // Just echo success
            } else {
                echo "Incorrect password";
            }
        } else {
            echo "User not found";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Please enter username and password";
}
?>