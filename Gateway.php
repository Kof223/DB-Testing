<?php

class Gateway {

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function findAll()
    {
        $statement = "SELECT * FROM data;";

        try {
            $statement = $this->db->query($statement);

            $list = array();
            while ($result = $statement->fetchArray(SQLITE3_ASSOC)) {
                $temp = array();
                $temp["name"] = $result["name"];
                $temp["age"] = $result["age"];
                array_push($list, $temp);
            }
            return $list;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function find($name)
    {
        $statement = "SELECT * FROM data WHERE name = :name;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindValue(':name', $name, SQLITE3_TEXT);
            $result = $statement->execute();
            return $result->fetchArray(SQLITE3_ASSOC);
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function insert(Array $input)
    {
        $statement = "INSERT INTO data (name, age) VALUES (:name, :age);";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindValue(':name', $input["name"], SQLITE3_TEXT);
            $statement->bindValue(':age', $input["age"], SQLITE3_INTEGER);
            $statement->execute();
            return $this->db->changes();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function delete($name)
    {
        $statement = "DELETE FROM data WHERE name = :name;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindValue(':name', $name, SQLITE3_TEXT);
            $statement->execute();
            return $this->db->changes();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }
}
