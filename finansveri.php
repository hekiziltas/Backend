<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
require "database.php";

if (!isset($_GET['user_id'])) {
    echo json_encode(["success" => false, "message" => "User ID eksik"]);
    exit;
}

$user_id = $_GET['user_id']; 

$sql = "SELECT bakiye FROM cuzdan WHERE user_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Veritabanı hatası: " . $conn->error]);
    exit;
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    echo json_encode(["success" => true, "balance" => $data['bakiye']]);
} else {
    echo json_encode(["success" => false, "message" => "Kullanıcı bulunamadı"]);
}

$stmt->close();
$conn->close();
?>
