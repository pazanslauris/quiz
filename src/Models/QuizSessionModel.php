<?php

namespace Quiz\Models;


class QuizSessionModel extends BaseModel
{
    /** @var int  */
    public $userId = 0;
    /** @var int */
    public $quizId = 0;
    /** @var QuestionModel  */
    public $question;
    /** @var bool */
    public $isAdmin = false;
}