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

use \Javamon\Jframe\Core\Config as Config;

/**
*  Eloquent ORM 클래스(모델 클래스와 동일) : ORM모델들의 부모 클래스 - 데이터베이스 커넥터, 기본 동작 쿼리 함수 내장
*/
class ORM {

    public static $table;
    public static $ORM_object;
    private $config;

    public function __construct()
    {
        empty($this->config) ? $this->config = (new Config())->configure() : false;

        $options = array(
            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET GLOBAL max_allowed_packet=16777216;',
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            // PDO::ATTR_EMULATE_PREPARES   => false,
            //Please add here mysql setting
        );

        $this->db_connect = new \PDO(
                                        'mysql:host='.$this->config["database"]["host"].';
                                        port='.$this->config["database"]["port"].';
                                        dbname='.$this->config["database"]["db"].';
                                        charset=utf8mb4',
                                        $this->config["database"]["user"],
                                        $this->config["database"]["password"], $options
                                    );
    }

    /**
     * ORM 기본 세팅 -> 현재 클래스와 데이터베이스의 테이블 이름 매핑 및 클래스의 객체 리턴
     * @access public
     * @return Object static::$ORM_object : ORM 클래스 객체
     */
    public static function ORM()
    {
        $class = '\\'.get_called_class();
        $static_class = explode('\\', get_called_class());

        static::$table = strtolower(array_pop($static_class).'s');
        static::$ORM_object = new $class();

        return static::$ORM_object;
    }

    public function query
                         (
                             $sql = ""
                         )
    {
        try {
            $this->db_connect->beginTransaction();

            $prepared_sql = $this->db_connect->prepare($sql);
            $prepared_sql->execute() ? $prepared_sql : $this->abort_error(true , "Query ERROR");
            $this->db_connect->commit();

            return $prepared_sql;

        } catch (PDOException $e) {
            $this->db_connect->rollBack();
            return false;
        }
    }

    public function selectAll ( $select = '*' )
    {
        try {
            $this->db_connect->beginTransaction();

            $sql = "SELECT {$select} FROM ".static::$table.";";

            $prepared_sql = $this->db_connect->prepare($sql);
            $prepared_sql->execute() ? $prepared_sql : $this->abort_error(true , "SELECT ERORROR - Check Query");
            $this->db_connect->commit();

            return $prepared_sql;

        } catch (PDOException $e) {
            $this->db_connect->rollBack();
            return false;
        }
    }

    public function select
                            (
                                $select = '*',
                                $where_colmn = null,
                                $where_value = null
                             )
    {
        try {
            $this->db_connect->beginTransaction();

            $this->abort_error(empty($where_colmn || $where_value), "No arguments were passed to select it.");

            $sql = "SELECT {$select} FROM ".static::$table." WHERE `".static::$table."`.`{$where_colmn}`='{$where_value}';";

            $prepared_sql = $this->db_connect->prepare($sql);
            $prepared_sql->execute() ? $prepared_sql : $this->abort_error(true , "SELECT ERORROR - Check Query");
            $this->db_connect->commit();

            return $prepared_sql;

        } catch (PDOException $e) {
            $this->db_connect->rollBack();
            return false;
        }

    }


    public function update
                            (
                                $update_colmn = '',
                                $update_value = '',
                                $where_colmn = '',
                                $where_value = ''
                            )
    {
        try {
            $this->db_connect->beginTransaction();

            $this->abort_error(empty($where_colmn || $where_value), 'No arguments were passed to update it.');

            $sql = "UPDATE `".static::$table."` SET `{$update_colmn}` = '{$update_value}'
                    WHERE `".static::$table."`.`{$where_colmn}` = {$where_value};";

            $prepared_sql = $this->db_connect->prepare($sql);
            $prepared_sql->execute() ? $prepared_sql : $this->abort_error(true , "UPDATE ERORROR - Check Query.");
            $this->db_connect->commit();

            return $prepared_sql;

        } catch (PDOException $e) {
            $this->db_connect->rollBack();
            return false;
        }
    }


    public function delete
                          (
                            $where_colmn = '',
                            $where_value = ''
                          )
    {
        try {
            $this->db_connect->beginTransaction();

            $sql = "DELETE FROM `".static::$table."` WHERE `".static::$table."`.`{$where_colmn}` = '{$where_value}'";

            $prepared_sql = $this->db_connect->prepare($sql);
            $prepared_sql->execute() ? $prepared_sql : $this->abort_error(true , "DELETE ERORROR - Check Query.");
            $this->db_connect->commit();

            return $prepared_sql;

        } catch (PDOException $e) {
            $this->db_connect->rollBack();
            return false;
        }
    }

    public function distinct
                            (
                                $select = '*',
                                $where_colmn = null,
                                $where_value = null
                             )
    {
        try {
            $this->db_connect->beginTransaction();

            $this->abort_error(empty($where_colmn || $where_value), "No arguments were passed to select it.");

            $sql = "SELECT DISTINCT {$select} FROM ".static::$table." WHERE `".static::$table."`.`{$where_colmn}`='{$where_value}';";

            $prepared_sql = $this->db_connect->prepare($sql);
            $prepared_sql->execute() ? $prepared_sql : $this->abort_error(true , "SELECT ERORROR - Check Query");
            $this->db_connect->commit();

            return $prepared_sql;

        } catch (PDOException $e) {
            $this->db_connect->rollBack();
            return false;
        }
    }

    protected function abort_error
                                (
                                    $boolean = true,
                                    $msg = "Check Query"
                                )
    {
        if ( $boolean )
        {
            //add logfile
            trigger_error($msg, E_USER_ERROR);
            return false;
        }
        else {
            return true;
        }
    }
}