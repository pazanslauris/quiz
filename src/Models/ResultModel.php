<?php

namespace Quiz\Models;


class ResultModel extends BaseModel
{
    /** @var int */
    public $userId;
    /** @var int */
    public $quizId;
    /** @var int */
    public $correctAnswers;
    /** @var int */
    public $totalAnswers;

    public function __construct(int $userId = 0, int $quizId = 0, int $correctAnswers = 0, int $totalAnswers = 0)
    {
        $this->userId = $userId;
        $this->quizId = $quizId;
        $this->correctAnswers = $correctAnswers;
        $this->totalAnswers = $totalAnswers;
    }

    public function isValid()
    {
        return ($this->userId != 0 && $this->quizId != 0);
    }
}