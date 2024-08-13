<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

require "database.php";

$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!isset($data['name']) || !isset($data['surname']) || !isset($data['password'])) {
    echo json_encode(["success" => false, "message" => "Eksik veya hatalı veri gönderildi"]);
    exit;
}

$name = $data['name'];
$surname = $data['surname'];
$password = $data['password'];

$sql = "SELECT * FROM user WHERE name = ? AND surname = ? AND password = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Veritabanı hatası: " . $conn->error]);
    exit;
}

$stmt->bind_param("sss", $name, $surname, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode(["success" => true, "message" => "Giriş başarılı!", "data" => $row]);
} else {
    echo json_encode(["success" => false, "message" => "Geçersiz giriş!"]);
}

$stmt->close();
$conn->close();
?>
