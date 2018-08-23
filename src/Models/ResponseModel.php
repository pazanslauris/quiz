<?php

namespace Quiz\Models;


class ResponseModel extends BaseModel
{
    const USER = 'user';
    const QUIZ = 'quiz';
    const QUIZZES = 'quizzes';
    const QUESTION = 'question';
    const QUESTIONS = 'questions';
    const ANSWER = 'answer';
    const RESULT = 'result';
    const STATUS = 'status';
    const ERRORMSG = 'errorMsg';

    /** @var int */
    public $type;

    /** @var array */
    public $response;

    /**
     * ResponseModel constructor.
     * @param string $type
     * @param $response
     */
    public function __construct(string $type, $response)
    {
        $this->type = $type;
        $this->response = $response;
}

    /**
     * @return bool
     */
    public function isValid()
    {
        return ($this->type !== '');
    }
}