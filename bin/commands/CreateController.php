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
class CreateController extends Command {
	/**
	 * Configure. Use methods $this->setDescription to set command descriptive, $this->setHelp to set help text, $this->addArgument to create and capture arguments, $this->getArgument to fetch the value of the specified argument
	 */
	protected function configure() {
		parent::configure();
		$this->setName('create:controller');
		$this->setDescription("Creates a new controller");
		$this->setHelp("Creates an empty controller to configure as you need.");
		$this->addArgument("ClassName", InputArgument::REQUIRED, "The name of the controller");
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
		$output->writeln('Creating controller');
		$class = $input->getArgument('ClassName');
		$path = "controllers/{$class}.php";
		$data = "<?php" . PHP_EOL;
		$data .= "namespace app\controllers;" . PHP_EOL;
		$data .= "use app\core\Controller;" . PHP_EOL . PHP_EOL;
		$data .= "class {$class} extends Controller {" . PHP_EOL;
		$data .= "\tfunction __construct() {}" . PHP_EOL;
		$data .= "}" . PHP_EOL;
		$data .= "?>";
		file_put_contents($path, $data);
		$output->writeln("File Creation Completed: controllers\\$class.php");
		return 0;
	}
}
?>