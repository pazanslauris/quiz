<?php

namespace Quiz\Models;


class QuestionModel extends BaseModel
{
    /** @var int */
    public $id;

    /** @var string */
    public $question;

    /** @var int */
    public $quizId;

    /** @var int */
    public $questionNo;

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