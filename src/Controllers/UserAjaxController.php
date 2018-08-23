<?php

namespace Quiz\Controllers;


use Quiz\Models\QuestionModel;
use Quiz\Models\ResponseModel;
use Quiz\Models\UserModel;
use Quiz\Services\QuizSessionService;

class UserAjaxController extends BaseAjaxController
{
    const MIN_NAME_LENGTH = 3;
    /**
     * Registers a new user, logs out the old one
     *
     * @return ResponseModel
     */
    public function registerAction()
    {
        if (!isset($this->post['name'])) {
            return new ResponseModel(ResponseModel::ERRORMSG, "No name was supplied");
        }
        $name = $this->post['name'];
        if (strlen($name) < 3) {
            return new ResponseModel( ResponseModel::ERRORMSG, "Name is too short");
        }

        $session = QuizSessionService::getSession();
        $user = $this->quizService->registerUser($name);
        $session->userId = $user->id;
        return new ResponseModel(ResponseModel::USER, $user);
    }

    public function loginAction()
    {
        if (!isset($this->post['name'])) {
            return new ResponseModel( ResponseModel::ERRORMSG, "No name was supplied");
        }
        if (!isset($this->post['password'])) {
            return new ResponseModel( ResponseModel::ERRORMSG, "No password was supplied");
        }

        //TODO: admins should be saved in the database not hardcoded here
        $name = $this->post['name'];
        $password = $this->post['password'];
        if ($name !== "admin" || $password !== "secret") {
            return new ResponseModel( ResponseModel::ERRORMSG, "Username and/or password is incorrect");
        }

        //Need to create a dummy user in order to pass isValid() checks
        $user = new UserModel;
        $user->id = 1;  //UserId 1 is hardcoded to the admin in the database
        $user->name = $name;
        $user->isAdmin = true;

        $session = QuizSessionService::getSession();
        $session->userId = 1;
        $session->quizId = 0;
        $session->question = new QuestionModel();
        $session->isAdmin = true;

        return new ResponseModel( ResponseModel::USER, $user);
    }

    /**
     * Returns the currently logged in user
     *
     * @return ResponseModel
     */
    public function getUserAction()
    {
        $session = QuizSessionService::getSession();
        $user = $this->quizService->getUser($session->userId);
        $user->isAdmin = $session->isAdmin; //TODO: should have an isAdmin column in the database

        if($user->isValid()) {
            return new ResponseModel(ResponseModel::USER, $user);
        } else {
            return new ResponseModel( ResponseModel::ERRORMSG, "Not logged in");
        }
    }

    /**
     * Logs a user out...
     *
     * @return ResponseModel
     */
    public function logoutAction()
    {
        QuizSessionService::endSession();
        return new ResponseModel(ResponseModel::STATUS, true);
    }
}