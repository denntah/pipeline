<?php
namespace Pipeline;

use Pipeline\Result\IResult;
use Pipeline\Interfaces\IRunnable;

class Action implements IRunnable {
    private $func;
    private $caller;
    private $ctx;

    public function __construct($func, $caller, $ctx) {
        $this->func = $func;
        $this->caller = $caller;
        $this->ctx = $ctx;
    }

    public function run() {
        $func = $this->func->bindTo($this->ctx);
        $result = $func($this->caller);

        if ($result !== false && !($result instanceof IResult)){
            if ($this->caller instanceof IRunnable)
                $result = $this->caller->run();
        }

        return $result;
    }
}