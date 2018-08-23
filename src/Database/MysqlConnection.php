<?php

namespace Quiz\Database;


use PDO;
use Quiz\Interfaces\ConnectionInterface;

class MysqlConnection implements ConnectionInterface
{
    /** @var PDO */
    protected $connection;

    /**
     * MysqlConnection constructor.
     */
    public function __construct()
    {
        $this->connect();
    }

    /**
     * @return PDO
     */
    public function connect()
    {
        //TODO: create a config
        $dsn = 'mysql:host=192.168.10.10;charset=utf8;dbname=quiz_database';
        $this->connection = new PDO($dsn, 'homestead', 'secret');

        return $this->connection;
    }

    /**
     * @param array $conditions
     * @return string
     */
    public function whereConditionBuilder(array $conditions): string
    {
        $conditionSql = "";
        if ($conditions) {
            $conditionStatements = []; /* Condition statements: id = ?, name = ?*/
            foreach ($conditions as $attribute => $value) {
                $conditionStatements[] = implode(' = ', [$attribute, '?']);
            }

            $conditionSql = ' WHERE ' . implode(' AND ', $conditionStatements);
        }
        return $conditionSql;
    }

    /**
     * @param array $select
     * @return string
     */
    public function selectBuilder(array $select)
    {
        $selectString = '*';
        if ($select) {
            $selectString = implode(', ', $select);
        }
        return $selectString;
    }

    /**
     * @param string $table
     * @param array $conditions [ 'id' => 1, 'name' => 'Janis' ]
     * @param array $select [ 'id', 'name' ]
     * @return array
     */
    public function select(string $table, array $conditions = [], array $select = [], bool $selectOne = false): array
    {
        $sqlSelect = $this->selectBuilder($select);
        $sqlCondition = $this->whereConditionBuilder($conditions);

        $sql = "SELECT " . $sqlSelect . " FROM " . $table . $sqlCondition;

        if ($selectOne) {
            $sql = $sql . " LIMIT 1";
        }

        $statement = $this->connection->prepare($sql);
        $statement->execute(array_values($conditions));

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        if (!$result) {
            //fetchAll failed
            return [];
        }
        return $result;
    }

    /**
     * @param string $table
     * @param string $primaryKey
     * @param array $attributes
     * @return int
     */
    public function insert(string $table, string $primaryKey, array $attributes): int
    {
        $sqlAttributes = implode(", ", array_keys($attributes));
        $sqlValues = implode(', ', array_fill(0, count($attributes), '?'));

        $sql = "INSERT INTO " . $table . "(" . $sqlAttributes . ")" . " VALUE(" . $sqlValues . ")";

        $statement = $this->connection->prepare($sql);
        $statement->execute(array_values($attributes));
        $id = $this->connection->lastInsertId();
        return $id;
    }
    
    /**
     * The primary key must be passed in the attributes...
     *
     * @param string $table
     * @param string $primaryKey
     * @param array $attributes
     * @return bool
     */
    public function update(string $table, string $primaryKey, array $attributes): bool
    {
        $sqlCondition = "WHERE " . $primaryKey . " = " . $attributes[$primaryKey];
        unset($attributes[$primaryKey]);

        $sqlAttributes = [];
        foreach ($attributes as $attribute => $value) {
            $sqlAttributes[] = "`" . $attribute . "`=? ";
        }
        $sql = "UPDATE " . $table . " SET " . implode(", ", $sqlAttributes) . $sqlCondition;
        $statement = $this->connection->prepare($sql);
        if (!$statement->execute(array_values($attributes))) {
            return false;
        }
        return ($statement->rowCount() > 0);
    }

    /**
     * Delete a row
     *
     * @param string $table
     * @param string $primaryKey
     * @param array $attributes
     * @return bool
     */
    public function delete(string $table, string $primaryKey, array $attributes): bool
    {
        //Don't allow a wildcard
        if (sizeof($attributes) == 0) {
            return false;
        }

        $sql = "DELETE FROM " . $table . $this->whereConditionBuilder($attributes);
        $statement = $this->connection->prepare($sql);
        if (!$statement->execute(array_values($attributes))) {
            return false;
        }
        return ($statement->rowCount() > 0);
    }

    /**
     * Fetches columns
     *
     * @param string $table
     * @return array
     */
    public function fetchColumns(string $table): array
    {
        $sql = "DESCRIBE " . $table;
        $statement = $this->connection->prepare($sql);
        $statement->execute();
        $columns = $statement->fetchAll();
        return $columns;
    }
}