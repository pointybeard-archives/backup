<?php
namespace Backup\Lib;

// import the Symfony Console Application
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;

use Backup\Lib\Exceptions;


/**
 * Adds dynamic locading of commands to the Symfony
 * console Application class
 */
Final class Console extends Application
{
    /**
     * This overloads the base Application function. Normally commands are
     * added to the Application manually (i.e. not autoloaded or added dynamically).
     *
     * Instead, this will examine the command name passed, e.g.
     * "prune", and dynamically create the command class
     * required. It is then added to the Application. The return value is a string.
     *
     * Effectively this is a "Single Command" Application, however the command is located
     * dynamically.
     *
     * This function is never called directly, rather it is run when the Application
     * run() function is called.
     *
     * @param InputInterface $input
     * @return string
     */
    protected function getCommandName(InputInterface $input)
    {
        // Make sure it's possible to call the default commands. Iterate over each one
        // and check if the names match
        foreach(parent::getDefaultCommands() as $c){
            if(strcasecmp($c->getName(), $input->getFirstArgument()) == 0){
                return $input->getFirstArgument();
            }
        }

        // Generate the class name, including correct namepsace.
        $class = sprintf('Backup\Commands\%sCommand', ucfirst($input->getFirstArgument()));
        
        // Sanity check - Make sure the generated class path is a real class
        if(!class_exists($class)){
            throw new Exceptions\InvalidConsoleCommandException(sprintf(
                '"%s" is not a valid command: Unable to locate command class %s',
                $input->getFirstArgument(),
                $class
            ));
        }

        // Add the command to the Application
        $this->add(new $class());

        // Echo back the first argument, since this is still the command name
        return $input->getFirstArgument();
    }
}
