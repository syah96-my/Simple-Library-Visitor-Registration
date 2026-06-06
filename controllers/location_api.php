<?php
include_once '../config/config.php'; // contains $base_url
include_once '../config/minda.php';
include_once '../sessions/session.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: ' . $base_url);

// Simple helper to get JSON body for POST requests
function getRequestData() {
    $input = file_get_contents("php://input");
    return json_decode($input, true);
}

// Function to read locations from the database
function readLocations() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT * FROM locations");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Only POST allowed']);
    exit;
}

function normalizeColor($color) {
    return preg_match('/^#[0-9a-fA-F]{6}$/', $color) ? $color : '#000000';
}

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$data = getRequestData();
if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}

$action = $data['action'] ?? '';

function validateCsrfToken($token) {
    global $pdo;

    if (!isset($_SESSION['user_id'])) {
        return false;
    }

    try {
        $stmt = $pdo->prepare('SELECT token FROM accounts WHERE account_id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        $dbToken = $stmt->fetchColumn();

        return is_string($dbToken) && hash_equals($dbToken, $token);
    } catch (PDOException $e) {
        error_log('Error validating CSRF token: ' . $e->getMessage());
        return false;
    }
}

$csrfToken = isset($data['csrf_token']) ? simpleDecode($data['csrf_token']) : '';

if (!validateCsrfToken($csrfToken)) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Invalid CSRF token']);
    exit;
}

try {
    if ($action === 'add') {
        $name = $data['name'] ?? '';
        $description = $data['description'] ?? '';
        $color = normalizeColor($data['color'] ?? '#000000');

        if (empty($name)) {
            echo json_encode(['status' => 'error', 'message' => 'Name is required']);
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO locations (name, description, color) VALUES (?, ?, ?)");
        $stmt->execute([$name, $description, $color]);

        // Return success message and updated list
        echo json_encode([
            'status' => 'success',
            'message' => 'Location added',
            'locations' => readLocations()
        ]);
        exit;
    }
    elseif ($action === 'edit') {
        $id = $data['id'] ?? 0;
        $name = $data['name'] ?? '';
        $description = $data['description'] ?? '';
        $color = normalizeColor($data['color'] ?? '#000000');

        if (!$id || empty($name)) {
            echo json_encode(['status' => 'error', 'message' => 'ID and Name are required']);
            exit;
        }

        $stmt = $pdo->prepare("UPDATE locations SET name=?, description=?, color=? WHERE location_id=?");
        $stmt->execute([$name, $description, $color, $id]);

        // Return success message and updated list
        echo json_encode([
            'status' => 'success',
            'message' => 'Location updated',
            'locations' => readLocations()
        ]);
        exit;
    }
    elseif ($action === 'delete') {
        $id = $data['id'] ?? 0;

        if (!$id) {
            echo json_encode(['status' => 'error', 'message' => 'ID is required']);
            exit;
        }

        $stmt = $pdo->prepare("DELETE FROM locations WHERE location_id=?");
        $stmt->execute([$id]);

        // Return success message and updated list
        echo json_encode([
            'status' => 'success',
            'message' => 'Location deleted',
            'locations' => readLocations()
        ]);
        exit;
    }
    elseif ($action === 'list') {
        // Return the list of locations directly
        echo json_encode([
            'status' => 'success',
            'locations' => readLocations()
        ]);
        exit;
    }
    else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        exit;
    }
} catch (PDOException $e) {
    error_log('Location API database error: ' . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Database error']);
}
?>
