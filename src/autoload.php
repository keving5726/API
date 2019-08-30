<?php
spl_autoload_register(function (string $namespace)
{
    $namespace = str_replace("\\", DIRECTORY_SEPARATOR, $namespace);
    $namespace = explode(DIRECTORY_SEPARATOR, $namespace);
    $class = array_pop($namespace);
    $namespace = implode(DIRECTORY_SEPARATOR, $namespace);
    $filePath = ".." . DIRECTORY_SEPARATOR . strtolower($namespace) . DIRECTORY_SEPARATOR . $class . '.php';
    if (file_exists($filePath))
    {
        include_once("$filePath");
    }
});
