<?php
function getColor($location_id) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT color FROM locations WHERE location_id = :location_id LIMIT 1");
    $stmt->execute(['location_id' => $location_id]);
    $color = $stmt->fetchColumn();

    return is_string($color) && preg_match('/^#[0-9a-fA-F]{6}$/', $color) ? $color : '#2f3059';
}

?>
