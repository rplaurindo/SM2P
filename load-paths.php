<?php

$rootPath = realpath('.');

$libPath = $rootPath . DIRECTORY_SEPARATOR . 'lib';
set_include_path(get_include_path() . PATH_SEPARATOR . $libPath);

$srcPath = $rootPath . DIRECTORY_SEPARATOR . 'src';
set_include_path(get_include_path() . PATH_SEPARATOR . $srcPath);
