<?php

$key = "12345678901234567890123456789012"; # 32 bytes for 256 bits
$iv = "123456789012"; # 12 bytes iv

$encrypted = openssl_encrypt("Hello", "AES-256-GCM", $key, OPENSSL_RAW_DATA, $iv, $tag);
$full_encrypted = $iv . $encrypted . $tag;
echo base64_encode($full_encrypted);