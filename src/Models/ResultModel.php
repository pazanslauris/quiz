<?php
/**
 * Created by PhpStorm.
 * User: Lauris
 * Date: 8/14/2018
 * Time: 3:00 PM
 */

namespace Quiz\Models;


class ResultModel
{
    /** @var int */
    public $totalAnswers;
    /** @var int */
    public $correctAnswers;
    /** @var UserModel */
    public $user;
    /** @var int */
    public $quizId;

    public function __construct(int $totalAnswers = 0, int $correctAnswers = 0, UserModel $user = null, int $quizId = 0)
    {
        $this->totalAnswers = $totalAnswers;
        $this->correctAnswers = $correctAnswers;
        $this->user = $user;
        $this->quizId = $quizId;
    }
}