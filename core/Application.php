<?php
namespace app\core;

ini_set("display_errors", "On");
error_reporting(E_ALL);
use app\core\database\Connection;
use app\core\database\DatabaseInterface;

class Application {
    public Router $router;
    public Request $request;
    public Connection $dbc;
    public Response $response;
    public Session $session;
    public Controller $controller;
    public ?UserModel $user;
    public View $view;
    public string $userClass = '';
    public string $layout = "main";

    public static Application $app;
    public static string $ROOT_DIR;

    /**
     * Constructor Function
     *
     * @param string $database_connector Use mysqli or pdo to set the database connectorthat you want to use, defaults to mysqli
     */ 
    function __construct(string $rootPath, $userClass, $database_connector = "mysqli") {
        DotEnv::load($rootPath . '/.env');
        $this->userClass = $userClass;
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->session = new Session();
        $this->view = new View();

        $this->CreateDatabaseConnection($database_connector);

        // setting up the user for right now will def have to come back for this in a bit
        $primaryValue = $this->session->get('user');
        if ($primaryValue) {
            $primaryKey = $this->userClass::primaryKey();
            $this->user = $this->userClass::findOne([$primaryKey => $primaryValue]);
        } else {
            $this->user = NULL;
        }
    }

    function run () {
        try {
            echo $this->router->resolve();
        } catch (\Exception $e) {
            $this->response->setStatusCode($e->getCode());
            echo $this->view->renderView('_error', ['exception' => $e]);
        }
    }

    function getDatabase() {
        return $this->dbc;
    }

    function login(DbModel $user) : bool {
        //do log in things here
        $this->user = $user;
        $primaryKey = $user::primaryKey();
        $primaryValue = $user->{$primaryKey};
        $this->session->set('user', $primaryValue);
        return true;
    }

    function logout() {
        $this->user = null;
        $this->session->remove('user');
    }

    static function isGuest() : bool {
        return Application::$app->user ? false : true;
    }
    
    /**
     * function to do the logic to create the database connection
     * @param string $database_connector Connection type to use, mysqli, pdo, or sqlite
     * @return void
     * @author Jesse Fender
     */
    private function CreateDatabaseConnection (string $database_connector) {
        $this->dbc = new Connection();
        $this->dbc->host = $_ENV["DATABASE_HOST"];
        $this->dbc->name = $_ENV["DATABASE_NAME"];
        $this->dbc->user = $_ENV["DATABASE_USER"];
        $this->dbc->pass = $_ENV["DATABASE_PASS"];
        $this->dbc->port = $_ENV["DATABASE_PORT"];
        $this->dbc->type = $_ENV["DATABASE_TYPE"];
        if ($database_connector == "mysqli") {
            $this->dbc->interface = DatabaseInterface::MYSQLI_INTERFACE;
        } elseif ($database_connector == "pdo") {
            $this->dbc->interface = DatabaseInterface::PDO_INTERFACE;
        } elseif ($database_connector == "sqlite") {
            $this->dbc->interface = DatabaseInterface::SQLITE_INTERFACE;
        }  else {
            Utility::dieAndDumpPretty("Unable to connect to database connector provided. use 'mysqli', 'pdo', or 'sqlite'.");
        }
        $this->dbc->connect();
    }
}

?>