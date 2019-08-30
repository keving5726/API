<?php
require dirname(__DIR__).'/src/autoload.php';

$env = dirname(__DIR__).'/.env';

if(!file_exists($env))
{
    echo "The file .env does not exist";
    return;
}

$_ENV = parse_ini_file($env, true);

$_SERVER += $_ENV;
