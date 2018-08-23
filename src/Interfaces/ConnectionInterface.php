<?php

namespace Quiz\Interfaces;


interface ConnectionInterface
{
    //TODO: split up to selectOne
    public function select(string $table, array $conditions = [], array $select = [], bool $selectOne = false): array;

    public function insert(string $table, string $primaryKey, array $attributes): int;

    public function update(string $table, string $primaryKey, array $attributes): bool;

    public function fetchColumns(string $table): array;

    public function delete(string $table, string $primaryKey, array $attributes): bool;
}