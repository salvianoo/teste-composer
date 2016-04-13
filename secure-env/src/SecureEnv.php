<?php

namespace SecureEnv;

class SecureEnv
{
    private $secEnv;

    public function __construct($key, $secEnv = [])
    {
        $this->secEnv = $secEnv;
        $this->bootstrapSecEnv($key);
    }

    public function getSecEnv($name, $fallback = '')
    {
        if (isset($this->secEnv[$name])) {
            return trim($this->secEnv[$name]);
        } elseif ($_SERVER[$name] !== false) { // usar
            return trim($_SERVER[$name]);
        } else {
            return $fallback;
        }
    }

    private function bootstrapSecEnv($key)
    {
        foreach ($_SERVER as $name => $value) {
            if (is_string($value) && strpos($value, 'ENC:') === 0) {
                $this->secEnv[$name] = $this->decryptEnv($key, substr($value, 4));
            }
        }
    }

    private function decryptEnv($key, $encoded)
    {
        list($iv, $value) = unserialize(base64_decode($encoded));
        return mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $value, MCRYPT_MODE_CBC, $iv);
    }
}
