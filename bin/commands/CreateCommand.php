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
class CreateCommand extends Command
{
    /**
     * Configure.
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('create:command');
        $this->setDescription('Create a new command and add to the list');
        $this->setHelp("Creates a new command file that you name it, then you can go in and configure it the way you want it...");
        $this->addArgument('ClassName', InputArgument::REQUIRED, "The class name that you want the class to be called, will be used for the file name as well as to make sure that the command is properly loaded.");
        $this->addArgument('CommandName', InputArgument::REQUIRED, 'Command name you want to use for the CLI.');
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
        $output->writeln('Creating a new command...');
        $command = $input->getArgument('CommandName');
        $class = $input->getArgument('ClassName');
        $existing = file_get_contents("bin/commands_list.php");

        $data = "<?php" . PHP_EOL;
        $data .= "namespace app\bin\commands;" . PHP_EOL . PHP_EOL;
        $data .= "use Symfony\Component\Console\Command\Command;" . PHP_EOL;
        $data .= "use Symfony\Component\Console\Input\InputInterface;" . PHP_EOL;
        $data .= "use Symfony\Component\Console\Input\InputOption;" . PHP_EOL;
        $data .= "use Symfony\Component\Console\Input\InputArgument;" . PHP_EOL;
        $data .= "use Symfony\Component\Console\Output\OutputInterface;" . PHP_EOL . PHP_EOL;
        $data .= "/**" . PHP_EOL;
        $data .= " * Command." . PHP_EOL;
        $data .= " */" . PHP_EOL;
        $data .= "class {$class} extends Command {" . PHP_EOL;
        $data .= "\t/**" . PHP_EOL;
        $data .= "\t * Configure. Use methods \$this->setDescription to set command descriptive, \$this->setHelp to set help text, \$this->addArgument to create and capture arguments, \$this->getArgument to fetch the value of the specified argument" . PHP_EOL;
        $data .= "\t */" . PHP_EOL;
        $data .= "\tprotected function configure() {" . PHP_EOL;
        $data .= "\t\tparent::configure();" . PHP_EOL;
        $data .= "\t\t\$this->setName('{$command}');" . PHP_EOL;
        $data .= "\t}" . PHP_EOL . PHP_EOL;
        $data .= "\t/**" . PHP_EOL;
        $data .= "\t * Execute command. Use method \$this->getArgument to fetch the value of the specified argument" . PHP_EOL;
        $data .= "\t *" . PHP_EOL;
        $data .= "\t * @param InputInterface \$input" . PHP_EOL;
        $data .= "\t * @param OutputInterface \$output" . PHP_EOL;
        $data .= "\t *" . PHP_EOL;
        $data .= "\t * @return int integer 0 on success, or an error code" . PHP_EOL;
        $data .= "\t */" . PHP_EOL;
        $data .= "\tprotected function execute(InputInterface \$input, OutputInterface \$output) {" . PHP_EOL;
        $data .= "\t\t\$output->writeln('Put text here');" . PHP_EOL;
        $data .= "\t\treturn 0;" . PHP_EOL;
        $data .= "\t}" . PHP_EOL;
        $data .= "}" . PHP_EOL;
        $data .= "?>";

        // create a new file....
        file_put_contents("bin/commands/{$class}.php", $data);

        // create the reference in the commands_list file...
        $instring = "\t\app\bin\commands\\$class::class," . PHP_EOL . "];";
        $existing = str_replace('];', $instring, $existing);
        file_put_contents('bin/commands_list.php', $existing);

        $output->writeln("Created new command file: {$class}.php in \bin\commands\ please complete the class data in that file.");

        return 0;
    }
}

?>