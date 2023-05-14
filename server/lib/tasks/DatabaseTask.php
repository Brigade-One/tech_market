<?php
namespace ServerTasks;

use DBController;
use Amp\Cancellation;
use Amp\Parallel\Worker\Task;
use Amp\Sync\Channel;

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
    public function getAllItems($args)
    {
        require_once 'lib/db_controller.php';
        return json_encode(DBController::getAllItemsFromDB('SELECT * FROM items'));
    }
    public function getItemById($args)
    {
        require_once 'lib/db_controller.php';
        return json_encode(DBController::getItemInstanceByID($args['id']));
    }
}