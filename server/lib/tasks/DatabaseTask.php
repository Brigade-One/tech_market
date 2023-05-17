<?php
namespace ServerTasks;

use TechMarket\Lib\DBController;
use Amp\Cancellation;
use Amp\Parallel\Worker\Task;
use Amp\Sync\Channel;
use TechMarket\Lib\ConnectDB;

define('DB_NAME', 'techmarket');
define('DB_TABLE', 'tech_market_db');
define('DB_USER', 'root');
define('DB_PASSWORD', '');

class DatabaseTask implements Task
{
    /**
     * @var callable
     */
    private $function;
    /**
     * @var mixed[]
     */
    private $args;
    public function __construct(
        string $function,
        array $args = []
    ) {
        $this->function = $function;
        $this->args = $args;
    }

    public function run(Channel $channel, Cancellation $cancellation): mixed
    {
        $function = $this->function;
        return $this->$function($this->args);
    }
    public function getItemsByQuery($args)
    {

        $connectDB = new ConnectDB(DB_NAME, DB_TABLE, DB_USER, DB_PASSWORD);
        $dbController = new DBController($connectDB);
        return json_encode($dbController->getItemsByQuery($args['query']));
    }
    public function getItemById($args)
    {
        $connectDB = new ConnectDB(DB_NAME, DB_TABLE, DB_USER, DB_PASSWORD);
        $dbController = new DBController($connectDB);
        return json_encode($dbController->getItemInstanceByID($args['id']));
    }
}