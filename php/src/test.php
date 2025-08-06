<?php


function test_aes(){
    $key = "12345678901234567890123456789012"; # 32 bytes for 256 bits
    $iv = "123456789012"; # 12 bytes iv

    $encrypted = openssl_encrypt("Hello", "AES-256-GCM", $key, OPENSSL_RAW_DATA, $iv, $tag);
    $full_encrypted = $iv . $encrypted . $tag;

    echo 'Encrypted: ' . base64_encode($full_encrypted).PHP_EOL;

    $droot = substr($full_encrypted, 12, -16);
    $div = substr($full_encrypted, 0, 12);
    $dtag = substr($full_encrypted, -16);

    echo 'Decrypted: '.openssl_decrypt($droot, "AES-256-GCM", $key, OPENSSL_RAW_DATA, $div, $dtag).PHP_EOL;
}

function test_rsa(){
    $text = "Hello";
    $raw_key = openssl_pkey_new(["private_key_bits"=>2048, "private_key_type"=>OPENSSL_KEYTYPE_RSA]);
    openssl_pkey_export($raw_key, $privateKey);
    $publicKey = openssl_pkey_get_details($raw_key)['key'];

    openssl_public_encrypt($text,$encrypted, $publicKey, OPENSSL_PKCS1_OAEP_PADDING);

    echo 'Encrypted: '.base64_encode($encrypted). PHP_EOL;

    openssl_private_decrypt($encrypted, $decrypted, $privateKey, OPENSSL_PKCS1_OAEP_PADDING);

    echo 'Decrypted: '. $decrypted;
}

function test_hmac(){
    echo base64_encode(
        hash_hmac('sha512', 'Hello', "secret", true)
    );
}


test_aes();
test_rsa();
test_hmac();
