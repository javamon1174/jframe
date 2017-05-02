<?php
namespace Javamon\Jframe;

use \Javamon\Jframe\Core\Loader as Loader;

class Route {

    static public function getRequest()
    {
        $segment = filter_input(INPUT_GET, "url");

        $url_array = array();
        $url_array = explode("/",$segment);

        $load = new loader();
        $load->init($url_array);
    }
}