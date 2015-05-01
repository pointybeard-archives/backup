<?php
namespace Backup\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Backup\Lib\Traits;

class RunCommand extends Command
{
    use Traits\hasEnvironmentRequirementsTrait;
    use Traits\hasLoadConfigurationTrait;
    
    protected function configure()
    {
        $this
            ->setName('run')
            ->setDescription('Creates a tar backup of files in a specified path and/or the records in a database.  is the location backups are saved/pruned from. By default, the backup will never prune unless the `prune` command is used.')
            ->addArgument(
                'folder',
                InputArgument::REQUIRED,
                'The location backups are saved from.'
            )
            ->addOption(
               'config',
               null,
               InputOption::VALUE_REQUIRED,
               'JSON file containing the paths and databases (including connection details) for each backup location.'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->checkEnvironment(['gzip', 'zip', 'mysqldump']);
        $this->loadConfiguration($input->getOption('config'));

        $t = microtime(true);

        $now = date('Y-m-d_His');
        $cwd = sprintf('%s/%s.%d', $input->getArgument('folder'), $now, floor(($t - floor($t)) * 10000));
        $output->writeln("Starting Backup `{$cwd}`");
        
        if(!is_dir($input->getArgument('folder')) && !is_writable($input->getArgument('folder'))){
            throw new \Exception(sprintf(
                "Destination folder `%s` does not exsit, or is not writable.", $input->getArgument('folder')
            ));
        }

        mkdir($cwd, 0755, true);

        foreach($this->config->locations as $name => $contents){

            $output->writeln("Creating folder structure for `{$name}`");

            if(isset($contents->files)){
                $output->write("Archiving files in `{$contents->files}` ...", false);
                
                exec(sprintf(
                    '%s -r9 %s/%s.%s.zip %s',
                    $this->which('zip'),
                    $cwd,
                    $name,
                    date('His'),
                    $contents->files
                ));
                
                $output->write(" <info>OK</info>", true);
            }

            if(isset($contents->database) && isset($contents->database->db)){
                foreach($contents->database->db as $db){
                    $output->write("Dumping database `{$db}` ...", false);
                    
                    exec(sprintf(
                        '%s -u %s --password="%s" -h %s %s | %s -9 > %s',
                        $this->which('mysqldump'),
                        $contents->database->user,
                        $contents->database->pass,
                        $contents->database->host,
                        $db,
                        $this->which('gzip'),
                        sprintf('%s/%s.%s.sql.gz', $cwd, $db, date('His'))
                    ));
                    
                    $output->write(" <info>OK</info>", true);
                    
                }
            }
        }
    }
}