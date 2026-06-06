<?php

include_once '../../config/config.php';
include_once '../../controllers/VisitController.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['nama'];
    $purpose = $_POST['tujuan'];
    $visitor_id = $_POST['visitorId'];
    $location_id = $_POST['location'];
    $adult = $_POST['adult'];
    $children = $_POST['children'];


    if (checkInVisitor($visitor_id, $name, $location_id, $purpose, $adult, $children)) {
          $card_token = getCardTokenByVisitorId($visitor_id);
            if ($card_token !== null) {
                header("Location: kiosk-success.php?id=" . urlencode($card_token));
                exit;
            } else {
                // visitor_id not found
            }
    } else {
        echo "Check-in failed. Please try again.";
    }
}



?>
