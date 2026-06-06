<?php
session_start();
header('Content-Type: application/json');
include_once '../config/minda.php';

if (isset($_SESSION['csrf_token'])) {
    
    echo json_encode(['csrf_token' => simpleEncode($_SESSION['csrf_token'])]);
} else {
    echo json_encode(['csrf_token' => null]);
}
?>
