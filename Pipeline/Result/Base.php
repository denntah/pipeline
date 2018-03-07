<?php
namespace Pipeline\Result;

use Pipeline\Context;

abstract class Base implements IResult {
    private $trash;
    private $contentType;
    private $charset;
    protected $ctx;

    public function __construct(Context $ctx, $contentType, $charset) {
        $this->ctx = $ctx;
        $this->contentType = $contentType;
        $this->charset = $charset;
    }

    protected function clean() {
        if ($this->ctx->isDebug()){
            $content = ob_get_clean();
            ob_start();
        } else {
            ob_clean();
            ob_start('ob_html_compress');
        }
    
        mb_internal_encoding($this->charset);
        mb_http_output($this->charset);
        header("Content-Type: $this->contentType; charset=$this->charset");

        if ($this->ctx->isDebug())
            echo $content;
    }

    public abstract function render();
}