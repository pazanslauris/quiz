<?php

namespace Quiz\Interfaces;


use Quiz\Models\ResultModel;

interface QuizResultRepositoryInterface
{
    public function saveResult(ResultModel $result): ResultModel;

    public function getResult(int $userId, int $quizId): ResultModel;
}