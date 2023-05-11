<?php

class MessageQueue
{
    private $queue = [];

    public function add($task)
    {
        array_push($this->queue, $task);
    }
    public function getTasks()
    {
        return $this->queue;
    }
    public function remove()
    {
        return array_shift($this->queue);
    }

    public function isEmpty()
    {
        return empty($this->queue);
    }
}