<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "phcci_document_manager";
date_default_timezone_set('Asia/Kuala_Lumpur');

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
