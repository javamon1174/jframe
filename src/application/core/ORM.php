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
*  역할 : ORM
*  Eloquent ORM 클래스(모델 클래스와 동일) : ORM모델들의 부모 클래스 - 데이터베이스 커넥터, 기본 동작 쿼리 함수 내장
*/
class ORM {

    /**
     * @access public
     * @var String $table : database table 이름
     */
    public static $table;

    /**
     * @access public
     * @var String $ORM_object : ORM 인스턴스
     */
    public static $ORM_object;

    /**
     * @access private
     * @var String $config : Config 클래스의 내장 데이터
     */
    private $config;

    /**
     * 데이터베이스 커넥트 및 커넥터 클래스 변수에 저장
     * @access public
     * @return Object $this->db_connect : 데이터베이스 커넥터 저장
     */
    public function __construct()
    {
        empty($this->config) ? $this->config = (new Config())->configure() : null;

        $options = array(
            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET GLOBAL max_allowed_packet=16777216;',
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            // \PDO::ATTR_EMULATE_PREPARES   => false,
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

    public function query ( $sql = "" )
    {
        try {
            $this->db_connect->beginTransaction();

            $prepared_sql = $this->db_connect->prepare($sql);
            $prepared_sql->execute() ? $prepared_sql : $this->abort_error(true , "Query ERROR");
            $this->db_connect->commit();

            return $prepared_sql;

        } catch (PDOException $e) {
            //add log write
            $this->db_connect->rollBack();
            return false;
        }
    }

    public function selectAll ( $select = '*' )
    {
        try {
            $table = static::$table;

            $this->db_connect->beginTransaction();

            $sql = "SELECT {$select} FROM {$table};";

            $prepared_sql = $this->db_connect->prepare($sql);
            $prepared_sql->execute();
            $this->db_connect->commit();

            return $prepared_sql;

        } catch (PDOException $e) {
            $this->db_connect->rollBack();
            return $this->abort_error(true , $e);
        }
    }

    public function insert
                            (
                                $insert_data   = []
                            )
    {
        try {
            $table = static::$table;

            //make query
            $sql        = (String) "INSERT INTO `{$table}` ";
            $sql_column = (String) "";
            $sql_value  = (String) "";

            $this->db_connect->beginTransaction();

            $sql .= "(";

            foreach ($insert_data as $column => $value) {
                //makes key data
                $key = ':'.$column;
                $data[$key] = $value;

                //makes sql selector for column
                $sql_column .= "`{$column}`, ";

                //makes for data binding to sql
                $sql_value .= " {$key},";
            }
            $sql_column = substr($sql_column, 0, -2);
            $sql_value  = substr($sql_value, 0, -1);

            $sql .= $sql_column;
            $sql .= ") VALUES (";
            $sql .= $sql_value;
            $sql .= ");";

            $prepared_sql = $this->db_connect->prepare($sql);
            $prepared_sql->execute($data);

            $this->db_connect->commit();

            return $this->db_connect->lastInsertId();

        } catch (PDOException $e) {
            $this->db_connect->rollBack();
            return $this->abort_error(true , $e);
        }
    }

    public function multiInsert(
                                    $column_arr = [],
                                    $value_arr = []
                                )
    {
        try {
            $table = static::$table;

            //make query
            $sql        = (String) "INSERT INTO `{$table}` ";
            $sql_column = (String) "";
            $sql_value  = (String) "";

            $this->db_connect->beginTransaction();

            $sql .= "(";
            foreach ($column_arr as $column => $value) {
                $sql_column .= "`{$value}`, ";
            }
            $sql_column = substr($sql_column, 0, -2);

            $sql .= $sql_column;
            $sql .= ") VALUES ";

            foreach ($value_arr as $column => $values) {
                $sql_value .= "(";
                foreach ($values as $key => $value) {
                    $values_arr[] = $value;
                    $sql_value .= "?,";
                }
                $sql_value = substr($sql_value, 0, -1);
                $sql_value .= "),";
            }
            $sql_value = substr($sql_value, 0, -2);

            $sql .= $sql_value;
            $sql .= ");";

            $prepared_sql = $this->db_connect->prepare($sql);
            $prepared_sql->execute($values_arr);

            $this->db_connect->commit();

            return $prepared_sql;

        } catch (PDOException $e) {
            $this->db_connect->rollBack();
            return $this->abort_error(true , $e);
        }
    }

    public function select
                            (
                                $select         = '*',
                                $where_column   = null,
                                $where_value    = null
                             )
    {
        try {
            $table = static::$table;

            $this->db_connect->beginTransaction();

            $sql = "SELECT {$select} FROM {$table} WHERE `{$table}`.`{$where_column}` = :value;";


            $prepared_sql = $this->db_connect->prepare($sql);

            $prepared_sql->execute(array(
                ":value" => $where_value,
            ));

            $this->db_connect->commit();

            return $prepared_sql;

        } catch (PDOException $e) {
            $this->db_connect->rollBack();
            return $this->abort_error(true , $e);
        }
    }

    public function update (
                                $update_colmn   = '',
                                $update_value   = '',
                                $where_column   = '',
                                $where_value    = ''
                            )
    {
        try {
            $table = static::$table;

            $this->db_connect->beginTransaction();

            $sql = "UPDATE `{$table}` SET `{$update_colmn}` = :update_value
                    WHERE `{$table}`.`{$where_column}` = :where_value;";

            $prepared_sql = $this->db_connect->prepare($sql);

            $prepared_sql->execute(array(
                ":update_value" => $update_value,
                ":where_value"  => $where_value,
            ));

            $this->db_connect->commit();

            return $prepared_sql;

        } catch (PDOException $e) {
            $this->db_connect->rollBack();
            return $this->abort_error(true , $e);
        }
    }


    public function delete
                          (
                            $where_column   = '',
                            $where_value    = ''
                          )
    {
        try {
            $table = static::$table;

            $this->db_connect->beginTransaction();

            $sql = "DELETE FROM `{$table}` WHERE `{$table}`.`{$where_column}` = ':where_value'";

            $prepared_sql = $this->db_connect->prepare($sql);

            $prepared_sql->execute(array(
                ":where_value" => $where_value,
            ));

            $this->db_connect->commit();

            return $prepared_sql;

        } catch (PDOException $e) {
            $this->db_connect->rollBack();
            return $this->abort_error(true , $e);
        }
    }

    public function distinct
                            (
                                $select         = '*',
                                $where_column   = null,
                                $where_value    = null
                             )
    {
        try {
            $table = static::$table;

            $this->db_connect->beginTransaction();

            $sql = "SELECT DISTINCT {$select} FROM `{$table}` WHERE `{$table}`.`{$where_column}`= :where_value;";

            $prepared_sql = $this->db_connect->prepare($sql);

            $prepared_sql->execute(array(
                ":where_value" => $where_value,
            ));

            $this->db_connect->commit();

            return $prepared_sql;

        } catch (PDOException $e) {
            $this->db_connect->rollBack();
            return $this->abort_error(true , $e);
        }
    }

    protected function abort_error
                                (
                                    $boolean    = true,
                                    $msg        = "Check Query"
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