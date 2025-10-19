<?php
require_once __DIR__ . '/../../db.php';

// ✅ CORS Headers
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

// ✅ Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// ✅ Read JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || empty($data['email']) || empty($data['password'])) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid input. Email and password required."]);
    exit;
}

$email = trim($data['email']);
$password = password_hash($data['password'], PASSWORD_BCRYPT);

try {
    // ✅ Connect to database using PDO
    $database = new Database();
    $conn = $database->connect();

    // ✅ Check if email exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = :email");
    $check->bindParam(":email", $email);
    $check->execute();

    if ($check->rowCount() > 0) {
        http_response_code(409);
        echo json_encode(["error" => "Email already exists"]);
        exit;
    }

    // ✅ Insert new user
    $stmt = $conn->prepare("INSERT INTO users (email, password_hash) VALUES (:email, :password_hash)");
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":password_hash", $password);

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(["message" => "User registered successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Failed to register user"]);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
?>
