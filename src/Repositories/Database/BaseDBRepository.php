<?php

namespace Quiz\Repositories\Database;


use Quiz\Database\MysqlConnection;
use Quiz\Interfaces\ConnectionInterface;
use Quiz\Interfaces\DBRepositoryInterface;
use Quiz\Models\BaseModel;

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
     * Initializes a new model
     *
     * @param array $attributes
     * @return BaseModel based on the current repository
     */
    private function init(array $attributes)
    {
        $class = static::getModelName();
        /** @var BaseModel $instance */
        $instance = new $class;
        $instance->setAttributes($attributes);
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

    public function delete(array $attributes): bool
    {
        $table = static::getTableName();
        $primaryKey = static::getPrimaryKey();

        $result = static::getConnection()->delete($table, $primaryKey, $attributes);
        return $result;
    }

    public function save($model): int
    {
        //TODO: implement
        //fetch columns
        //  exclude primarykey
        //cameltosnake
        //insertRow
        //return id
    }
}