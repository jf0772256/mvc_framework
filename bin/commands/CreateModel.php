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
class CreateModel extends Command {
	/**
	 * Configure. Use methods $this->setDescription to set command descriptive, $this->setHelp to set help text, $this->addArgument to create and capture arguments, $this->getArgument to fetch the value of the specified argument
	 */
	protected function configure() {
		parent::configure();
		$this->setName('create:model');
		$this->setDescription("Creates a new model file, expects two arguments, 1. model name, 2. uses dbmodel [1] OR uses model [2][default].");
		$this->setHelp("Creates a model to use, If second arg is 1, will use the DBModel class otherwise [2] will use the Model class");
		$this->addArgument("ModelName", InputArgument::REQUIRED, "Model name that the model class will be created with as well as the file name");
		$this->addArgument("UsesModel", InputArgument::OPTIONAL, "Uses what model to extend the new model class by, 1 = DBModel, 2[default] = Model", 2);
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
		$output->writeln('Create new model...');
		$class = $input->getArgument("ModelName");
		$uses = (int)$input->getArgument("UsesModel");
		$path = "models\\$class.php";
		$data = "<?php" . PHP_EOL . PHP_EOL;
		$data .= "namespace app\models;" . PHP_EOL;
		$data .= $uses == 1 ? "use app\core\DbModel;" . PHP_EOL . PHP_EOL : "use app\core\Model;" . PHP_EOL . PHP_EOL;
		$data .= $uses == 1 ? "class {$class} extends DBModel {" . PHP_EOL : "class {$class} extends Model {" . PHP_EOL;
		$data .= "\tfunction rules() : array {" . PHP_EOL;
		$data .= "\t\treturn []; // Populate validation rules" . PHP_EOL;
		$data .= "\t}" . PHP_EOL . PHP_EOL;
		$data .= "\tfunction labels() : array {" . PHP_EOL;
		$data .= "\t\treturn []; // populate with model labels" . PHP_EOL;
		$data .= "\t}" . PHP_EOL . PHP_EOL;

		if ($uses == 1) {
			$data .= "\tfunction tableName() : string {" . PHP_EOL;
			$data .= "\t\treturn 'table name goes here';" . PHP_EOL;
			$data .= "\t}" . PHP_EOL . PHP_EOL;
			$data .= "\tfunction primaryKey() : string {" . PHP_EOL;
			$data .= "\t\treturn 'primary key goes here';" . PHP_EOL;
			$data .= "\t}" . PHP_EOL . PHP_EOL;
			$data .= "\tfunction attributes() : array {" . PHP_EOL;
			$data .= "\t\treturn []; // database field names" . PHP_EOL;
			$data .= "\t}" . PHP_EOL . PHP_EOL;
		}

		$data .= "}" . PHP_EOL . PHP_EOL;
		$data .= "?>";

		file_put_contents($path, $data);

		$output->writeln("Created model {$class}.php");

		return 0;
	}
}
?>