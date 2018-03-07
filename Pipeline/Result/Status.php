<?php
namespace Pipeline\Result;

class Status extends Base {
    private $statusCode;
    private $data;

    public function __construct($ctx, $statusCode, $data = null) {
        parent::__construct($ctx, 'application/json','UTF-8');
        $this->statusCode = $statusCode;
        $this->data = $data;
    }

    public function render() {
        $this->clean();
        
        http_response_code($this->statusCode);

        if ($this->data)
            echo json_encode($this->data);
    }
}