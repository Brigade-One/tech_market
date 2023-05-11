<?php
namespace ServerTasks;

use OrderController;
use Amp\Cancellation;
use Amp\Parallel\Worker\Task;
use Amp\Sync\Channel;

class GetOrderHistoryTask implements Task
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
    public function getOrderHistory($args)
    {
        require_once 'lib/order_controller.php';
        return json_encode(OrderController::getOrderHistory($args['token']));
    }
}