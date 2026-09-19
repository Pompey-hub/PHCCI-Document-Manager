<?php
session_start();
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("
        SELECT id, first_name, last_name, email, password, role
        FROM users
        WHERE email = ?
    ");

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            header("Location: ../admin/index.php");
        } else {
            echo "Invalid password";
        }

    } else {
        echo "User not found";
    }
}
?>