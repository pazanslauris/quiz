<?php

namespace Quiz\Repositories\Database;


use Quiz\Database\MysqlConnection;
use Quiz\Interfaces\ConnectionInterface;
use Quiz\Interfaces\DBRepositoryInterface;

abstract class BaseDBRepository implements DBRepositoryInterface
{
    /** @var ConnectionInterface */
    protected static $connection;

    /**
     * @return ConnectionInterface
     */
    protected static function getConnection(): ConnectionInterface
    {
        if (!static::$connection) {
            static::$connection = new MysqlConnection(); //Use a factory?
        }

        return static::$connection;
    }

    /**
     * Convert snake_case to camelCase
     *
     * @param $name
     * @return mixed|string
     */
    private function snakeToCamel($name)
    {
        $exploded = explode('_', $name);
        $ret = array_shift($exploded);
        foreach ($exploded as $word) {
            $ret = $ret . ucfirst($word);
        }
        return $ret;
    }

    /**
     * Initializes a new model
     *
     * @param array $attributes
     * @return model based on the current repository
     */
    private function init(array $attributes)
    {
        $class = static::getModelName();
        $instance = new $class;

        //as $key => $value
        foreach ($attributes as $name => $value) {
            $name = $this->snakeToCamel($name);
            if (property_exists($class, $name)) {
                $instance->$name = htmlspecialchars($value);
            }
        }

        return $instance;
    }

    /**
     * Wrapper around init() to support arrays of arrays
     *
     * @param $result
     * @return array
     */
    private function initAttributes($result)
    {
        $ret = [];
        foreach ($result as $attribute) {
            $ret[] = $this->init($attribute);
        }
        return $ret;
    }

    /**
     * Gets a column by id and maps it to model
     *
     * @param int $id
     * @param string $select
     * @return mixed
     */
    public function getById(int $id, array $select = [])
    {
        $table = static::getTableName();

        $result = static::getConnection()->select($table, ['id' => $id], $select, true);
        if (!$result) {
            //Failed to fetch
            $class = static::getModelName();
            return new $class;
        }

        $objectArray = $this->initAttributes($result);
        return array_shift($objectArray);
    }

    /**
     * Returns objects from a query
     *
     * @param array $conditions
     * @param array $select
     * @return array
     */
    public function getByConditions(array $conditions, array $select = []): array
    {
        $table = static::getTableName();
        $result = static::getConnection()->select($table, $conditions, $select);
        if (!$result) {
            //Failed to fetch
            return [];
        }
        return $this->initAttributes($result);
    }

    /**
     * Fetches all columns and maps them to models
     *
     * @param array $select
     * @return array
     */
    public function getAll(array $select = [])
    {
        $table = static::getTableName();
        $result = static::getConnection()->select($table, [], $select);

        if (!$result) {
            //Failed to fetch
            return [];
        }

        return $this->initAttributes($result);
    }

    /**
     * @param array $attributes
     * @return int AUTO INCREMENT id
     */
    public function insertRow(array $attributes): int
    {
        $table = static::getTableName();
        $primaryKey = static::getPrimaryKey();

        $result = static::getConnection()->insert($table, $primaryKey, $attributes);
        return $result;
    }

    /**
     * The primary key must be passed in the attributes... [ 'primary_key' => 1 ]
     *
     * @param array $attributes
     * @return bool
     */
    public function updateColumn(array $attributes): bool
    {
        $table = static::getTableName();
        $primaryKey = static::getPrimaryKey();

        $result = static::getConnection()->update($table, $primaryKey, $attributes);
        return $result;
    }
}