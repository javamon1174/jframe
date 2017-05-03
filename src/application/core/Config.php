<?php
/**
 * Javamon's JFramework
 *
 * PHP 컴포저 기반 제이프레임워크
 *
 * Created on 2017. 5.
 * @package      Javamon\Jframe
 * @category     Index
 * @license      http://opensource.org/licenses/MIT
 * @author       javamon <javamon1174@gmail.com>
 * @link         http://javamon.be/Jframe
 * @link         https://github.com/javamon1174/jframe
 * @version      0.0.1
 */
namespace Javamon\Jframe\Core;

/**
 *  프레임워크 구동을 위한 환경설정 코어클래스
 */
class Config
{
    /**
     * @access private
     * @var String $web_user : 웹상의 유저
     */
    private $web_user = 'web_master';

    /**
     * @access private
     * @var Array $database : 데이터베이스 기본 정보
     */
    private $database = [
        "host"=> "localhost",
        "db"=> "jframe",
        "user"=> "jframe",
        "password"=> "",
        "port"=> "3306",
    ];

    /**
     * @access private
     * @var Array $default : 기본 MVC 모듈 지정
     */
    private $default = [
        "controller" => "sample",
        "model" => "sample",
        "view" => "index",
    ];

    /**
     * 기본 셋팅 값 CONFIG 인스턴스 변수에 저장
     * @access public
     * @return Array $config : set config instance
     */
    public function configure()
    {
        $this->constDefine();
        $config["user"] = $this->web_user;
        $config["database"] = $this->database;
        $config["default"] = $this->default;
        $config["sessions"] = md5("jframe");
        return $config;
    }

    /**
     * 상수 선언부
     * @access private
     * @return void
     */
    private function constDefine()
    {
        defined('DEVELOPMENT_ENVIRONMENT') or define('DEVELOPMENT_ENVIRONMENT', true);
        defined('ROOT') or define('ROOT', '/home/vagrant/jframe');
        defined('HTTP_HOST') or define('HTTP_HOST', 'http://localhost:7777/');
    }
}
