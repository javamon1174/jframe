<?php
namespace Javamon\Jframe\Core;

use \Javamon\Jframe\Core\Config as Config;
use \Javamon\Jframe\Core\Loader as Loader;
use \Javamon\Jframe\Core\Model as Model;
use \Javamon\Jframe\Core\ORM as ORM;

class Processor
{
    protected $load;
    protected $database;
    protected $config;

    public function __construct()
    {
        empty($this->config) ? $this->config = (new Config())->configure() : false;
        empty($this->load) ? $this->load = new loader() : false;
        empty($this->database) ? $this->database = $this->load->model() : false;
    }
}
