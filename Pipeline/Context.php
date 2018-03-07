<?php
namespace Pipeline;

use Pipeline\Result\IResult;
use Pipeline\Result\View;
use Pipeline\Result\Status;
use Pipeline\Result\Redirect;

class Context {
    private $result = null;
    public $settings = [
        'debug' => false,
        'templateRoot' => null
    ];

    public function __construct($settings) {
        if ($settings !== null)
            $this->settings = array_merge($this->settings, $settings);
    }

    public function isDebug() {
        return !!$this->settings['debug'];
    }

    public function hasResult() {
        return $this->result instanceof IResult;
    }

    public function render() {
        $this->result->render();
    }

    public function redirect($url, $permanent = true) {
        $this->result = new Redirect($this, $url, $permanent);
    }

    public function view($view, $data = null) {
        $this->result = new View($this, $view, $data);
    }
    
    public function status($statusCode, $data = null) {
        $this->result = new Status($this, $statusCode, $data);
    }
}