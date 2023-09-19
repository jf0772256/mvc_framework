<?php
	
	namespace app\core\database;
	
	use \mysqli;
	use mysqli_stmt;
	use \PDO;
	use PDOStatement;
	use \app\core\Utility;
	
	/**
	 * Connect method generates the connection to the database using the interface option use getConnection() to return the connection object
	 * Make sure to have set the interface property OR it will default to mysqli
	 *
	 * ```php
	 * $db = new Connection();
	 * $db->interface = DatabaseInterface::PDO_INTERFACE; // can be MYSQLI or SQLITE interfaces too
	 * $db->connect();
	 * ```
	 *
	 * @return Connection
	 * @author Jesse Fender
	 */
	class Connection extends Database
	{
		/**
		 * Runs a query off of the database connection interface used, returning the statement object
		 *
		 * @param string      $sql    SQL statement to be prepared and ran
		 * @param array|null  $params Parameters array to bind to the query through the statement object
		 * @param string|null $types  For when you are using mysqli (DatabaseInterface::MYSQLI_INTERFACE) and want to use a defined parameter type string, otherwise will default to 's' and is not used for PDO/SQLite connections
		 *
		 * ```php
		 *  //after creating connection instance use query() on the Connection instance
		 *  $db->("SELECT * FROM table")->get_results()->fetch_all(MYSQLI_ASSOC);
		 *  // pdo uses by default the associative arrays for array returns
		 *  // sqlite interface is technically a wrapped pdo connection, it uses a different sql syntax.
		 * ```
		 *
		 * All queries are parametrised, and you can pass parameters to the query method using the params array:
		 * ```php
		 *  $id = 1;
		 *  $db->query("SELECT * FROM table WHERE id=?", [$id])->get_results()->fetch_array(MYSQLI_ASSOC);
		 * ```
		 *
		 * The final parameter is not required and will be auto generated if not passed
		 *
		 * @return mysqli_stmt|PDOStatement|bool Either a PDO or mysqli statement object or a boolean if there was an error
		 * @author Jesse Fender
		 */
		public function query (string $sql, ?array $params = [], ?string $types = null)
		{
			if ($this->interface === DatabaseInterface::MYSQLI_INTERFACE)
			{
				// run prepared query return the statement object to caller;
				$types = $types ?? str_repeat('s', count($params));
				$statement = $this->connection->prepare($sql);
				if (count($params) > 0 && $this->interface === DatabaseInterface::MYSQLI_INTERFACE)
				{
					$statement->bind_param($types, ...$params);
					$statement->execute();
				} elseif (count($params) > 0 && $this->interface !== DatabaseInterface::MYSQLI_INTERFACE)
				{
					$statement->execute($params);
				} else
				{
					$statement->execute();
				}
				return $statement;
			} else
			{
				// run prepared query and return the statement object to caller;
				$statement = $this->connection->prepare($sql);
				$statement->execute($params);
				return $statement;
			}
			
		}
		
		function connect ()
		{
			try
			{
				if ($this->interface === DatabaseInterface::MYSQLI_INTERFACE)
				{
					// create connection object mysqli
					$this->connection = new mysqli($this->host, $this->user, $this->pass, $this->name, $this->port);
					if ($this->connection->connect_error) Utility::dieAndDumpPretty("Error connecting to MySQL: {$this->connection->connect_error}");
				} elseif ($this->interface === DatabaseInterface::PDO_INTERFACE)
				{
					// create connection using pdo
					$this->connection = new PDO($this->buildDSN(), $this->user, $this->pass, $this->pdoOptions);
					$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				} elseif ($this->interface === DatabaseInterface::SQLITE_INTERFACE)
				{
					// create connection using pdo specifically for sqlite
					$this->connection = new PDO($this->buildDSN(), null, null, $this->pdoOptions);
					$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				} else
				{
					throw new DatabaseInterfaceException('Interface not implemented', 1042);
				}
			} catch (\Throwable $th)
			{
				Utility::dieAndDumpPretty($th);
			}
		}
		
		/**
		 * Creates the DSN st ring that is used to connect
		 * @return string DSN connection string to use when generating a new connection.
		 * @author Jesse Fender
		 */
		private function buildDSN (): string
		{
			if ($this->type === "sqlite")
			{
				return "{$this->type}:{$this->name}";
			} else
			{
				return "{$this->type}:host={$this->host};port={$this->port};dbname={$this->name}";
			}
		}
	}
	
	?>