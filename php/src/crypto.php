<?php declare(strict_types= 1);

class RSA
{
    private string $privateKey = '';
    private string $publicKey = '';
    private mysqli $connection;

    function __construct(bool $new = false)
    {
        $this->connection = mysqli_connect("localhost", "test", "asdfgh", "test");
        mysqli_query($this->connection, "create table if not exists CryptoKey (
            key_id varchar(20) not null primary key,
            key_text varchar(2000) not null
        );");
        $this->generateKeys($new);
    }

    private function generateKeys(bool $new = false): void
    {
        if ($new) {
            $key = openssl_pkey_new([
                "private_key_bits" => 2048,
                "private_key_type" => OPENSSL_KEYTYPE_RSA
            ]);
            openssl_pkey_export($key, $this->privateKey);
            $this->publicKey = openssl_pkey_get_details($key)['key'];
            $this->saveKey("RSA", $this->privateKey);
        } else {
            $key = $this->getKey("RSA");
            if ($key) {
                $this->privateKey = $key;
                $this->publicKey = openssl_pkey_get_details(openssl_pkey_get_private($key))['key'];
                return;
            }
            $this->generateKeys(true);
        }
    }

    private function saveKey(string $key_id, string $key_text): void
    {
        if ($this->getKey($key_id)) {
            mysqli_query($this->connection, "update CryptoKey set key_text = '$key_text' where key_id = '$key_id'");
        } else {
            mysqli_query($this->connection, "insert into CryptoKey (key_id, key_text) values ('$key_id', '$key_text') on duplicate key update key_text = '$key_text'");
        }
    }

    private function getKey(string $key_id): string|false
    {
        try {
            return mysqli_query($this->connection, "select key_text from Keys where key_id = '$key_id'")->fetch_row()[0];
        } catch (Exception) {
            return false;
        }
    }

    public function encrypt(string $data)
    {
        return openssl_public_encrypt($data, $encrypted, $this->publicKey) ? base64_encode($encrypted) : false;
    }

    public function decrypt(string $data)
    {
        return openssl_private_decrypt(base64_decode($data), $decrypted, $this->privateKey) ? $decrypted : false;
    }

}

class AES
{

    static function generateAESKey(int $size = 32) 
    {
        return base64_encode(openssl_random_pseudo_bytes(32));
    }
    static function encrypt($data, $key)
    {
        $iv = openssl_random_pseudo_bytes(12);
        $encrypted = openssl_encrypt($data, "AES-256-GCM", base64_decode($key), iv: $iv, tag:$tag);
        return base64_encode($iv.$encrypted. $tag);
    }

    static function decrypt($data, $key)
    {
        $d = base64_decode($data);
        $iv = substr($d, 0, 12);
        $mdata = substr($d, 12, -16);
        $tag = substr($d, -16);
        return openssl_decrypt($mdata, "AES-256-GCM", base64_decode($key), iv:$iv, tag: $tag);
    }
}

