<?php
namespace Backup\Lib\Traits;
use Backup\Lib\Exceptions;

trait hasEnvironmentRequirementsTrait{
    
    private $env = array();
    
    protected function checkEnvironment(array $programs){
        foreach($programs as $b){
            $path = $this->which($b);
            if(strlen(trim($path)) == 0){
                throw new Exceptions\MissingEnvironmentDependencyException(sprintf(
                    'Unable to locate required environment dependency `%s`', $b
                ));
            }
        }
        return true;
    }
    
    protected function which($program){
        if(!in_array($program, $this->env)){
            $this->env[$program] = trim(shell_exec(sprintf('which %s', $program)));
        }
        return $this->env[$program];
    }
}