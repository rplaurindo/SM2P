<?php

$rootPath = realpath(__DIR__);

$libPath = $rootPath . DIRECTORY_SEPARATOR . 'lib';
$srcPath = $rootPath . DIRECTORY_SEPARATOR . 'src';

set_include_path(get_include_path() . PATH_SEPARATOR . $libPath);
set_include_path(get_include_path() . PATH_SEPARATOR . $srcPath);

spl_autoload_register(function ($className) {
    include str_replace('\\', DIRECTORY_SEPARATOR, $className)  . '.php';
});
