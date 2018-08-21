<?php

namespace Quiz\Controllers;


use Quiz\Models\ResponseModel;
use Quiz\Services\QuizSessionService;

class UserAjaxController extends BaseAjaxController
{

    /**
     * Registers a new user, logs out the old one
     *
     * @return ResponseModel
     */
    public function registerAction()
    {
        if (!isset($this->post['name'])) {
            return new ResponseModel('errorMsg', "no name");
        }
        $session = QuizSessionService::getSession();
        $user = $this->quizService->registerUser($this->post['name']);
        $session->userId = $user->id;
        return new ResponseModel(ResponseModel::USER, $user);
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

        if($user->isValid()) {
            return new ResponseModel(ResponseModel::USER, $user);
        } else {
            return new ResponseModel( ResponseModel::ERRORMSG, "not logged in");
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