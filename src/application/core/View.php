<?php
namespace Javamon\Jframe\Core;

use \Javamon\Jframe\Core\Loader as Loader;

class View
{
    private $data = array();

    private $render = FALSE;

    public function load($view = null)
    {

        try {
            $file = ROOT . '/src/application/view/' . strtolower($view) . '.php';

            if (file_exists($file)) {
                $this->render = $file;
            } else {
                throw new customException('View : ' . $view . ' not found!');
            }
        }
        catch (customException $e) {
            echo $e->errorMessage();
        }
    }

    public function assign($input_data)
    {
        $this->data['view_data'] = $input_data;
    }

    public function __destruct()
    {
        extract($this->data);
        include_once($this->render);
    }

    public function __construct() { }
}
