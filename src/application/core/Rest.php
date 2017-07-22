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

use \Javamon\Jframe\Core\Config     as Config;
use \Javamon\Jframe\Core\Processor  as Processor;
use \Javamon\Jframe\Core\Loader     as Loader;
use \Javamon\Jframe\Core\Model      as Model;
use \Javamon\Jframe\Model\Token     as Token;
use \Javamon\Jframe\Core\ORM        as ORM;

/**
 *  레스트 프로세스 클래스 : RESTFUL API를 호환하기 위한 클래스
 */
class Rest extends Processor
{
    /**
     * @access protected
     * @var Array $input : HTTP request data
     */
    protected $input;

    /**
     * @access protected
     * @var Array $method : HTTP METHOD
     */
    protected $method;

    public function __construct($http_get)
    {
        // setting config
        if (empty($this->config))
            $this->config = (new Config())->configure();

        // setting loader
        if (empty($this->load))
            $this->load = new loader();

        // setting model object
        if (empty($this->model))
            $this->model = $this->load->model();

        // setting view object
        if (empty($this->view))
            $this->view = $this->load->view();

        $this->getRequestInfo($http_get);
    }

    protected function getRequestInfo($http_get)
    {
        // if ($_SERVER["CONTENT_TYPE"] == "application/json" && !empty($_SERVER["HTTP_ACCESS_TOKEN"]))
        if (isset($_SERVER["CONTENT_TYPE"]))
        {
            $this->method = strtolower($_SERVER["REQUEST_METHOD"]);

            defined('HOST')              or define('HOST',              $_SERVER["REQUEST_SCHEME"].'://'.$_SERVER["HTTP_HOST"]);
            defined('CONTENT_TYPE')      or define('CONTENT_TYPE',      $_SERVER["CONTENT_TYPE"]);
            defined('REQUEST_METHOD')    or define('REQUEST_METHOD',    $_SERVER["REQUEST_METHOD"]);
            defined('HTTP_ACCESS_TOKEN') or define('HTTP_ACCESS_TOKEN', $_SERVER["HTTP_ACCESS_TOKEN"]);
            defined('HTTP_VERSION')      or define('HTTP_VERSION',      "HTTP/1.1");
            defined('STATUS_CODE')       or define('STATUS_CODE',       200);
            defined('STATUS_MESSAGE')    or define('STATUS_MESSAGE',    $this->getHttpStatusMessage(200));

            header(HTTP_VERSION. " ". STATUS_CODE ." ". STATUS_MESSAGE);
            header("Content-Type:". CONTENT_TYPE);

            //token check
            if (!$this->accessTokenCheck())
            {
                $code = 203;
                $response["result"] = [
                    "msg"       => $this->getHttpStatusMessage($code),
                    "http_code" => ($code),
                ];
                http_response_code($code);

                return
                exit(json_encode($response));
            }

            //get body data
            if ($this->method == "get")
            {
                $this->input                  = new \stdClass();
                $this->input->{$this->method} = $http_get;
            }
            elseif ($this->method != "get")
            {
                $this->ParseRawHttpRequest();
            }
        }
        else
        {
            $code = 400;
            $response["result"] = [
                "message"   => $this->getHttpStatusMessage($code),
                "http_code" => ($code),
            ];
            http_response_code($code);

            return
            exit(json_encode($response));
        }
    }

    private function accessTokenCheck()
    {
        $result = Token::ORM()->select('seq', 'token', HTTP_ACCESS_TOKEN);
        return ($result->fetch(\PDO::FETCH_ASSOC) == true);
    }

    protected function getHttpStatusMessage($statusCode){

		$httpStatus = [
			100 => 'Continue',
			101 => 'Switching Protocols',
			200 => 'OK',
			201 => 'Created',
			202 => 'Accepted',
			203 => 'Non-Authoritative Information',
			204 => 'No Content',
			205 => 'Reset Content',
			206 => 'Partial Content',
			300 => 'Multiple Choices',
			301 => 'Moved Permanently',
			302 => 'Found',
			303 => 'See Other',
			304 => 'Not Modified',
			305 => 'Use Proxy',
			306 => '(Unused)',
			307 => 'Temporary Redirect',
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			406 => 'Not Acceptable',
			407 => 'Proxy Authentication Required',
			408 => 'Request Timeout',
			409 => 'Conflict',
			410 => 'Gone',
			411 => 'Length Required',
			412 => 'Precondition Failed',
			413 => 'Request Entity Too Large',
			414 => 'Request-URI Too Long',
			415 => 'Unsupported Media Type',
			416 => 'Requested Range Not Satisfiable',
			417 => 'Expectation Failed',
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
			502 => 'Bad Gateway',
			503 => 'Service Unavailable',
			504 => 'Gateway Timeout',
			505 => 'HTTP Version Not Supported'];

		return ($httpStatus[$statusCode]) ? $httpStatus[$statusCode] : $status[500];
	}

    // https://stackoverflow.com/questions/5483851/manually-parse-raw-http-data-with-php
    private function ParseRawHttpRequest()
    {
        $input = file_get_contents('php://input');

        preg_match('/boundary=(.*)$/', $_SERVER['CONTENT_TYPE'], $matches);
        (isset($matches[1])) ? $boundary = $matches[1] : $boundary = "";

        $a_blocks = preg_split("/-+$boundary/", $input);
        array_pop($a_blocks);

        // loop data blocks
        foreach ($a_blocks as $id => $block)
        {
            if (empty($block)) continue;

            if (strpos($block, 'application/octet-stream') !== FALSE)
            {
                preg_match("/name=\"([^\"]*)\".*stream[\n|\r]+([^\n\r].*)?$/s", $block, $matches);
            }
            else
            {
                preg_match('/name=\"([^\"]*)\"[\n|\r]+([^\n\r].*)?\r$/s', $block, $matches);
            }
            if (count($matches) > 0) $temp[$matches[1]] = $matches[2];
        }

        $this->input                  = new \stdClass();
        $this->input->{$this->method} = $temp;
    }
}
