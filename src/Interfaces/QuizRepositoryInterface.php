<?php

namespace Quiz\Interfaces;

use Quiz\Models\QuizModel;

interface QuizRepositoryInterface
{
    public function addQuiz(QuizModel $quiz): QuizModel;

    public function getQuizzes(): array;

    public function getQuiz(int $quizId): QuizModel;
}