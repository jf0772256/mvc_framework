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
	class GenerateDocs extends Command
	{
		/**
		 * Configure. Use methods $this->setDescription to set command descriptive, $this->setHelp to set help text, $this->addArgument to create and capture arguments, $this->getArgument to fetch the value of the specified argument
		 */
		protected function configure ()
		{
			parent::configure();
			$this->setName('create:docs');
			$this->setDescription('Run php documentation generation script');
			$this->setHelp("Runs and either creates or updates existing documentation for easier display of the use / api / others of the framework.");
		}
		
		/**
		 * Execute command. Use method $this->getArgument to fetch the value of the specified argument
		 *
		 * @param InputInterface  $input
		 * @param OutputInterface $output
		 *
		 * @return int integer 0 on success, or an error code
		 */
		protected function execute (InputInterface $input, OutputInterface $output)
		{
			$output->writeln('Running PHP Documentation Builder, please wait for the script to complete');
			$output->writeln('After running your documentation will be in the /docs folder. Navigate to the index.html file to view.');
			$output->writeln(shell_exec("php phpDocumentor.phar"));
			return 0;
		}
	}
	
	?>