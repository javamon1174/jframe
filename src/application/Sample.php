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

/** 샘플
 *  샘플 컨트롤러(프로세서) 클래스
 */
class Sample extends Processor
{

    /** 샘플
     * 요청 URL : http://127.0.0.1/sample/
     */
    public function Sample($arg)
    {
        // use model sample code
        // $this->model->update('user', 'user_name','power','user_index', 5);
        // $this->model->delete('user', 'user_index', 5);
        // $result = $this->model->select('user', '*','user_index', 6);
        // $result = $this->model->selectAll('user', 'user_name');
        //
        // use orm model sample code
        // User::ORM()->update('user_name','powerfull','user_index', 6);
        // User::ORM()->delete('user_index', 6);
        // $result = User::ORM()->select( '*','user_index', 6);

        /**
         * 부모 클래스에서 선언된 객체들에 바로 접근하여 바로 사용가능합니다.
         */
        $result = User::ORM()->selectAll();

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
    
    /** 샘플
     * 요청 URL : http://127.0.0.1/sample/userPicture
     */
    public function userPicture()
    {
        $result = User::ORM()->selectAll();

        while($row=$result->fetch(\PDO::FETCH_OBJ)) {
            $data['user'][] = $row;
        }

        $layout[] = "header";
        $layout[] = "picture";
        $layout[] = "footer";

        $this->view->load($layout, $data);
    }
}
