<?php
namespace ServerTasks;

use AuthController;

use Amp\Cancellation;
use Amp\Parallel\Worker\Task;
use Amp\Sync\Channel;

class AuthTask implements Task
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
    public function signIn($args)
    {
        require_once 'lib/auth_controller.php';
        return json_encode(AuthController::signIn($args['jsonData']));
    }
    public function signUp($args)
    {
        require_once 'lib/auth_controller.lib';
        return json_encode(AuthController::signUp($args['jsonData']));
    }
}