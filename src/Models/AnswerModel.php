<?php

namespace Quiz\Models;


class AnswerModel extends BaseModel
{
    /** @var int */
    public $id;

    /** @var string */
    public $answer;

    /** @var int */
    public $questionId;

    /** @var bool */
    public $isCorrect; //should be made private to prevent accidental leaks.


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

    /**
     * Strips sensitive data
     * @return $this
     */
    public function jsonSerialize()
    {
        $answer = clone $this;
        unset($answer->isCorrect);
        return (array)$answer;
    }
}