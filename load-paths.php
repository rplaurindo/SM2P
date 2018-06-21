<?php

$rootPath = realpath('.');

$composeSubFolder = function ($path, $folder) {
    return $path . DIRECTORY_SEPARATOR . $folder;
};

$libPath = $composeSubFolder($rootPath, 'lib');
set_include_path(get_include_path() . PATH_SEPARATOR . $libPath);

// to doesn't need to include a subpath, a interactive method should be implemented to read the subfolders, iterate over them (save parent folders with DIRECTORY_SEPARATOR)
$libSMTPPath = $composeSubFolder($libPath, 'SMTP');
set_include_path(get_include_path() . PATH_SEPARATOR . $libSMTPPath);

$srcPath = $composeSubFolder($rootPath, 'src');
set_include_path(get_include_path() . PATH_SEPARATOR . $srcPath);
