<?php
namespace Javamon\Jframe\Processor;

use \Javamon\Jframe\Core\Processor as Processor;
use \Javamon\Jframe\Model\User as User;
use \Javamon\Jframe\Core\ORM as ORM;

class Sample extends Processor
{

    public function Sample($arg)
    {
        // use model sample code
        // $this->model->update('user', 'user_name','power','user_index', 5);
        // $this->model->delete('user', 'user_index', 5);
        // $result = $this->model->select('user', '*','user_index', 6);
        // $result = $this->model->selectAll('user', 'user_name');

        $result = User::ORM()->selectAll();
        // User::ORM()->update('user_name','powerfull','user_index', 6);
        // User::ORM()->delete('user_index', 6);
        // $result = User::ORM()->select( '*','user_index', 6);

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
