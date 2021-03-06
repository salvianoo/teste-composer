<?php

namespace SecureEnv;

class EncriptaEnvFile
{

    private $key;

    public function __construct()
    {
        $this->key = Encrypt::genkey();
    }

    public function readEnvFile($file = '.env')
    {
        $filepath = dirname(dirname(__DIR__)) . '/'. $file;

        $file_content = file($filepath);

        $secEnv = [];

        foreach ($file_content as $envar) {
            $arr_name  = preg_split("/(=.+)/", $envar);
            $arr_value = preg_split("/^(\w)+=?/", $envar);

            $secEnv[$arr_name[0]] = $this->encryptEnv($this->key, $arr_value[1]);
        }
        return $secEnv;
    }

    public function writeSecEnvFile($contents)
    {
        $file = fopen('.secenv', 'w');

        foreach ($contents as $env_name => $env_value) {
            $envar = $env_name .'='. $env_value . "\n";
            fwrite($file, $envar);
        }

        fclose($file);
    }

    public function getKey() {
        return $this->key;
    }

    public function encryptEnv($key, $plain) {
        return Encrypt::encrypt($key, $plain);
    }
    // 2. encrypta cada variavel
    // 3. salva no arquivo .secenv
}