<?php

namespace Quiz\Services;


use Quiz\Models\QuestionModel;
use Quiz\Models\QuizSessionModel;

class QuizSessionService
{
    public static function newSession(): QuizSessionModel
    {
        if (isset($_SESSION)) {
            session_unset();
        } else {
            session_start();
        }
        $sessionModel = new QuizSessionModel();
        $sessionModel->question = new QuestionModel();
        $_SESSION['sessionModel'] = $sessionModel;
        return $sessionModel;
    }

    public static function endSession()
    {
        session_unset();
    }

    public static function getSession(): QuizSessionModel
    {
        if (!isset($_SESSION)) {
            //Session isn't set...
            session_start();
            if (!isset($_SESSION['sessionModel'])) {
                return static::newSession();
            }
        }
        return $_SESSION['sessionModel'];
    }

    public static function saveSession(QuizSessionModel $sessionModel)
    {
        $_SESSION['sessionModel'] = $sessionModel;
    }
}