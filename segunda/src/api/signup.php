<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization");


// Include the Database class
require_once __DIR__ . '/database.php'; // Adjust this path accordingly

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->email, $data->password)) {
    echo json_encode(['status' => 'error', 'message' => 'Email and password are required']);
    exit;
}

$email = $data->email;
$password = password_hash($data->password, PASSWORD_BCRYPT);

// Initialize and connect
$db = new Database();
$db->loadEnv($db->path);
$conn = $db->connect();

if (!$conn) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}

try {
    $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'User registered']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Insert failed']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

echo 'DB_HOST: ' . getenv('DB_HOST') . '<br>';
echo 'DB_NAME: ' . getenv('DB_NAME') . '<br>';
echo 'DB_USER: ' . getenv('DB_USER') . '<br>';
echo 'DB_PASS: ' . getenv('DB_PASS') . '<br>';


?>
