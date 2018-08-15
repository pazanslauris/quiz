<?php
/**
 * Created by PhpStorm.
 * User: Lauris
 * Date: 8/14/2018
 * Time: 10:12 AM
 */

namespace Quiz\Models;


class QuestionModel
{
    /** @var int */
    public $id;

    /** @var string */
    public $question;

    /** @var int */
    public $quizId;

    /**
     * QuestionModel constructor.
     * @param int $id
     * @param string $question
     * @param int $quizId
     */
    public function __construct(int $id = 0, string $question = '', int $quizId = 0)
    {
        $this->id = $id;
        $this->question = $question;
        $this->quizId = $quizId;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return ($this->id !== 0);
    }
}