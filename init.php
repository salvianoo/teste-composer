<?php
require __DIR__ . '/vendor/autoload.php';

if (getenv('AMBIENTE') == 'DEV') {
    $encripta = new SecureEnv\EncriptaEnvFile();

    $chaves = $encripta->readEnvFile();
    $encripta->writeSecEnvFile($chaves);

    $dotenv = new Dotenv\Dotenv(__DIR__, '.secenv');
    $dotenv->load();

    $key = $encripta->getKey();
    $secureEnv = new SecureEnv\SecureEnv($key);
} else {
    $key = "GGlmzcfkVBw3IEcVGVrlZLBPjd7Ak2Xz";

    $secureEnv = new SecureEnv\SecureEnv($key);
}

// print_r($_SERVER);

echo $secureEnv->getEnv('DB_HOST') . '<br>';
echo $secureEnv->getEnv('DB_USER') . '<br>';
echo $secureEnv->getEnv('DB_PASS') . '<br>';
echo $secureEnv->getEnv('DB_NAME') . '<br>';
