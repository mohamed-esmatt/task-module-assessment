<?php
require_once __DIR__ . '/../../db.php';

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$user_id = $_SESSION['user_id'];

$data = json_decode(file_get_contents("php://input"), true);

try {
    $database = new Database();
    $conn = $database->connect();

    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        $stmt = $conn->prepare("SELECT t.*, u.email as assignee_email FROM tasks t JOIN users u ON t.assignee_id = u.id WHERE t.assignee_id = :user_id ORDER BY t.due_date ASC");
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($tasks);
    }

    if ($method === 'POST') {
        if (!$data || empty($data['title']) || empty($data['due_date']) || empty($data['assignee_email'])) {
            http_response_code(400);
            echo json_encode(["error" => "Title, due date, and assignee email required"]);
            exit;
        }

        // Find assignee id
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->bindParam(":email", $data['assignee_email']);
        $stmt->execute();
        $assignee = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$assignee) {
            http_response_code(404);
            echo json_encode(["error" => "Assignee not found"]);
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO tasks (title, description, due_date, priority, creator_id, assignee_id) VALUES (:title, :description, :due_date, :priority, :creator_id, :assignee_id)");
        $stmt->execute([
            ":title" => $data['title'],
            ":description" => $data['description'] ?? "",
            ":due_date" => $data['due_date'],
            ":priority" => $data['priority'] ?? "low",
            ":creator_id" => $user_id,
            ":assignee_id" => $assignee['id']
        ]);

        http_response_code(201);
        echo json_encode(["message" => "Task created successfully"]);
    }

    if ($method === 'PUT') {
        if (!$data || !isset($data['id'])) {
            http_response_code(400);
            echo json_encode(["error" => "Task ID required"]);
            exit;
        }

        $stmt = $conn->prepare("UPDATE tasks SET is_completed = :is_completed WHERE id = :id AND assignee_id = :user_id");
        $stmt->execute([
            ":is_completed" => $data['is_completed'] ?? 0,
            ":id" => $data['id'],
            ":user_id" => $user_id
        ]);

        echo json_encode(["message" => "Task updated"]);
    }

    if ($method === 'DELETE') {
        if (!$data || !isset($data['id'])) {
            http_response_code(400);
            echo json_encode(["error" => "Task ID required"]);
            exit;
        }

        $stmt = $conn->prepare("DELETE FROM tasks WHERE id = :id AND (assignee_id = :user_id OR creator_id = :user_id)");
        $stmt->execute([
            ":id" => $data['id'],
            ":user_id" => $user_id
        ]);

        echo json_encode(["message" => "Task deleted"]);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
