<?php
namespace Pipeline;

use Pipeline\Action;
use Pipeline\Context;
use Pipeline\Interfaces\IRunnable;
use Pipeline\Result\IResult;

class App implements IRunnable {
    private $ctx = null;
    private $path = null;
    private $routes = array();
    private $running = false;

    public function __construct(Context $ctx, $path = null) {
        $this->ctx = $ctx;
        $this->path = $path;

        // default (not found) middleware
        $this->use(function($next) {
            $next();
            if (!$this->hasResult())
                $this->status(404);
        });
    }

    public static function init($settings = null) {
        return new App(new Context($settings));
    }

    private function add($func, $caller = null) {
        $this->routes[] = new Action($func, $caller, $this->ctx);
    }

    public function run() {
        if ($this->running === true)
            return;
        
        $this->running = true;

        while ($this->next()) { }

        if ($this->path === null)
            $this->ctx->render();
    }

    public function next() {
        if (!count($this->routes) || $this->ctx->hasResult())
            return false;

        $route = array_shift($this->routes);
        
        return $route->run() !== false;
    }

    public function use($func) {
        $self = $this;
        $this->add($func, function() use($self) { $self->next(); });
    }

    public function map($path, $func) {
        if (Uri::startsWith($this->path . $path)) {
            $app = new App($this->ctx, $this->path . $path);
            $this->add($func, $app);
        }
    }

    public function get($path, $func) {
        $this->exec($path, $func, 'GET');
    }

    public function post($path, $func) {
        $this->exec($path, $func, 'POST');
    }

    public function put($path, $func) {
        $this->exec($path, $func, 'PUT');
    }

    public function delete($path, $func) {
        $this->exec($path, $func, 'DELETE');
    }

    private function exec($path, $func, $method) {
        if (Uri::matches($this->path . $path, $method)) {
            $this->ctx->args = Uri::getArguments($this->path . $path);
            $this->add($func);
        }
    }
}