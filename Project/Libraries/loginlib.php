<?php
$host = "localhost";
$uname = "root";
$psw = "";
$dbName = "PIF_25_26";
$conn = new mysqli($host, $uname, $psw, $dbName);
if (session_status() != PHP_SESSION_ACTIVE) session_start();
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
function userAlreadyExists($username)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}
function emailAlreadyExists($email) {
    global $conn;
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}
?>

