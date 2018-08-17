<?php
/**
 * Created by PhpStorm.
 * User: Lauris
 * Date: 8/16/2018
 * Time: 3:23 PM
 */

namespace Quiz\Interfaces;


interface ConnectionInterface
{
    public function select(string $table, array $conditions = [], array $select = [], bool $selectOne = false): array;

    public function insert(string $table, string $primaryKey, array $attributes): int;

    public function update(string $table, string $primaryKey, array $attributes): bool;

    public function fetchColumns(string $table): array;
}