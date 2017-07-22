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
namespace Javamon\Jframe\Processor;

use \Javamon\Jframe\Core\Rest as Rest;
use \Javamon\Jframe\Model\User as Users;
use \Javamon\Jframe\Core\ORM as ORM;

/**
 *  샘플 컨트롤러(프로세서) 클래스
 */
class User extends Rest
{
    function index()
    {
        if (!empty($this->input->{$this->method}) || $this->method == 'get')
        {
            return $this->{$this->method}();
        }
    }

    private function get()
    {
        if (!empty($this->input->get))
        {
            $result = Users::ORM()->select("*", "user_index", $this->input->get[0]);
            while($row=$result->fetch(\PDO::FETCH_OBJ)) {
                $data['user'][] = $row;
            }
            exit(json_encode($data));
        }
        else
        {
            $result = Users::ORM()->selectAll();
            while($row=$result->fetch(\PDO::FETCH_OBJ)) {
                $data['user'][] = $row;
            }
            exit(json_encode($data));
        }
    }

    private function post()
    {
        $result = Users::ORM()->insert(array(
            "user_name"         => $this->input->post["user_name"],
            "user_alias"        => $this->input->post["user_alias"],
            "user_password"     => $this->input->post["user_password"],
            "user_image"        => $this->input->post["user_image"],
        ));

        $code = 200;
        http_response_code($code);
        
        if ($result == 0)
        {
            $response["result"] = [
                "message"   => "insert success",
                "http_code" => ($code),
            ];
        }
        else {
            $response["result"] = [
                "message"   => "insert fail",
                "http_code" => ($code),
            ];
        }
        return exit(json_encode($response));

    }
    private function put()
    {
        $result = Users::ORM()->update(
            "user_name",
                $this->input->put["user_name"],
            "user_index",
                $this->input->put["user_index"]
        );

        if ($result)
        {
            $code = 200;
            $response["result"] = [
                "message"   => "update success",
                "http_code" => ($code),
            ];
            http_response_code($code);

            return exit(json_encode($response));
        }
    }

    private function delete()
    {
        echo 'delete';
    }

}
