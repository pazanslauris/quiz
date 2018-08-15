<?php

namespace Quiz\Tests;

use PHPUnit\Framework\TestCase;
use Quiz\Models\UserModel;
use Quiz\Repositories\UserRepository;


class UserTest extends TestCase
{
    /** @var UserRepository */
    private $userRepository;

    public function setUp()
    {
        parent::setUp();

        $this->userRepository = new UserRepository;
    }

    //UserRepository
    public function testUserCreation()
    {
        $user = new UserModel();
        $user->name = "Ilze";

        $createdUser = $this->userRepository->saveOrCreate($user);
        self::assertNotEquals($user->id, $createdUser->id);
    }

    public function testNameEdit()
    {
        $user = new UserModel(0, 'Ilze');

        $createdUser = $this->userRepository->saveOrCreate($user);
        $user2 = new UserModel($createdUser->id, 'Maksis');

        $editedUser = $this->userRepository->saveOrCreate($user2); //edit name to Maksis

        self::assertNotEquals($user->name, $editedUser->name);
        self::assertEquals($createdUser->id, $editedUser->id);
    }
}