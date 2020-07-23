<?php

namespace database;

use app\Configuration;
use Mysqli;

class Database
{
    private static $instance = null;
    private $databaseName;
    private $hostName;
    private $userName;
    private $passCode;
    private $connection;

    private function __construct()
    {
        $config = new Configuration();
        $this->databaseName = $config->dbName;
        $this->hostName = $config->dbHost;
        $this->userName = $config->dbUserName;
        $this->passCode = $config->dbPassword;
        $this->connection = new MySQLi($this->hostName, $this->userName, $this->passCode, $this->databaseName);
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    public static function getInstance(): Database
    {
        if (!isset(self::$instance)) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    function selectAll($tableName): array
    {
        $result = array();
        $query = 'SELECT * FROM ' . $this->databaseName . '.' . $tableName;
        $data = $this->connection->query($query);
        if ($data->num_rows > 0) {
            while ($row = $data->fetch_assoc()) {
                $result[] = $row;
            }
        }
        return $result;
    }

    function selectWhere($tableName, $fields, $whereValues): array
    {
        $result = array();
        $fieldsQ = "";
        $countFields = 1;
        foreach ($fields as $field) {
            if ($countFields <= count($fields) && $countFields > 1) {
                $fieldsQ .= ",";
            }
            $fieldsQ .= " " . $field;
            $countFields++;
        }
        $query = 'SELECT ' . $fieldsQ . ' FROM ' . $this->databaseName . '.' . $tableName;
        if (count($whereValues) > 0) {
            $query .= " WHERE ";
            $counter = 1;
            foreach ($whereValues as $value) {
                $operation = isset($value["operation"]) ? $value["operation"] : "=";
                $query .= "{$value["name"]} {$operation} \"{$value["val"]}\"";
                if ($counter < count($whereValues)) {
                    $query .= ' AND ';
                }
                $counter++;
            }
        }
        $data = $this->connection->query($query);
        if ($data->num_rows > 0) {
            while ($row = $data->fetch_assoc()) {
                $result[] = $row;
            }
        }
        return $result;
    }

    function selectAllWhere($tableName, $whereValues): array
    {
        $result = array();
        $query = 'SELECT * FROM ' . $this->databaseName . '.' . $tableName;
        if (count($whereValues) > 0) {
            $query .= " WHERE ";
            $counter = 1;
            foreach ($whereValues as $value) {
                $operation = isset($value["operation"]) ? $value["operation"] : "=";
                $query .= "{$value["name"]} {$operation} \"{$value["val"]}\"";
                if ($counter < count($whereValues)) {
                    $query .= ' AND ';
                }
                $counter++;
            }
        }
        $data = $this->connection->query($query);
        if ($data->num_rows > 0) {
            while ($row = $data->fetch_assoc()) {
                $result[] = $row;
            }
        }
        return $result;
    }

    function deleteWhere($tableName, $whereValues): bool
    {
        $query = 'DELETE FROM ' . $this->databaseName . '.' . $tableName;
        if (count($whereValues) > 0) {
            $query .= " WHERE ";
            $counter = 1;
            foreach ($whereValues as $value) {
                $operation = isset($value["operation"]) ? $value["operation"] : "=";
                $query .= "{$value["name"]} {$operation} \"{$value["val"]}\"";
                if ($counter < count($whereValues)) {
                    $query .= ' AND ';
                }
                $counter++;
            }
        }
        $result = $this->connection->query($query);
        return $result;
    }

    function insertInto($tableName, $values, $fields = []): bool
    {
        $query = 'INSERT INTO ' . $tableName;
        if (count($fields)) {
            $query .= ' (';
            foreach ($fields as $field) {
                $query .= $field . ',';
            }
            $query .= ')';
        }
        $query .= ' VALUES (';
        $i = 0;
        while (isset($values[$i]["val"]) && isset($values[$i]["type"])) {
            if ($values[$i]["type"] == "char") {
                $query .= "'";
                $query .= $values[$i]["val"];
                $query .= "'";
            } else if ($values[$i]["type"] == 'int') {
                $query .= $values[$i]["val"];
            }
            $i++;
            $query .= ',';
        }
        $query .= ')';
        $query = str_replace(',)', ')', $query);
        $result = $this->connection->query($query);
        return $result;
    }

    public function update($tableName, $values, $id): bool
    {
        $set = '';
        $counter = 1;
        foreach ($values as $value) {
            $set .= "{$value["name"]} = \"{$value["val"]}\"";
            if ($counter < count($values)) {
                $set .= ',';
            }
            $counter++;
        }
        $query = "UPDATE {$tableName} SET {$set} WHERE id = {$id}";
        $result = $this->connection->query($query);
        return $result;
    }
}

