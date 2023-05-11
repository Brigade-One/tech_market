<?php
use Amp\Cancellation;
use Amp\Parallel\Worker\Task;
use Amp\Sync\Channel;

class OrderTask implements Task
{
    public function __construct(
        private string $token
    ) {
    }

    public function run(Channel $channel, Cancellation $cancellation): string
    {
        require_once './order_controller.php';
        return OrderController::writeOrderData('./data/orders.txt', $this->token);
    }
}