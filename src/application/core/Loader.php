<?php
namespace Javamon\Jframe\Core;

use \Javamon\Jframe\Core\Config as Config;

class Loader
{
    private $class;
    private $function;

    public function __construct() {}

    public function init($arg = Array())
    {
        empty($this->config) ? $this->config = (new Config())->configure() : false;

        //default controller mapping
        empty($arg[0]) ? $arg[0]  = $this->config["default"]["controller"] : false;
        empty($arg[1]) ? $arg[1]  = $this->config["default"]["controller"] : false;

        $class_name = "\\Javamon\\Jframe\\Processor\\".ucfirst($this->config["default"]["controller"]);

        $instance = new $class_name();
        method_exists($instance,ucfirst($arg[0])) ? $construct =  true : $construct =  false ;

        if ($construct)
        {
            (!empty($arg[1])) ? $function_name = $arg[1] : $function_name = $arg[0];

            //remove class name and function name from arguments
            array_shift($arg);
            array_shift($arg);

            return $instance->$function_name($arg);
        }
        else
        {
            return trigger_error("The defined function can not be found : ".ucfirst($arg[0])."()", E_USER_ERROR);
        }
    }

    public function model($class = null)
    {
        //for query builder and orm
        (!empty($class))
        ? $class_name = "\\Javamon\\Jframe\\Model\\".ucfirst($class)
        : $class_name = "\\Javamon\\Jframe\\Core\\Model";

        return new $class_name();
    }

    public function controller($class = null)
    {
        $class_name = "\\Javamon\\Jframe\\Processor\\".ucfirst($class);
        return new $class_name();
    }

    public function view()
    {
        $class_name = "\\Javamon\\Jframe\\Core\\View";
        return new $class_name();
    }

}
