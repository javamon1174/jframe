<?php
namespace Javamon\Jframe\Core;

class CustomException extends \Exception
{
    public function errorMessage() : string
    {
        $msg =
<<<DIV
<div style="position: fixed; top: 0; left: 0;margin:auto; background: black; color:white;width: 100%; height: 100%; padding: 50px 0px 0px 50px;">
    error message : {$this->getMessage()} <hr />
    file          : {$this->getFile()} <br />
    line          : {$this->getLine()}
</div>
DIV;

        //write web server log
        error_log($msg);
        error_log("{$this->getTraceAsString()}");
        
        return exit($msg);
    }
}