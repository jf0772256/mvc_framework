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
class CreateViewLayout extends Command {
	/**
	 * Configure. Use methods $this->setDescription to set command descriptive, $this->setHelp to set help text, $this->addArgument to create and capture arguments, $this->getArgument to fetch the value of the specified argument
	 */
	protected function configure() {
		parent::configure();
		$this->setName('create:view-layout');
		$this->setDescription("Creates a new view layout for content to be displayed in");
		$this->addArgument("LayoutName", InputArgument::REQUIRED, "Layout name, which the file will be named");
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
		$output->writeln('Creating view...');
		$view = $input->getArgument('LayoutName');
		$path = "views\layouts\\$view.php";
		$data = "<?php" . PHP_EOL . PHP_EOL;
		$data .= "use app\core\Application;" . PHP_EOL;
		$data .= "/**" . PHP_EOL;
		$data .= " *@var \$this \app\core\View" . PHP_EOL;
		$data .= " */" . PHP_EOL . PHP_EOL;
		$data .= "?>" . PHP_EOL;
		$data .= "<!doctype html>" . PHP_EOL;
		$data .= "<html lang=\"en\">" . PHP_EOL;
		$data .= "\t<head>" . PHP_EOL;
		$data .= "\t\t<meta charset=\"utf-8\">" . PHP_EOL;
		$data .= "\t\t<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">" . PHP_EOL;
		$data .= "\t\t<title><?=\$this->title ?></title>" . PHP_EOL;
		$data .= "\t</head>" . PHP_EOL;
		$data .= "\t<body>" . PHP_EOL;
		$data .= "\t\t{{content}}" . PHP_EOL;
		$data .= "\t</body>" . PHP_EOL;
		$data .= "</html>" . PHP_EOL;

		file_put_contents($path,$data);
		
		$output->writeln('Created view layout');
		return 0;
	}
}
?>