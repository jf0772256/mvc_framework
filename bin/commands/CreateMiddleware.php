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
class CreateMiddleware extends Command {
	/**
	 * Configure. Use methods $this->setDescription to set command descriptive, $this->setHelp to set help text, $this->addArgument to create and capture arguments, $this->getArgument to fetch the value of the specified argument
	 */
	protected function configure() {
		parent::configure();
		$this->setName('create:middleware');
		$this->setDescription("Create a new middleware file to be used to protect or validate the methodology access on some endpoint or controller action");
		$this->setHelp("This is used to cvreate a middleware file that can be filled out to make it work");
		$this->addArgument("MiddlewareName", InputArgument::REQUIRED, "Name of the middleware class");
	}

	/**
	 * Execute command. Use method $this->getArgument to fetch the value of the specified argument
	 *
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 *
	 * @return int integer 0 on success, or an error code
	 */
	protected function execute(InputInterface $input, OutputInterface $output) {
		$output->writeln('Creating new middleware file');
		$class = $input->getArgument('MiddlewareName');
		$path = "core\middleware\\$class.php";
		$data = "<?php" . PHP_EOL . PHP_EOL;
		$data .= "namespace app\core\middleware;" . PHP_EOL . PHP_EOL;
		$data .= "class {$class}Middleware extends BaseMiddleware {" . PHP_EOL;
    	$data .= "\tprotected array \$actions = [];" . PHP_EOL;
		$data .= "\tfunction __construct(array \$actions = []) {" . PHP_EOL;
        $data .= "\t\t\$this->actions = \$actions;" . PHP_EOL;
    	$data .= "\t}" . PHP_EOL . PHP_EOL;
		$data .= "\tfunction execute() {" . PHP_EOL;
		$data .= "\t\t// Enter your execute code that you want the middleware to run when its called on" . PHP_EOL;
    	$data .= "\t}" . PHP_EOL;
		$data .= "}" . PHP_EOL . PHP_EOL;
		$data .= "?>";
		file_put_contents($path, $data);
		$output->writeln("Create middleware file {$class}.php");
		return 0;
	}
}
?>