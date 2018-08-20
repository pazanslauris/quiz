<?php

namespace Quiz\Models;


class QuizModel extends BaseModel
{
    /** @var int */
    public $id;
    /** @var string */
    public $name;

    public function __construct(int $id = 0, string $name = '')
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return ($this->id !== 0);
    }
}