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

use \Javamon\Jframe\Core\Processor as Processor;
use \Javamon\Jframe\Model\User as User;
use \Javamon\Jframe\Core\ORM as ORM;

/**
 *  샘플 컨트롤러(프로세서) 클래스
 */
class Sample extends Processor
{

    /** 샘플
     * 요청 URL : http://127.0.0.1/sample/
     */
    public function Sample($arg)
    {
        /**
         * 부모 클래스에서 선언된 객체들에 바로 접근하여 바로 사용가능합니다.
         */
        $result = $this->model->selectAll('user');

        while($row=$result->fetch(\PDO::FETCH_OBJ)) {
        /*its getting data in line.And its an object*/
            $data['user'][] = $row;
        }

        //use view sample code
        $layout[] = "header";
        $layout[] = "table";
        $layout[] = "footer";

        $this->view->load($layout, $data);
    }

    public function test($arg)
    {
        echo 'sample/test function<br />';
        var_dump($arg);
    }

    /** 샘플
     * 요청 URL : http://127.0.0.1/sample/userPicture
     */
    public function userPicture()
    {
        $result = User::ORM()->selectAll();

        // $result = User::ORM()->insert(array(
        //     "user_name" => "자바몬11",
        //     "user_alias" => "javamon",
        //     "user_password" => "1111",
        //     "user_image" => null,
        // ));

        // $data = [
        //     ["자바몬12", "javamon", "1111", null],
        //     ["자바몬22", "javamon", "2223", null],
        //     ["자바몬32", "javamon", "3333", null],
        // ];
        //
        // $result = User::ORM()->multiInsert(array(
        //     "user_name",
        //     "user_alias",
        //     "user_password",
        //     "user_image",
        // ), $data);

        // $result = $this->model->insert('user', array(
        //     "user_name" => "자바몬",
        //     "user_alias" => "javamon",
        //     "user_password" => "1111",
        //     "user_image" => null,
        // ));

        // $data = [
        //     ["자바몬1", "javamon", "1111", null],
        //     ["자바몬2", "javamon", "2223", null],
        //     ["자바몬3", "javamon", "3333", null],
        // ];
        //
        // $result = $this->model->multiInsert("user", array(
        //     "user_name",
        //     "user_alias",
        //     "user_password",
        //     "user_image",
        // ), $data);

        while($row = $result->fetch(\PDO::FETCH_OBJ)) {

            $data['user'][] = $row;
        }


        $layout[] = "header";
        $layout[] = "picture";
        $layout[] = "footer";

        $this->view->load($layout, $data);
    }
}
