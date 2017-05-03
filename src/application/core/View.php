<?php
namespace Javamon\Jframe\Core;

use \Javamon\Jframe\Core\Loader as Loader;

class View
{
    private $data = Array();

    private $render = false;

    public function load(
                            $views = Array(),
                            $input_data = Array()
                        )
    {
        $this->data = $input_data;

        foreach ($views as $key => $view)
        {
            
            try
            {
                $file = ROOT . '/src/application/view/' . strtolower($view) . '.php';

                if (file_exists($file))
                {
                    $this->render = $file;
                }
                else
                {
                    throw new customException('View : ' . $view . ' not found!');
                }
            }
            catch (customException $e)
            {
                echo $e->errorMessage();
            }

            extract($this->data, EXTR_PREFIX_SAME, "wddx");
            include($this->render);
        }
    }
}
