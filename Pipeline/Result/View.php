<?php
namespace Pipeline\Result;

class View extends Base {
    private $view = null;
    private $data = null;

    public function __construct($ctx, $view, $data = null) {
        if ($ctx->settings['templateRoot'] === null)
            throw new \Exception('templateRoot not defined');

        parent::__construct($ctx, 'text/html','UTF-8');
        $this->view = $view;
        $this->data = $data;
    }

    public function render() {
        $this->clean();
        if ($this->data !== null)
            extract($this->data);
        include($this->ctx->settings['templateRoot'] . $this->view);
    }
}