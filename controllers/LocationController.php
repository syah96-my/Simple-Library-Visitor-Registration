<?php

function createLocation($name, $description, $status, $color) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO locations (name, description, status, color) VALUES (:name, :description, :status, :color)");
        $stmt->execute([
            ':name' => $name,
            ':description' => $description,
            ':status' => $status,
            ':color' => $color
        ]);
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        error_log('Error creating location: ' . $e->getMessage());
        return 'Error';
    }
}

// Read all locations
function readLocations() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT * FROM locations");
        $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$locations) {
            return [];
        }

        return $locations;
    } catch (PDOException $e) {
        error_log("Error fetching locations: " . $e->getMessage());
        return [];
    }
}

// Update a location
function updateLocation($location_id, $name, $description, $status, $color) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE locations SET name = :name, description = :description, status = :status, color = :color WHERE location_id = :location_id");
        $stmt->execute([
            ':location_id' => $location_id,
            ':name' => $name,
            ':description' => $description,
            ':status' => $status,
            ':color' => $color
        ]);
        return $stmt->rowCount();
    } catch (PDOException $e) {
        error_log('Error updating location: ' . $e->getMessage());
        return 'Error';
    }
}

// Delete a location
function deleteLocation($location_id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("DELETE FROM locations WHERE location_id = :location_id");
        $stmt->execute([':location_id' => $location_id]);
        return $stmt->rowCount();
    } catch (PDOException $e) {
        error_log('Error deleting location: ' . $e->getMessage());
        return 'Error';
    }
}

// Get a single location by ID
function getLocationById($location_id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM locations WHERE location_id = :location_id");
        $stmt->execute([':location_id' => $location_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log('Error fetching location: ' . $e->getMessage());
        return 'Error';
    }
}

?>
