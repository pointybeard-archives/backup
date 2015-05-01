#!/usr/bin/env php
<?php
Phar::mapPhar();
require_once 'phar://backup.phar/vendor/autoload.php';
use Backup\Lib\Console as BackupConsole;
$console = new BackupConsole();
$console->run();
__HALT_COMPILER();