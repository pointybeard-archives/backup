<?php
namespace Backup\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Backup\Lib\Traits\hasEnvironmentRequirementsTrait;

class PruneCommand extends Command
{
    use hasEnvironmentRequirementsTrait;
    
    protected function configure()
    {
        $this
            ->setName('prune')
            ->setDescription('Deletes backups. Must specify either `days` or `total`.')
            ->addArgument(
                'folder',
                InputArgument::REQUIRED,
                'The location backups are pruned from.'
            )
            ->addOption(
               'days',
               ['d'],
               InputOption::VALUE_OPTIONAL,
               'This is the number of days to keep in the backup.'
            )
           ->addOption(
              'total',
              ['t'],
              InputOption::VALUE_OPTIONAL,
              'used by `prune`. This is the total number of backups that should be kept for any given location'
           )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("here we are");
    }
}


/*


    $now = date('Y-m-d');

    $seven_days_ago = trim(shell_exec("date --date='10 days ago' '+%Y-%m-%d'"));

    exec(sprintf('rm -rf %s/%s', $backups, $seven_days_ago));
