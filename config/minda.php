<?php

function simpleEncode($token, $key = '9f2c7a8d4b3e6f1a9d0c2e7b5f8a4d3c6b5e9f0a1c2d3e4f56789abcd1234567') {
    $encoded = '';
    $keyLength = strlen($key);
    for ($i = 0; $i < strlen($token); $i++) {
        $encoded .= chr(ord($token[$i]) ^ ord($key[$i % $keyLength]));
    }
    return base64_encode($encoded);
}

function simpleDecode($encodedToken, $key = '9f2c7a8d4b3e6f1a9d0c2e7b5f8a4d3c6b5e9f0a1c2d3e4f56789abcd1234567') {
    $decoded = base64_decode($encodedToken, true);
    if ($decoded === false) {
        return false; // Invalid base64 input
    }
    $token = '';
    $keyLength = strlen($key);
    for ($i = 0; $i < strlen($decoded); $i++) {
        $token .= chr(ord($decoded[$i]) ^ ord($key[$i % $keyLength]));
    }
    return $token;
}

?>