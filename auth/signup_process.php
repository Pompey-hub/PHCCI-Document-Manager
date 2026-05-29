<?php
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $first_name = $_POST['first_name'];
    $last_name  = $_POST['last_name'];
    $email      = $_POST['email'];
    $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // check if email exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "Email already exists";
        exit;
    }

    $stmt = $conn->prepare("
        INSERT INTO users (first_name, last_name, email, password)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->bind_param("ssss", $first_name, $last_name, $email, $password);

    if ($stmt->execute()) {
        header("Location: signin.php?success=1");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>