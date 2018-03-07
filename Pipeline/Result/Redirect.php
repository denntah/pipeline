<?php
namespace Pipeline\Result;

class Redirect extends Base {
    private $url = null;
    private $permanent = null;

    public function __construct($ctx, $url, $permanent) {
        parent::__construct($ctx, 'text/html','UTF-8');
        $this->url = $url;
        $this->permanent = $permanent;
    }

    public function render() {
        $this->clean();
        header('Location: ' . $this->url, true, $this->permanent ? 301 : 302);
        die();
    }
}