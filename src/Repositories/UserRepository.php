<?php
/**
 * Created by PhpStorm.
 * User: Lauris
 * Date: 8/14/2018
 * Time: 10:19 AM
 */

namespace Quiz\Repositories;

use Quiz\Models\UserModel;

class UserRepository
{
    /** @var UserModel[] */
    private $users = [];

    /** @var int */
    private $userIds = 0;

    /**
     * Creates or updates a user.
     *
     * @param UserModel $user
     * @return UserModel
     */
    public function saveOrCreate(UserModel $user): UserModel
    {
        $existingUser = $this->getById($user->id);

        if ($existingUser->isNew()) {
            $this->userIds++;
            $existingUser->id = $this->userIds;

        }
        $existingUser->name = $user->name;

        $this->users[$existingUser->id] = $existingUser;

        return $existingUser;
    }

    /**
     * Gets a user by id.
     *
     * @param int $userId
     * @return UserModel
     */
    public function getById(int $userId): UserModel
    {
        if (isset($this->users[$userId])) {
            return $this->users[$userId];
        }
        return new UserModel;
    }
}