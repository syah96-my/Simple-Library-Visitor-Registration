<?php
include_once '../../config/config.php';
include_once '../../controllers/VisitController.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $cardToken = $_GET['id'];

    if (!preg_match('/^[a-f0-9]{64}$/', $cardToken)) {
        header("Location: invalid-id.php");
        exit;
    }

    $visitor_id = getVisitorIdByCardToken($cardToken);
    
    if ($visitor_id !== null) {
        // Set the cookie with the visitor ID instead of the user ID for consistency
        $cookieName = 'visitor_id';
        $expireInDays = 365;
        setcookie($cookieName, $visitor_id, [
            'expires' => time() + (86400 * $expireInDays),
            'path' => '/',
            'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        
        // Redirect to the visitor card page
        header("Location: visitor-card.php");
        exit;
    } else {
        header("Location: no-visitor.php");
        exit;
    }
} else {
    // Redirect if no ID was provided
    header("Location: no-location.php");
    exit;
}
?>
