<?php
	
	namespace app\core\database;
	
	use \mysqli;
	use \PDO;
	
	final class DatabaseInterfaceException extends \Exception
	{
		function __construct ($message = "", $code = 0, ?\Exception $previous = null)
		{
			parent::__construct($message, $code, $previous);
		}
	}
	
	/**
	 * Enum class for ensuring that the database interface that you wanted is actually selected
	 * there are three types to use: MYSQLI_INTERFACE, PDO_INTERFACE, and SQLITE_INTERFACE
	 * usage::
	 * ```php
	 * DatabaseInterface::MYSQLI_INTERFACE;  // would select the mysqli database connection type
	 * ```
	 */
	final class DatabaseInterface
	{
		const PDO_INTERFACE = 0;
		const MYSQLI_INTERFACE = 1;
		const SQLITE_INTERFACE = 2;
	}
	
	/**
	 * Use Connection.php to create a connection to the database
	 * @author        Jesse Fender
	 * @copyright (c) 2022
	 */
	abstract class Database
	{
		/**
		 * host variable is a string
		 * @var string $host
		 * @author Jesse Fender
		 */
		private string $host = 'localhost';
		/**
		 * Database name, if type is sqlite this is the path to the database file
		 * @var string $name
		 * @author Jesse Fender
		 */
		private string $name = '';
		/**
		 * Username for the connection
		 * @var string $user
		 * @author Jesse Fender
		 */
		private string $user = '';
		/**
		 * Password for the user
		 * @var string $pass
		 * @author Jesse Fender
		 */
		private string $pass = '';
		/**
		 * Database port used
		 * @var string $port
		 * @author Jesse Fender
		 */
		private string $port = '3306';
		/**
		 * Database type, used only with PDO connection
		 * @var string $type
		 * @author Jesse Fender
		 */
		private string $type = 'mysql';
		/**
		 * PDO Connection options array, currently sets default returns as assoc arrays, can change or add other properties. Only use dby the PDODatabase
		 * @var array $pdoOptions
		 * @author Jesse Fender
		 */
		private array $pdoOptions = [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false];
		/**
		 * Summary of connection
		 * @var mysqli|PDO
		 * @author Jesse Fender
		 */
		private $connection;
		private $interface = DatabaseInterface::MYSQLI_INTERFACE;
		
		public function __get ($prop)
		{
			return $this->{$prop};
		}
		
		public function __set ($prop, $value)
		{
			$this->{$prop} = $value;
		}
		
		/**
		 * Create connection object
		 * @return void
		 * @author Jesse Fender
		 */
		abstract function connect ();
		
		/**
		 * Fetches connection from the class. you technically can use teh getter, but this allows a functional process for this
		 * @return PDO|mysqli database connection object
		 * @author Jesse Fender
		 */
		function getConnection ()
		{
			return $this->connection;
		}
	}