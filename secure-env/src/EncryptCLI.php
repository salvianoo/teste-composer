<?php

if ($argc < 2) fail();
switch ($argv[1]) {
    case "genkey":
        printKey();
        break;
    case "enc":
    case "encrypt":
        if ($argc != 4) fail();
        encrypt($argv[2], $argv[3]);
        break;
    default:
        fail();
}

function fail() {
    die("Usage:\n\n# Generate a new key\nphp enc.php genkey\n\n# Encrypt a value\nphp enc.php enc \"key\" \"value\"\n");
}

function printKey() {
    $chars = implode('', array_merge(range('a', 'z'), range('A', 'Z'), range(0, 9)));
    $len   = strlen($chars);
    $key   = "";
    mt_srand((double)microtime() * 1000000);
    for ($i = 0; $i < 32; $i++) {
        $key .= $chars[mt_rand(0, $len-1)];
    }
    print "Key: $key\n";
}

function encrypt($key, $plain) {
    // create and randomized initial vector
    mt_srand((double)microtime() * 1000000);
    $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC), MCRYPT_RAND);

    // encrypt the value
    $value = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $plain, MCRYPT_MODE_CBC, $iv);

    // encode & print
    $encoded = rtrim(base64_encode(serialize([$iv, $value])), "\0\3");
    echo "ENC:$encoded\n";
}
