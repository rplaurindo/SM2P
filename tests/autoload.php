<?php

spl_autoload_register(function ($className) {
    include str_replace('\\', DIRECTORY_SEPARATOR, $className)  . '.php';
});
