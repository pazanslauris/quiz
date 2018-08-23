<?php

namespace Quiz\Interfaces;


use Quiz\Models\AnswerModel;

interface AnswerRepositoryInterface
{
    public function addAnswer(AnswerModel $answer);

    public function getAnswers(int $questionId): array;

    public function getAnswer(int $answerId): AnswerModel;

    public function updateAnswer(AnswerModel $answer): AnswerModel;

    public function deleteAnswer(AnswerModel $answer): bool;
}