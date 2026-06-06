<?php
session_start();
include_once '../../config/config.php';
include_once '../../controllers/CookieRetrieve.php';
include_once '../../controllers/VisitController.php';

// First, check if 'loc' parameter exists in GET
if (isset($_GET['loc']) && !empty($_GET['loc'])) {
    $location_id = base64_decode($_GET['loc'], true);
    if ($location_id === false || !ctype_digit($location_id)) {
        header("Location: invalid-id.php");
        exit;
    }
} else {
    // Location not provided, redirect to no-location page
    header("Location: no-location.php");
    exit;
}

// Check if visitor_id cookie exists and not empty
if (isset($_COOKIE['visitor_id']) && !empty($_COOKIE['visitor_id'])) {
    $visitor_id = $_COOKIE['visitor_id']; // get visitor_id from cookie
    $userInfo = getUserInfo();

    if ($userInfo === false || $userInfo['visitor_id'] === null) {
        setcookie('visitor_id', '', [
            'expires' => time() - 3600,
            'path' => '/',
            'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        unset($_COOKIE['visitor_id']);
        $_SESSION['location_id'] = $location_id;
        header("Location: check-in.php?loc=" . rawurlencode($_GET['loc']));
        exit;
    }

        // Use your checkLocation function to see if the user already checked in today at this location
        if (checkLocation($visitor_id, $location_id)) {
            // Case 1: Checked in today at the same location
            header("Location: visitor-card.php");
            exit;
        } else {
            // Case 2 & 3: Either checked in today at a different location or not checked in today at all
            if (isCheckedInToday($visitor_id)) {
                    if (updateNewLocation($visitor_id, $location_id)) {
                        header("Location: visitor-card.php");
                        exit;
                    } else {
                        echo "Failed to update location.";
                    }

            } else {
         echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>SweetAlert2 Demo</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .swal2-popup {
            max-width: 90% !important;
            width: auto !important;
            font-size: 1rem !important;
        }
        .swal2-confirm, .swal2-deny {
            padding: 0.8rem 1.5rem !important;
            font-size: 1rem !important;
        }
        @media (max-width: 480px) {
            .swal2-popup {
                font-size: 0.9rem !important;
            }
            .swal2-confirm, .swal2-deny {
                padding: 0.6rem 1.2rem !important;
                font-size: 0.9rem !important;
            }
        }
        @media (max-width: 360px) {
            .swal2-popup {
                font-size: 0.8rem !important;
            }
            .swal2-confirm, .swal2-deny {
                padding: 0.5rem 1rem !important;
                font-size: 0.8rem !important;
            }
        }
    </style>
</head>
<body>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        Swal.fire({
            title: "Adakah anda hadir seorang diri?",
            icon: "question",
            showDenyButton: true,
            confirmButtonText: "Ya",
            denyButtonText: "Tidak",
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("register_new_day.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        visitor_id: "' . $visitor_id . '",
                        location_id: "' . $location_id . '"
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = "visitor-card.php";
                    } else {
                        Swal.fire("Error", data.message || "Failed to update location.", "error");
                    }
                });
            } else if (result.isDenied) {
                window.location.href = "check-in-update.php";
            }
        });
    });
</script>
</body>
</html>';

            }
        }

} else {
    $_SESSION['location_id'] = $location_id;
    header("Location: check-in.php?loc=" . rawurlencode($_GET['loc']));
    exit;
}
?>
