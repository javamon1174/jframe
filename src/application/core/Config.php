<?php
namespace Javamon\Jframe\Core;

class Config
{
    private $web_user = 'web_master';

    private $database = [
        "host"=> "localhost",
        "db"=> "jframe",
        "user"=> "jframe",
        "password"=> "1111",
        "port"=> "3306",
    ];

    private $default = [
        "controller" => "sample",
        "model" => "sample",
        "view" => "index",
    ];

    public function configure()
    {
        $this->constDefine();
        $config["user"] = $this->web_user;
        $config["database"] = $this->database;
        $config["default"] = $this->default;
        $config["sessions"] = md5("jframe");
        return $config;
    }

    private function constDefine()
    {
        defined('DEVELOPMENT_ENVIRONMENT') or define('DEVELOPMENT_ENVIRONMENT', true);
        defined('ROOT') or define('ROOT', '/home/vagrant/jframe');

    }
}
