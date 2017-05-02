<?php
namespace Javamon\Jframe\Core;

use \Javamon\Jframe\Core\Config as Config;

class Model {

    private $db_connect;
    private $config;

    public function __construct()
    {
        empty($this->config) ? $this->config = (new Config())->configure() : false;

        $this->db_connect = new \PDO(
                                        'mysql:host='.$this->config["database"]["host"].';
                                        port='.$this->config["database"]["port"].';
                                        dbname='.$this->config["database"]["db"].';
                                        charset=utf8mb4',
                                        $this->config["database"]["user"],
                                        $this->config["database"]["password"]
                                    );
    }

    public function query
                         (
                             $sql = ""
                         )
    {
        $prepared_sql = $this->db_connect->prepare($sql);
        $prepared_sql->execute() ? $prepared_sql : $this->abort_error(true , "Query ERROR");
        return $prepared_sql;
    }

    public function selectAll
                            (
                                $table = null,
                                $select = '*'
                            )
    {
        $this->abort_error(empty($table), "No arguments were passed to selectAll it.");
        $sql = "SELECT {$select} FROM {$table};";

        $prepared_sql = $this->db_connect->prepare($sql);
        $prepared_sql->execute() ? $prepared_sql : $this->abort_error(true , "SELECT ERORROR - Check Query");

        return $prepared_sql;
    }

    public function select
                            (
                                $table = '',
                                $select = '*',
                                $where_colmn = null,
                                $where_value = null
                             )
    {
        $this->abort_error(empty($where_colmn || $where_value), "No arguments were passed to select it.");

        $sql = "SELECT {$select} FROM {$table} WHERE `{$table}`.`{$where_colmn}`='{$where_value}';";

        $prepared_sql = $this->db_connect->prepare($sql);
        $prepared_sql->execute() ? $prepared_sql : $this->abort_error(true , "SELECT ERORROR - Check Query");
        return $prepared_sql;
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
        $this->abort_error(empty($where_colmn || $where_value), 'No arguments were passed to update it.');

        $sql = "UPDATE `{$table}` SET `{$update_colmn}` = '{$update_value}'
                WHERE `{$table}`.`{$where_colmn}` = {$where_value};";

        $prepared_sql = $this->db_connect->prepare($sql);
        $prepared_sql->execute() ? $prepared_sql : $this->abort_error(true , "UPDATE ERORROR - Check Query.");
        return $prepared_sql;
    }

    public function delete
                          (
                            $table = '',
                            $where_colmn = '',
                            $where_value = ''
                          )
    {
        $sql = "DELETE FROM `{$table}` WHERE `{$table}`.`{$where_colmn}` = '{$where_value}'";

        $prepared_sql = $this->db_connect->prepare($sql);
        $prepared_sql->execute() ? $prepared_sql : $this->abort_error(true , "DELETE ERORROR - Check Query.");
        return $prepared_sql;
    }

    public function distinct
                            (
                                $table = '',
                                $select = '*',
                                $where_colmn = null,
                                $where_value = null
                             )
    {
        $this->abort_error(empty($where_colmn || $where_value), "No arguments were passed to select it.");

        $sql = "SELECT DISTINCT {$select} FROM {$table} WHERE `{$table}`.`{$where_colmn}`='{$where_value}';";

        $prepared_sql = $this->db_connect->prepare($sql);
        $prepared_sql->execute() ? $prepared_sql : $this->abort_error(true , "SELECT DISTINCT ERORROR - Check Query.");
        return $prepared_sql;
    }

    public function join() { }

    private function abort_error
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
