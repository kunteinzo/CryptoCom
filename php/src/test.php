<?php

require "../vendor/autoload.php";

header("Content-Type: application/json");

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$jwt = new JWT();

$d = $jwt->encode(['data' => 'test'], "key", "HS512");
//echo $d;
echo json_encode($jwt->decode($d, new Key("key", "HS512")));