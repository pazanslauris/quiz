<?php

namespace Quiz\Interfaces;

use Quiz\Models\QuizModel;

interface QuizRepositoryInterface
{
    public function addQuiz(QuizModel $quiz): QuizModel;

    public function getQuizzes(): array;

    public function getQuiz(int $quizId): QuizModel;

    public function updateQuiz(QuizModel $quiz): QuizModel;

    public function deleteQuiz(QuizModel $quiz): bool;
}