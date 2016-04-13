<?php

namespace SecureEnv;

class Encrypt
{
    public static function genkey()
    {
        $chars = implode('', array_merge(range('a', 'z'), range('A', 'Z'), range(0, 9)));
        $len   = strlen($chars);
        $key   = "";
        mt_srand((double)microtime() * 1000000);
        for ($i = 0; $i < 32; $i++) {
            $key .= $chars[mt_rand(0, $len-1)];
        }
        // print "Key: $key\n";
        return $key;
    }

    public static function encrypt($key, $plain)
    {
        // create and randomized initial vector
        mt_srand((double)microtime() * 1000000);
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC), MCRYPT_RAND);

        // encrypt the value
        $value = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $plain, MCRYPT_MODE_CBC, $iv);

        // encode & print
        $encoded = rtrim(base64_encode(serialize([$iv, $value])), "\0\3");
        // echo "ENC:$encoded\n";
        return "ENC:". $encoded;
    }
}
