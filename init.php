<?php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';

if (getenv('AMBIENTE') == 'DEV') {
    $encripta = new SecureEnv\EncriptaEnvFile();

    $chaves = $encripta->readEnvFile();
    $encripta->writeSecEnvFile($chaves);

    $dotenv = new Dotenv\Dotenv(__DIR__, '.secenv');
    $dotenv->load();

    $key = $encripta->getKey();
    $secureEnv = new SecureEnv\SecureEnv($key);
} else { // Prod
    $key = "GGlmzcfkVBw3IEcVGVrlZLBPjd7Ak2Xz";

    $secureEnv = new SecureEnv\SecureEnv($key);
}

// print_r($secureEnv);
// print_r($_SERVER);

echo $secureEnv->getSecEnv('DB_HOST') . '<br>';
echo $secureEnv->getSecEnv('DB_USER') . '<br>';
echo $secureEnv->getSecEnv('DB_PASS') . '<br>';
echo $secureEnv->getSecEnv('DB_NAME') . '<br>';
