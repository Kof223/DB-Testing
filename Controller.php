<?php
use Gateway;
use SQLGateway;

class Controller {

    private $db;
    private $requestMethod;
    private $name;

    private $gateway;

    public function __construct($requestMethod, $name, $isSQLITE)
    {
        if ($isSQLITE) {
            $this->db = new SQLite3('./db/test.db', SQLITE3_OPEN_READWRITE);
            $this->gateway = new Gateway($this->conn);
        } else {
            $serverName = "localhost";
            $connectionOptions = array(
                "Database" => "testDB",
                "Uid" => "sa",
                "PWD" => "Password1234"
            );
            //Establishes the connection
            $this->db = sqlsrv_connect($serverName, $connectionOptions);
            $this->gateway = new SQLGateway($this->db);
        }
        $this->requestMethod = $requestMethod;
        $this->name = $name;
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->name) {
                    $response = $this->getUser($this->name);
                } else {
                    $response = $this->getAllUsers();
                };
                break;
            case 'POST':
                $response = $this->createUserFromRequest();
                break;
            case 'DELETE':
                $response = $this->deleteUser($this->name);
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function getAllUsers()
    {
        $result = $this->gateway->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        
        $response['body'] = "<h1>People Information</h1><p>";
        foreach ($result as $entry) {
            $response['body'] .= "<b>Name: </b>" . $entry["name"] . "&emsp;<b>Age: </b>" . $entry["age"] . "<br>";
        }
        $response['body'] .= "</p>";
        //$response['body'] = json_encode($result);
        return $response;
    }

    private function getUser($id)
    {
        $result = $this->gateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = "
            <h1>Person Information</h1>
            <p>
                Name: " . $result["name"] . "<br>Age: " . $result["age"] .
            "</p> 
        ";
        return $response;
    }

    private function createUserFromRequest()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validate($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->gateway->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = "<h1>Successfully Created</h1>";
        return $response;
    }

    private function deleteUser($id)
    {
        $result = $this->gateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $this->gateway->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = "<h1>Successfully Deleted</h1>";
        return $response;
    }

    private function validate($input)
    {
        if (! isset($input['name'])) {
            return false;
        }
        if (! isset($input['age'])) {
            return false;
        }
        return true;
    }

    private function unprocessableEntityResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}