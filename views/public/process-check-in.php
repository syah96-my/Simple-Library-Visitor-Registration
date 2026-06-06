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
        header("Location: visitor-card.php");
        exit();
    } else {
        echo "Check-in failed. Please try again.";
    }
}



?>