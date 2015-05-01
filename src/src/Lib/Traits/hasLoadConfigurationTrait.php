<?php
namespace Backup\Lib\Traits;
use Backup\Lib\Exceptions;

trait hasLoadConfigurationTrait{
    private $config;
    public function loadConfiguration($path)
    {
        $this->config = json_decode(file_get_contents($path));
        return true;
    }
}