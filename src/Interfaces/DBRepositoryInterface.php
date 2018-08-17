<?php

namespace Quiz\Interfaces;


interface DBRepositoryInterface
{
    public function getModelName(): string;

    public function getTableName(): string;

    public function getPrimaryKey(): string;
}