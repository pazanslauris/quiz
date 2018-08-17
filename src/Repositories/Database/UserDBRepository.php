<?php

namespace Quiz\Repositories\Database;


use Quiz\Interfaces\UserRepositoryInterface;
use Quiz\Models\UserModel;

class UserDBRepository extends BaseDBRepository implements UserRepositoryInterface
{

    public function saveOrCreate(UserModel $user): UserModel
    {
        $localUser = $this->getUserById($user->id);
        if ($localUser->isValid()) {
            //User already exists, update the name

            $this->updateColumn(['id' => $user->id, 'name' => $user->name]);
            return $user;
        }
        //Create a new user

        $id = $this->insertRow(['name' => $user->name]);
        $user->id = $id;
        return $user;
    }

    public function getUserById(int $userId): UserModel
    {
        $res = $this->getById($userId, ['id', 'name']);
        return $res;
    }

    public function getModelName(): string
    {
        return UserModel::class;
    }

    public function getTableName(): string
    {
        return 'users';
    }

    public function getPrimaryKey(): string
    {
        return 'id';
    }
}