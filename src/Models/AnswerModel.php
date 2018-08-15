<?php
/**
 * Created by PhpStorm.
 * User: Lauris
 * Date: 8/14/2018
 * Time: 10:13 AM
 */

namespace Quiz\Models;


class AnswerModel
{
    /** @var int */
    public $id;

    /** @var string */
    public $answer;

    /** @var int */
    public $questionId;

    /** @var bool */
    public $isCorrect;


    /**
     * AnswerModel constructor.
     * @param int $id
     * @param string $answer
     * @param int $questionId
     * @param bool $isCorrect
     */
    public function __construct(int $id = 0, string $answer = '', int $questionId = 0, bool $isCorrect = false)
    {
        $this->id = $id;
        $this->answer = $answer;
        $this->questionId = $questionId;
        $this->isCorrect = $isCorrect;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return ($this->id !== 0);
    }
}