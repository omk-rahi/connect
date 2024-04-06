<?php

class Connection
{
    private $server_name;
    private $port;
    private $db_name;
    private $username;
    private $password;
    private $pdo;

    public function __construct($server_name, $port, $db_name, $username, $password)
    {
        $this->server_name = $server_name;
        $this->port = $port;
        $this->db_name = $db_name;
        $this->username = $username;
        $this->password = $password;

        try {
            $this->pdo = new PDO("mysql:host={$server_name};port={$port};dbname={$db_name}", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    public function save($table_name, $object)
    {
        $object_keys = array_keys($object);

        $object_keys2 = array_map(function ($key) {
            return ':' . $key;
        }, $object_keys);

        $column_names = implode(",", $object_keys);
        $column_names2 = implode(",", $object_keys2);


        $statement = $this->pdo->prepare("INSERT INTO {$table_name}({$column_names}) VALUES ({$column_names2})");

        $statement->execute($object);

        $result = $this->pdo->lastInsertId();

        return $result;
    }

    public function find($table_name, $options)
    {
        $column_names = array_keys($options);

        $condition_array = [];

        for ($i = 0; $i < count($column_names); $i++) {
            array_push($condition_array, "{$column_names[$i]} = :{$column_names[$i]}");
        }

        $condition = implode(" AND ", $condition_array);

        $statement = $this->pdo->prepare("SELECT * FROM {$table_name} WHERE {$condition}");

        $statement->execute($options);

        $result = $statement->fetchAll();

        return $result;
    }

    public function findById($table_name, $id)
    {
        $statement = $this->pdo->prepare("SELECT * FROM {$table_name} WHERE id = :id");

        $statement->execute(["id" => $id]);

        $result = $statement->fetch();

        return $result;
    }

    public function findAll($table_name)
    {
        $statement = $this->pdo->prepare("SELECT * FROM {$table_name}");

        $statement->execute();

        $result = $statement->fetchAll();

        return $result;
    }
}
