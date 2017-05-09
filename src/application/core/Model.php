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
 *  모델 클래스 : 데이터베이스 커넥터, 기본 동작 쿼리 함수 내장
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
     * 데이터베이스 커넥터 클래스 변수에 저장
     * @access public
     * @return Object $this->db_connect : 데이터베이스 커넥터 저장
     */
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
                                $table = null,
                                $select = '*'
                            )
    {

        try {
            $this->db_connect->beginTransaction();

            $this->abort_error(empty($table), "No arguments were passed to selectAll it.");
            $sql = "SELECT {$select} FROM {$table};";

            $prepared_sql = $this->db_connect->prepare($sql);

            $prepared_sql->execute() ? $prepared_sql : $this->abort_error(true , "SELECT ERORROR - Check Query");
            $this->db_connect->commit();

            return $prepared_sql;

        } catch (PDOException $e) {
            $this->db_connect->rollBack();
            return false;
        }
    }

    /**
     * 내장 기능 함수 : 문자열 쿼리 실행
     * @access public
     * @param String $table : 데이터베이스 테이블
     * @param String $select : 조회 조건
     * @param String $where_colmn : 조회 조건
     * @param String $where_value : 조회 조건 값
     * @return Array $prepared_sql : 쿼리 실행 결과(데이터)
     */
    public function select
                            (
                                $table = '',
                                $select = '*',
                                $where_colmn = null,
                                $where_value = null
                             )
    {
        try {
            $this->db_connect->beginTransaction();

            $this->abort_error(empty($where_colmn || $where_value), "No arguments were passed to select it.");

            $sql = "SELECT {$select} FROM {$table} WHERE `{$table}`.`{$where_colmn}`='{$where_value}';";

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
                                $table = '',
                                $update_colmn = '',
                                $update_value = '',
                                $where_colmn = '',
                                $where_value = ''
                            )
    {
        try {
            $this->db_connect->beginTransaction();

            $this->abort_error(empty($where_colmn || $where_value), 'No arguments were passed to update it.');

            $sql = "UPDATE `{$table}` SET `{$update_colmn}` = '{$update_value}'
                    WHERE `{$table}`.`{$where_colmn}` = {$where_value};";

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
                            $table = '',
                            $where_colmn = '',
                            $where_value = ''
                          )
    {

        try {
            $this->db_connect->beginTransaction();

            $this->abort_error(empty($where_colmn || $where_value), 'No arguments were passed to update it.');

            $sql = "DELETE FROM `{$table}` WHERE `{$table}`.`{$where_colmn}` = '{$where_value}'";

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
                                $table = '',
                                $select = '*',
                                $where_colmn = null,
                                $where_value = null
                             )
    {
        try {
            $this->db_connect->beginTransaction();

            $this->abort_error(empty($where_colmn || $where_value), "No arguments were passed to select it.");

            $sql = "SELECT DISTINCT {$select} FROM {$table} WHERE `{$table}`.`{$where_colmn}`='{$where_value}';";

            $prepared_sql = $this->db_connect->prepare($sql);
            $prepared_sql->execute() ? $prepared_sql : $this->abort_error(true , "SELECT DISTINCT ERORROR - Check Query.");
            $this->db_connect->commit();

            return $prepared_sql;

        } catch (PDOException $e) {
            $this->db_connect->rollBack();
            return false;
        }
    }

    public function join() { }

    /**
     * 인수&쿼리 에러 발생 함수
     * @access protected
     * @param String $boolean : 인수 전달 여부
     * @param String $msg : 에러 메세지
     * @return Void : 에러 발생했는지 결과
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
