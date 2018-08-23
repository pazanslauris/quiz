<?php

namespace Quiz\Repositories;

use Quiz\Interfaces\QuizRepositoryInterface;
use Quiz\Models\QuizModel;

class QuizRepository implements QuizRepositoryInterface
{
    /** @var QuizModel[] */
    private $quizzes = [];

    /**
     * Add a quiz, does not validate.
     *
     * @param QuizModel $quiz
     * @return QuizModel
     */
    public function addQuiz(QuizModel $quiz): QuizModel
    {
        $this->quizzes[] = $quiz;
        return $quiz;
    }

    /**
     * Gets all quizzes.
     *
     * @return QuizModel[]
     */
    public function getQuizzes(): array
    {
        return $this->quizzes;
    }

    /**
     * Gets a quiz by id.
     *
     * @param int $quizId
     * @return QuizModel
     */
    public function getQuiz(int $quizId): QuizModel
    {
        foreach ($this->quizzes as $quiz) {
            if ($quiz->id == $quizId) {
                return $quiz;
            }
        }
        return new QuizModel;
    }

    public function updateQuiz(QuizModel $newQuiz): QuizModel
    {
        foreach ($this->quizzes as $quiz) {
            if ($quiz->id == $newQuiz->id) {
                $quiz->name = $newQuiz->name;
                return $newQuiz;
            }
        }
        return new QuizModel;
    }

    public function deleteQuiz(QuizModel $oldQuiz): bool
    {
        foreach($this->quizzes as $key => $quiz) {
            if($quiz->id == $oldQuiz->id){
                unset($this->quizzes[$key]);
                return true;
            }
        }
        return false;
    }
}