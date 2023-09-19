<?php
namespace app\bin\commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command.
 */
class MigrateRun extends Command
{
    /**
     * Configure.
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('migrate:run');
        $this->setDescription('Run Migrations File');
        $this->setHelp("Migrate runs all the migrations with to using the specified database parameter dbType, options are mysqli, pdo, and sqlite. Configure database connection information in the .env file.");
        $this->addArgument('dbType', InputArgument::REQUIRED, 'The database interface to use.');
    }

    /**
     * Execute command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int integer 0 on success, or an error code
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('');
        $output->writeln('dbType: '.$input->getArgument('dbType'));
        $output->writeln(shell_exec("php migrations.php --dbType={$input->getArgument('dbType')}"));
        return 0;
    }
}

?>