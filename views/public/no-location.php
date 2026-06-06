<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warning</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }

        .warning-container {
            text-align: center;
            background-color: #ffdddd;
            color: #a94442;
            border: 1px solid #a94442;
            border-radius: 8px;
            padding: 20px;
            max-width: 90%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .warning-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .warning-message {
            font-size: 1rem;
        }

        @media (max-width: 600px) {
            .warning-title {
                font-size: 1.2rem;
            }
            .warning-message {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="warning-container">
        <div class="warning-title">Warning</div>
        <div class="warning-message">No location is set. Please scan a valid location QR code.</div>
    </div>
</body>
</html>
