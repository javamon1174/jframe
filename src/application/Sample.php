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
        // $this->database->update('user', 'user_name','power','user_index', 5);
        // $this->database->delete('user', 'user_index', 5);
        // $result = $this->database->select('user', '*','user_index', 6);
        // $result = $this->database->selectAll('user', 'user_name');


        $result = User::ORM()->selectAll();
        // User::ORM()->update('user_name','powerfull','user_index', 6);
        // User::ORM()->delete('user_index', 6);
        // $result = User::ORM()->select( '*','user_index', 6);

        while($row=$result->fetch(\PDO::FETCH_OBJ)) {
        /*its getting data in line.And its an object*/
            $data[] = $row;
        }

        $var_array = array("color" => "blue",
                   "size"  => "medium",
                   "shape" => "sphere");

        // $view = new view();
        $this->view->load("table");
        $this->view->assign($data);

        // $this->view->load("table");
        // $this->view->assign('variablename', 'variable content');

        // $this->view->assign($var_array);

    }

    public function bare($arg)
    {
        echo "sample/bare <hr />";
        var_dump($arg);
    }

    public function layer($arg)
    {
        echo "sample/layer <hr />";
        var_dump($arg);
    }



}
