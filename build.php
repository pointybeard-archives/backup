<?php

    $srcRoot = __DIR__ . "/src";
    $distRoot = __DIR__ . "/dist";
  
    $phar = new Phar(
        $distRoot . "/backup.phar",
        FilesystemIterator::CURRENT_AS_FILEINFO|FilesystemIterator::KEY_AS_FILENAME,
        "backup.phar"
    );
    $phar->buildFromDirectory($srcRoot);
    $phar->setStub(file_get_contents('stub.php'));