<?php
require_once '../../config/config.php';
require_once '../../controllers/VisitController.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if (!is_array($input) || empty($input['visitor_id']) || empty($input['location_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing visitor_id or location_id']);
    exit;
}

$visitor_id = $input['visitor_id'];
$location_id = $input['location_id'];

if (registerNewDay($visitor_id, $location_id)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Unable to register visit']);
}
?>
