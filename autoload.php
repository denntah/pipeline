<?php
spl_autoload_register(function ($class) {
    $file = __DIR__ . DIRECTORY_SEPARATOR . $class . '.php';

    if ($found = file_exists($file) === true)
        require $file;
    
    return $found;
});