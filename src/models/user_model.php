<?php

class UserModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function createUser($username, $password, $email)
    {
        // Insert the user data into the database
        $query = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";
        $statement = $this->db->prepare($query);
        $statement->execute(['username' => $username, 'password' => $password, 'email' => $email]);
        // Additional error handling and validation can be implemented here
    }

    public function getUserByUsername($username)
    {
        // Retrieve user data from the database based on the username
        $query = "SELECT * FROM users WHERE username = :username";
        $statement = $this->db->prepare($query);
        $statement->execute(['username' => $username]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);
        return $user;
    }
}
?>