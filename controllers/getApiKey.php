<?php
include_once '../config/config.php';  // Load the config file
header('Content-Type: application/json');

http_response_code(404);
echo json_encode(['status' => 'error', 'message' => 'Not found']);
?>
