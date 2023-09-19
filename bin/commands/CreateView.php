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
class CreateView extends Command {
	/**
	 * Configure. Use methods $this->setDescription to set command descriptive, $this->setHelp to set help text, $this->addArgument to create and capture arguments, $this->getArgument to fetch the value of the specified argument
	 */
	protected function configure() {
		parent::configure();
		$this->setName('create:view');
		$this->setDescription("Creates a new view to be displayed in a layout");
		$this->addArgument("ViewName", InputArgument::REQUIRED, "view name, which the file will be named");
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
		$view = $input->getArgument('ViewName');
		$path = "views\\$view.php";
		$data = "<?php" . PHP_EOL . PHP_EOL;
		$data .= "/**" . PHP_EOL;
		$data .= " *@var \$this \app\core\View" . PHP_EOL;
		$data .= " */" . PHP_EOL;
		$data .= "\$this->title = 'page title here';" . PHP_EOL . PHP_EOL;
		$data .= "?>" . PHP_EOL;
		$data .= "<!-- HTML code goes here for the view -->" . PHP_EOL;

		file_put_contents($path,$data);
		
		$output->writeln('Created view');
		return 0;
	}
}
?>