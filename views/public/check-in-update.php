<?php
session_start();
include_once '../../config/config.php';
include_once '../../controllers/CookieRetrieve.php';

if (isset($_GET['loc']) && !empty($_GET['loc'])) {
    $decodedLocation = base64_decode($_GET['loc'], true);
    if ($decodedLocation === false || !ctype_digit($decodedLocation)) {
        header("Location: invalid-id.php");
        exit;
    }
    $_SESSION['location_id'] = $decodedLocation;
}

if (isset($_SESSION['location_id'])) {
    $location_id = $_SESSION['location_id'];
} else {
    header("Location: no-location.php");
    exit;
}

// Main logic to use the functions
$userInfo = getUserInfo();

if ($userInfo === false || $userInfo['visitor_id'] === null) {
    header("Location: check-in.php");
    exit;
} else {
    $visitorId = $userInfo['visitor_id'];
    $userName = htmlspecialchars(strtoupper($userInfo['userName']));
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Registration</title>
    <!-- Bulma CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="../../assets/css/main-visitor.css">
    <link rel="stylesheet" href="../../assets/css/icon.css">
    <!-- Iconify Icons -->
    <script src="https://code.iconify.design/2/2.1.2/iconify.min.js"></script>
   
</head>
<body>
    <section class="section">
        <div class="container">
            <div class="box">
                <!-- Logo above the title -->
                <img src="../../assets/images/dummy-logo.svg" alt="Logo" class="logo">
                
                <h1 class="title has-text-centered">Pendaftaran Pelawat</h1>
                
                <form action="process-check-in.php" method="POST">
                    <!-- Nama -->
                    <input type="hidden" name="location" value="<?php echo htmlspecialchars($location_id); ?>" />
                    <input type="hidden" name="visitorId" value="<?php echo htmlspecialchars($visitorId); ?>" />
                    <input type="hidden" name="nama" value="<?php echo htmlspecialchars($userName); ?>" />
                    

                    <div class="field">
                        <label class="label has-text-white">
                            <h3 style="text-align: center; font-weight: bold;">SELAMAT KEMBALI</h3>
                            <h4 style="text-align: center; font-weight: bold;"><?php echo htmlspecialchars($userName); ?></h4>
                        </label>
                    </div>

                    <!-- Tujuan -->
                    <div class="field">
                        <label class="label has-text-white">Tujuan</label>
                        <div class="control">
                            <ul class="radio-list">
                                <li>
                                    <label class="radio">
                                        <input type="radio" name="tujuan" value="Pengguna" required>
                                        <img src="../../assets/images/read.png" alt="icon" class="radio-icon">
                                        <span class="radio-label">Pengguna</span>
                                    </label>
                                </li>
                                <li>
                                    <label class="radio">
                                        <input type="radio" name="tujuan" value="Lawatan" required>
                                        <img src="../../assets/images/tourists.png" alt="icon" class="radio-icon">
                                        <span class="radio-label">Lawatan</span>
                                    </label>
                                </li>
                                <li>
                                    <label class="radio">
                                        <input type="radio" name="tujuan" value="Kontraktor" required>
                                        <img src="../../assets/images/builder.png" alt="icon" class="radio-icon">
                                        <span class="radio-label">Kontraktor</span>
                                    </label>
                                </li>
                                <li>
                                    <label class="radio">
                                        <input type="radio" name="tujuan" value="Urusan Rasmi" required>
                                        <img src="../../assets/images/businessman.png" alt="icon" class="radio-icon">
                                        <span class="radio-label">Urusan Rasmi</span>
                                    </label>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    
                    <div class="field">
                        <label class="label has-text-white">Dewasa</label>
                        <div class="control has-icons-left">
                            <input class="input is-rounded" type="number" name="adult" min="0" max="999" value="1" required>
                        </div>
                    </div>   
                    
                    <div class="field">
                        <label class="label has-text-white">Kanak-Kanak</label>
                        <div class="control has-icons-left">
                             <input class="input is-rounded" type="number" name="children" min="0" max="999" value="0" required>
                        </div>
                    </div>   
                    

                    <!-- Submit Button -->
                    <div class="field">
                        <div class="control">
                            <button type="submit" class="button is-fullwidth is-rounded">Daftar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</body>
</html>
