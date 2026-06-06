<?php
include_once '../../config/config.php';


if (isset($_GET['id'])) {
    $cardToken = $_GET['id'];
    $qrUrl = $base_url . "/views/public/retrieve-card.php?id=" . rawurlencode($cardToken);
    $encodedUrl = urlencode($qrUrl);
} else {
    echo "User ID not provided.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berjaya</title>
    <!-- Bulma CSS -->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
        <link rel="stylesheet" href="../../assets/css/main-kiosk.css">
    <link rel="stylesheet" href="../../assets/css/icon.css">
    <!-- Iconify Icons -->
    <script src="https://code.iconify.design/2/2.1.2/iconify.min.js"></script>
    <style>
        .countdown {
            font-size: 1.5rem;
            color: #ffffff;
            margin-top: 10px;
        }
        .button {
            margin-top: 1rem;
            background-color: #fff944;
            border: none;
            border-radius: 10px;
        }
        .button:hover {
            background-color: #2980b9;
        }
        .qr-code {
            margin: 1rem auto;
            width: 200px;
            height: 200px;
        }
        
        /* Custom Heading Styles */
.box {
    width: 100%; /* Ensure full width within its container */
    max-width: 600px; /* Adjust to your desired width */
    margin: auto; /* Center the box */
    padding: 1.5rem;
    background-color: #1e2a47; /* Darker blue for the box */
    border-radius: 12px;
}

/* Custom Heading Styles */
h2.title {
    margin-bottom: 0.5rem !important; /* Reduce bottom margin */
    font-size: 1.75rem; /* Slightly larger font size */
    font-weight: 700; /* Bold for prominence */
    line-height: 1.2; /* Tighter line spacing */
    color: #fff; /* Keep text white */
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5); /* Subtle shadow for readability */
    white-space: nowrap; /* Prevent line break */
    overflow-wrap: normal; /* Prevent breaking words */
}

h2.title.has-text-centered {
    text-align: center; /* Center the text */
    margin-top: 0.5rem; /* Add a little spacing from the top */
}

/* Decorative underline effect */
h2.title::after {
    content: "";
    display: block;
    width: 50px; /* Length of underline */
    height: 4px; /* Thickness of underline */
    background-color: #fff944; /* Light yellow underline */
    margin: 0.2rem auto 0; /* Position and spacing */
    border-radius: 4px; /* Smooth rounded edges */
}

    </style>
    <script>
        let countdown = 30;
        function startCountdown() {
            const countdownElement = document.getElementById('countdown');
            const interval = setInterval(() => {
                countdown--;
                countdownElement.textContent = countdown;
                if (countdown <= 0) {
                    clearInterval(interval);
                    window.location.href = 'kiosk.php';
                }
            }, 1000);
        }
        window.onload = startCountdown;
    </script>
</head>
<body>
    <section class="section">
        <div class="container">
            <div class="box">
                <!-- Logo -->
                <img src="../../assets/images/dummy-logo.svg" alt="Logo" class="logo">

                <!-- Title -->
                <h1 class="title has-text-centered">Pendaftaran Berjaya</h1>
                <h2 class="title has-text-centered">Selamat Datang</h2>
                <h2 class="title has-text-centered">Visitor Center</h2>


                <!-- QR Code and Countdown -->
                <div class="content has-text-centered">
                    <p class="has-text-white">Sila imbas untuk muat turun kad digital</p>

<img class="qr-code" src="https://qrtag.net/api/qr_200.png?url=<?php echo $encodedUrl; ?>" alt="QR Code">


                    <div class="countdown">Imbas sebelum: <span id="countdown">30</span> saat</div>
                    
                    <!-- Pendaftaran Baru Button -->
                    <div class="field">
                        <div class="control">
                            <button class="button is-fullwidth is-rounded" onclick="window.location.href='kiosk.php'">Pendaftaran Baru</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
