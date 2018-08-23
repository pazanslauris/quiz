<?php

namespace Quiz\Models;


class QuizModel extends BaseModel
{
    /** @var int */
    public $id;
    /** @var string */
    public $name;
    /** @var int */
    public $totalQuestionCount;

    public function __construct(int $id = 0, string $name = '', int $totalQuestionCount = 0)
    {
        $this->id = $id;
        $this->name = $name;
        $this->totalQuestionCount = $totalQuestionCount;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return ($this->id !== 0);
    }
}