<?php

namespace Quiz\Interfaces;


use Quiz\Models\UserModel;

interface UserRepositoryInterface
{
    public function saveOrCreate(UserModel $user): UserModel;

    public function getUserById(int $userId): UserModel;
}