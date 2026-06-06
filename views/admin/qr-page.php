<?php
include_once '../../config/config.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $encodedId = $_GET['id'];  // just get the encoded id, no decoding
} else {
    $encodedId = null;
}

$registrationUrl = $base_url . '/views/public/index.php?loc=' . rawurlencode($encodedId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code and URL Display</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            max-width: 90%;
            width: 400px;
        }

        .qr-code {
            width: 100%;
            max-width: 300px;
            margin: 0 auto 20px;
        }

        .url-box {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
            font-size: 16px;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .info-text {
            margin-top: 10px;
            color: #555;
        }

        button {
            padding: 8px 16px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Location QR Code</h2>
<div class="url-box" id="urlBox">
    <?= htmlspecialchars($registrationUrl) ?>
</div>

        <button onclick="copyURL()">Copy URL</button>
       <div class="info-text">
  Copy the url and generate QR via <br>
  <a href="https://www.qrcode-monkey.com/" target="_blank" rel="noopener noreferrer">
    https://www.qrcode-monkey.com/
  </a>
</div>

    </div>

    <script>
        function copyURL() {
            const urlBox = document.getElementById("urlBox");
            const text = urlBox.innerText;
            navigator.clipboard.writeText(text).then(() => {
                alert("URL copied to clipboard!");
            }).catch(() => {
                alert("Failed to copy URL.");
            });
        }
    </script>
</body>
</html>
