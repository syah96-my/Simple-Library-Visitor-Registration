<?php

function generateUUID() {
    // Generate a UUID version 4 (random)
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),       // 32 bits for "time_low"
        mt_rand(0, 0xffff),                            // 16 bits for "time_mid"
        mt_rand(0, 0x0fff) | 0x4000,                   // 16 bits for "time_hi_and_version", top 4 bits are 0100
        mt_rand(0, 0x3f) | 0x80,                       // 8 bits for "clk_seq_hi_res", 8 bits for "clk_seq_low"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff) // 48 bits for "node"
    );
}


function setUUIDCookie($cookieName = 'visitor_id', $expireInDays = 365) {
    // Generate UUID for the cookie
    $uuid = generateUUID(); // Use the generateUUID function you created earlier

    setcookie($cookieName, $uuid, [
        'expires' => time() + (86400 * $expireInDays),
        'path' => '/',
        'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    return $uuid;
}
?>
