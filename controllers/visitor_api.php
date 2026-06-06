<?php
include_once '../config/minda.php';
include_once '../config/config.php';
session_start();
header('Content-Type: application/json');


function validateCsrfToken($token) {
    global $pdo; // Access the global database connection

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        return false;
    }

    try {
        // Fetch the stored token from the database using the user ID
        $stmt = $pdo->prepare('SELECT token FROM accounts WHERE account_id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        $dbToken = $stmt->fetchColumn();

        // Compare the session token with the database token
        return hash_equals($dbToken, $token);
    } catch (PDOException $e) {
        error_log('Error validating CSRF token: ' . $e->getMessage());
        return false;
    }
}


$csrfToken = isset($_GET['csrf_token']) ? simpleDecode($_GET['csrf_token']) : '';

if (!validateCsrfToken($csrfToken)) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Invalid CSRF token']);
    exit;
}

// Sanitize and validate the year parameter
$year = filter_input(INPUT_GET, 'year', FILTER_VALIDATE_INT, [
    'options' => [
        'default' => date('Y'),    // Use the current year if not set
        'min_range' => 2000,       // Set a reasonable range
        'max_range' => date('Y')   // No future years
    ]
]);

// Early exit if year validation fails
if ($year === false) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid year format']);
    exit;
}

try {
    // Prepare the SQL query with parameterized input to prevent SQL injection
    $stmt = $pdo->prepare("
        SELECT 
            DATE_FORMAT(check_in, '%M %Y') AS month,
            location_name,
            SUM(adult) AS total_adult,
            SUM(child) AS total_child,
            (SUM(adult) + SUM(child)) AS total
        FROM visits
        WHERE status = 'checked-in' 
          AND YEAR(check_in) = :year
        GROUP BY month, location_name
        ORDER BY month, location_name;
    ");
    
    // Bind the year parameter explicitly as an integer
    $stmt->bindValue(':year', $year, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Close the cursor to free connection resources
    $stmt->closeCursor();

    echo json_encode(['status' => 'success', 'data' => $data]);
} catch (PDOException $e) {
    // Log the error for internal debugging (do not expose in production)
    error_log('Database error: ' . $e->getMessage());

    // Return a generic error message to the client
    echo json_encode(['status' => 'error', 'message' => 'An error occurred while fetching statistics']);
} finally {
    // Close the database connection explicitly
    $pdo = null;
}
?>
