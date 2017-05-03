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
namespace Javamon\Jframe\Model;

use \Javamon\Jframe\Core\ORM as ORM;

/** 샘플
 *  ORM은 데이터베이스 작업을 위한 아름답고 간단한 액티브레코드 구현을 제공합니다.
 *  각 테이터베이스 테이블은 테이블과 상호작용 하는데 사용되는 해당 "모델"을 가지고 있습니다.
 *  테이블명 : users, 클래스명: user로 매핑되며 정적객체와 동적객체를 혼합한 형태로,
 *  사용하는 문법은 => User::ORM()->selectAll();
 *  참고문헌 : https://www.laravel.co.kr/docs/4.x/eloquent
 */
class User extends ORM
{
    /**
     *  부모의 내장된 변수나 함수를 커스터마이징하여 사용 할 수 있습니다.
     */
     public function query
                          (
                              $sql = ""
                          )
     {
         $prepared_sql = $this->db_connect->prepare($sql);
         $prepared_sql->execute() ? $prepared_sql : $this->abort_error(true , "Query ERROR");
         return $prepared_sql;
     }
}