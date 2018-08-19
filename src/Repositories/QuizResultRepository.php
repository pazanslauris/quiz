<?php
/**
 * Created by PhpStorm.
 * User: Lauris
 * Date: 8/19/2018
 * Time: 7:56 PM
 */

namespace Quiz\Repositories;


use Quiz\Interfaces\QuizResultRepositoryInterface;
use Quiz\Models\ResultModel;

class QuizResultRepository implements QuizResultRepositoryInterface
{

    /** @var ResultModel[] */
    private $results = [];

    public function saveResult(ResultModel $result): ResultModel
    {
        $this->results[] = $result;
        return $result;
    }

    public function getResult(int $userId, int $quizId): ResultModel
    {
        foreach ($this->results as $result) {
            if ($result->userId == $userId &&
                $result->quizId == $quizId) {
                return $result;
            }
        }
        return new ResultModel;
    }
}