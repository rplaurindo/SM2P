<?php

spl_autoload_register(function ($className) {
    global $globalClassName;
    global $namespacedPath;

    $globalClassName = $className;
    $paths = explode(PATH_SEPARATOR, get_include_path());
    $namespacedPath =  str_replace('\\', DIRECTORY_SEPARATOR, $globalClassName)  . '.php';

    array_walk($paths, function ($path) {
        global $namespacedPath;
        global $globalClassName;
        $absolutePath = $path . DIRECTORY_SEPARATOR . $namespacedPath;

        if (file_exists($absolutePath)) {
            include $namespacedPath;
        } else {
            $fileName = basename($globalClassName)  . '.php';
            $absolutePath = $path . DIRECTORY_SEPARATOR . $fileName;

            if (file_exists($absolutePath)) {
                include $fileName;
            }
        }

    });

});
