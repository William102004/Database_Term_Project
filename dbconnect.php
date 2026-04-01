<?php
$servername = "localhost";
$database = "term_project";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully";
    // echo "<br>";
} catch (PDOException $e) {
    echo json_encode(["ok" => false, "error" => "Connection failed: " . $e->getMessage()]);
}
?>