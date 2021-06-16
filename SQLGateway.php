<?php

class SQLGateway {

    private $conn = null;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function findAll()
    {
        $statement = "SELECT * FROM data;";

        try {
            $statement = sqlsrv_query($this->conn, $statement);

            $list = array();
            while ($result = sqlsrv_fetch_array($statement, SQLSRV_FETCH_ASSOC)) {
                $temp = array();
                $temp["name"] = $result["name"];
                $temp["age"] = $result["age"];
                array_push($list, $temp);
            }
            return $list;
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }

    public function find($name)
    {
        $statement = "SELECT * FROM data WHERE name = ?";

        try {
            $statement = sqlsrv_prepare($this->conn, $statement, array($name));
            sqlsrv_execute($statement);
            return sqlsrv_fetch_array($statement, SQLSRV_FETCH_ASSOC);
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }

    public function insert(Array $input)
    {
        $statement = "INSERT INTO data (name, age) VALUES (?, ?);";
        $input = array($input["name"], $input["age"]);
        try {
            $statement = sqlsrv_prepare($this->conn, $statement, $input);
            sqlsrv_execute($statement);
            return sqlsrv_rows_affected($statement);
        } catch (Exception $e) {
            exit($e->getMessage());
        }    
    }

    public function delete($name)
    {
        $statement = "DELETE FROM data WHERE name = ?;";

        try {
            $statement = sqlsrv_prepare($this->conn, $statement, array($name));
            sqlsrv_execute($statement);
            return sqlsrv_rows_affected($statement);
        } catch (Exception $e) {
            exit($e->getMessage());
        }    
    }
}
