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
 *  역할 : 쿼리 빌더
 *  모델 클래스 : 데이터베이스 커넥트 및 커넥터 저장/관리, 기본 동작 쿼리 함수 내장
 */
class Model
{

    protected $transactionCounter = 0;

    /**
     * @access private
     * @var String $db_connect : 데이터베이스 커넥터 저장
     */
    private $db_connect = "";

    /**
     * @access private
     * @var Array $config : 기본 설정값 저장
     */
    private $config = Array();

    /**
     * @access private
     * @var Array $table : 선택된 테이블
     */
    private $table = "";

    /**
     * 데이터베이스 커넥트 및 커넥터 클래스 변수에 저장
     * @access public
     * @return Object $this->db_connect : 데이터베이스 커넥터 저장
     */
     public function __construct()
     {
         if (empty($this->config))
         {
             $this->config = (new Config())->configure();
         }

         //PDO 기본 세팅
         $options = array(
             \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
             \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET GLOBAL max_allowed_packet=16777216;',
             \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
             \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC, //쿼리 실행 후 리턴 데이터 타입
             // PDO::ATTR_EMULATE_PREPARES   => false,
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
     * 내장 기능 함수 : 문자열 쿼리 실행
     * @access public
     * @param String $sql : sql query
     * @return Array $prepared_sql : 쿼리 실행 결과(데이터)
     */
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
            //add log write
            $this->db_connect->rollBack();
            return false;
        }
    }

    /**
     * 내장 기능 함수 : 문자열 쿼리 실행
     * @access public
     * @param String $table : 데이터베이스 테이블
     * @param String $select : 조회 조건
     * @return Array $prepared_sql : 쿼리 실행 결과(데이터)
     */
    public function selectAll
                            (
                                $table  = null,
                                $select = '*'
                            )
    {

        try {
            $this->db_connect->beginTransaction();

            $sql = "SELECT {$select} FROM `{$table}s`;";

            $prepared_sql = $this->db_connect->prepare($sql);
            $prepared_sql->execute();

            $this->db_connect->commit();

            return $prepared_sql;

        } catch (PDOException $e) {
            $this->db_connect->rollBack();
            return $this->abort_error(true , $e);
        }
    }

    /**
     * 내장 기능 함수 : 문자열 쿼리 실행
     * @access public
     * @param String $table : 데이터베이스 테이블
     * @param String $select : 조회 조건
     * @param String $where_column : 조회 조건
     * @param String $where_value : 조회 조건 값
     * @return Array $prepared_sql : 쿼리 실행 결과(데이터)
     */
    public function select
                            (
                                $table          = '',
                                $select         = '*',
                                $where_column   = null,
                                $where_value    = null
                             )
    {
        try {
            $this->db_connect->beginTransaction();

            $sql = "SELECT {$select} FROM `{$table}s` WHERE `{$where_column}` = :value;";

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

    public function multiInsert(
                                    $table = "",
                                    $column_arr = [],
                                    $value_arr = []
                                )
    {
        try {
            //make query
            $sql        = (String) "INSERT INTO `{$table}s` ";
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

    public function insert
                            (
                                $table         = '',
                                $insert_data   = []
                            )
    {
        try {
            //make query
            $sql        = (String) "INSERT INTO `{$table}s` ";
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

            return $prepared_sql;

        } catch (PDOException $e) {
            $this->db_connect->rollBack();
            return $this->abort_error(true , $e);
        }
    }

    public function update
                            (
                                $table          = '',
                                $update_colmn   = '',
                                $update_value   = '',
                                $where_column   = '',
                                $where_value    = ''
                            )
    {
        try {
            $this->db_connect->beginTransaction();

            $sql = "UPDATE `{$table}s` SET `{$update_colmn}` = :update_value
                    WHERE `{$table}s`.`{$where_column}` = :where_value;";

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
                            $table          = '',
                            $where_column   = '',
                            $where_value    = ''
                          )
    {

        try {
            $this->db_connect->beginTransaction();

            $sql = "DELETE FROM `{$table}s` WHERE `{$table}`.`{$where_column}` = ':where_value'";

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
                                $table          = '',
                                $select         = '*',
                                $where_column    = null,
                                $where_value    = null
                             )
    {
        try {
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

    public function join() { }

    /**
     * 인수&쿼리 에러 발생 함수
     * @access protected
     * @param String $boolean : 인수 전달 여부
     * @param String $msg : 에러 메세지
     */
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
