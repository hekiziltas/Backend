<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit;
}

header('Content-Type: application/json');
require "database.php";

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

switch ($method) {
    case 'POST':
        if (!isset($data['hesap_no']) || !isset($data['hesap_turu'])) {
            echo json_encode(["success" => false, "error" => "Geçersiz veri.", "data" => $data]);
            exit;
        }

        $hesapNo = $data['hesap_no'];
        $hesapTuru = $data['hesap_turu'];

        $sql = "INSERT INTO hesaplar (hesap_no, hesap_turu) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            echo json_encode(["success" => false, "error" => $conn->error]);
            exit;
        }

        $stmt->bind_param("ss", $hesapNo, $hesapTuru);

        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => $stmt->error]);
        }

        $stmt->close();
        break;

    case 'GET':
        $sql = "SELECT * FROM hesaplar";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $hesaplar = [];
            while($row = $result->fetch_assoc()) {
                $hesaplar[] = $row;
            }
            echo json_encode(["success" => true, "data" => $hesaplar]);
        } else {
            echo json_encode(["success" => false, "error" => "Kayıt bulunamadı."]);
        }
        break;

        case 'DELETE':
            $id = $_GET['id'];
            
            $sql = "DELETE FROM hesaplar WHERE id = ?";
            $stmt = $conn->prepare($sql);
            
            if ($stmt === false) {
                echo json_encode(["success" => false, "error" => $conn->error]);
                exit;
            }
            
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false, "error" => $stmt->error]);
            }
            
            $stmt->close();
            break;
        
            case 'PUT':
                $data = json_decode(file_get_contents("php://input"), true);
                
                if (!isset($data['id']) || !isset($data['hesap_no']) || !isset($data['hesap_turu'])) {
                    echo json_encode(["success" => false, "error" => "Geçersiz veri.", "data" => $data]);
                    exit;
                }
                
                $id = $data['id'];
                $hesapNo = $data['hesap_no'];
                $hesapTuru = $data['hesap_turu'];
                
                $sql = "UPDATE hesaplar SET hesap_no = ?, hesap_turu = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                
                if ($stmt === false) {
                    echo json_encode(["success" => false, "error" => $conn->error]);
                    exit;
                }
                
                $stmt->bind_param("ssi", $hesapNo, $hesapTuru, $id);
                
                if ($stmt->execute()) {
                    echo json_encode(["success" => true]);
                } else {
                    echo json_encode(["success" => false, "error" => $stmt->error]);
                }
                
                $stmt->close();
                break;
            

    default:
        echo json_encode(["success" => false, "error" => "Geçersiz istek yöntemi."]);
        break;
}

$conn->close();
?>
