<?php

namespace Quiz\Interfaces;


use Quiz\Models\QuestionModel;

interface QuestionRepositoryInterface
{
    public function addQuestion(QuestionModel $question): QuestionModel;

    public function getQuestions(int $quizId): array;

    public function getQuestion(int $questionId): QuestionModel;

    public function getQuestionByNo(int $quizId, int $questionNo): QuestionModel;
}