<?php
session_start();
include_once '../../config/config.php';
include_once '../../controllers/CookieSet.php';

$uuid = generateUUID();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Registration</title>
    <!-- Bulma CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="../../assets/css/main-kiosk.css">
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
                
                <form action="process-check-in-kiosk.php" method="POST">
                    <!-- Nama -->
                    <input type="hidden" name="visitorId" value="<?php echo htmlspecialchars($uuid); ?>" />

                    <div class="field">
                        <label class="label has-text-white">Nama</label>
                        <div class="control has-icons-left">
                            <input class="input is-rounded" type="text" name="nama" placeholder="Masukkan nama" required>
                            <span class="icon is-small is-left">
                                <span class="iconify" data-icon="mdi:user" style="color: #ffffff;"></span>
                            </span>
                        </div>
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
                        <label class="label has-text-white">Lokasi Dituju</label>
                        <div class="control">
                            <div class="select is-fullwidth is-rounded">
                                <select name="location" required>
                                    <option value="" disabled selected>Sila pilih lokasi</option>
                                    <?php
                                    // Fetch locations from the database
                                    $stmt = $pdo->query("SELECT location_id, name FROM locations ORDER BY name ASC");
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        echo '<option value="' . htmlspecialchars($row['location_id']) . '">' . htmlspecialchars($row['name']) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    
                    
                <div class="field-group">
                    <div class="field adult">
                        <label class="label has-text-white">Dewasa</label>
                        <div class="control has-icons-left">
                            <input class="input is-rounded" type="number" name="adult" min="0" max="999" value="1" required>
                        </div>
                    </div>   
                
                    <div class="field child">
                        <label class="label has-text-white">Kanak-Kanak</label>
                        <div class="control has-icons-left">
                            <input class="input is-rounded" type="number" name="children" min="0" max="999" value="0" required>
                        </div>
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
