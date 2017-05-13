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

use \Javamon\Jframe\Core\Loader as Loader;

/**
 *  뷰 클래스 : 컨트롤러(프로세서로)부터 요청 받은 페이지 로드 및 데이터 연관 배열 선언
 */
class View
{
    private $data = Array();

    private $render = false;

    /**
     * @access public
     * @param Array $views : 요청받은 페이지
     * @param Array $input_data : 요청받은 데이터
     */
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
            //연관 배열 선언
            extract($this->data, EXTR_PREFIX_SAME, "wddx");

            //페이지 로드
            include($this->render);
        }
    }
}
