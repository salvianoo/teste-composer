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
        } elseif (getenv($name) !== false) { // usar
            return trim(getenv($name));
        } else {
            return $fallback;
        }
    }

    private function bootstrapSecEnv($key)
    {
        foreach ($this->envArray() as $name => $value) {
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

    private function envArray()
    {
        $vars_env = explode("\n", shell_exec('printenv'));

        // print_r($vars_env);


        return array_reduce($vars_env, function ($acc, $value) {
            $var_env = (strstr($value, '=') ? explode("=", $value) : array($value, ''));  // FIX THIS =

            // print_r($var_env);

            return ( array_merge($acc, array($var_env[0] => $var_env[1])) );
        }, []);
    }
}