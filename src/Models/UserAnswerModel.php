<?php
/**
 * Created by PhpStorm.
 * User: Lauris
 * Date: 8/14/2018
 * Time: 10:15 AM
 */

namespace Quiz\Models;


class UserAnswerModel extends BaseModel
{
    /** @var int */
    public $id;

    /** @var int */
    public $userId;

    /** @var int */
    public $quizId;

    /** @var int */
    public $questionId;

    /** @var int */
    public $answerId;


    /**
     * UserAnswerModel constructor.
     * @param int $id
     * @param int $userId
     * @param int $quizId
     * @param int $questionId
     * @param int $answerId
     */
    public function __construct(int $id = 0, int $userId = 0, int $quizId = 0, int $questionId = 0, int $answerId = 0)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->quizId = $quizId;
        $this->questionId = $questionId;
        $this->answerId = $answerId;
    }

    /**
     * @return bool
     */
    public function isSubmitted():bool
    {
        return ($this->id !== 0);
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        if ($this->userId === 0 || $this->quizId === 0 ||
            $this->questionId === 0 || $this->answerId === 0) {
            return false;
        }
        return true;
    }
}