<?php

$key = "12345678901234567890123456789012"; # 32 bytes for 256 bits
$iv = "123456789012"; # 12 bytes iv

$encrypted = openssl_encrypt("Hello", "AES-256-GCM", $key, OPENSSL_RAW_DATA, $iv, $tag);
$full_encrypted = $iv . $encrypted . $tag;

echo 'Encrypted: ' . base64_encode($full_encrypted).PHP_EOL;

$droot = substr($full_encrypted, 12, -16);
$div = substr($full_encrypted, 0, 12);
$dtag = substr($full_encrypted, -16);

echo 'Decrypted: '.openssl_decrypt($droot, "AES-256-GCM", $key, OPENSSL_RAW_DATA, $div, $dtag).PHP_EOL;
