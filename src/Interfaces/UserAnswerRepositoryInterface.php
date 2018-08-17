<?php

namespace Quiz\Interfaces;


use Quiz\Models\UserAnswerModel;

interface UserAnswerRepositoryInterface
{
    public function saveAnswer(UserAnswerModel $userAnswer): UserAnswerModel;

    public function getAnswers(int $userId, int $quizId): array;

    public function getAnswerToQuestion(int $userId, int $quizId, int $questionId): UserAnswerModel;
}