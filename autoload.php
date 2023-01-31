<?php
include_once('./vendor/autoload.php');

define('ENV_DIR', implode(DIRECTORY_SEPARATOR, [__DIR__, 'tests']));
return include_once('./bootstrap.php');
