<?php

namespace Quiz\Controllers;


use Quiz\Models\UserModel;

class IndexController extends BaseController
{
    public function IndexAction()
    {
        $user = new UserModel();
        $user->id = 1;
        $user->name = 'janis';
        return $this->render('index', compact('user'));
    }
}