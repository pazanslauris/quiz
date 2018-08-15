<?php
/**
 * Created by PhpStorm.
 * User: Lauris
 * Date: 8/14/2018
 * Time: 10:21 AM
 */

namespace Quiz\Repositories;


use Quiz\Models\UserAnswerModel;

class UserAnswerRepository
{
    /** @var UserAnswerModel[] */
    private $userAnswers = [];

    /**
     * Saves an answer, does not validate.
     *
     * @param UserAnswerModel $userAnswer
     */
    public function saveAnswer(UserAnswerModel $userAnswer)
    {
        $this->userAnswers[] = $userAnswer;
    }

    /**
     * Returns all answers that match the criteria.
     *
     * @param int $userId
     * @param int $quizId
     * @return UserAnswerModel[]
     */
    public function getAnswers(int $userId, int $quizId): array
    {
        $ret = [];
        foreach ($this->userAnswers as $answerModel) {
            if ($answerModel->userId == $userId && $answerModel->quizId == $quizId) {
                $ret[] = $answerModel;
            }
        }
        return $ret;
    }

    /**
     * Returns a single answer that matches the criteria.
     *
     * @param int $userId
     * @param int $quizId
     * @param int $questionId
     * @return UserAnswerModel
     */
    public function getAnswerToQuestion(int $userId, int $quizId, int $questionId): UserAnswerModel
    {
        //Filter by user and quiz id
        $userAnswers = $this->getAnswers($userId, $quizId);

        //There can be only 1 answer to a question...
        foreach ($userAnswers as $answer) {
            if ($answer->questionId == $questionId) {
                return $answer;
            }
        }
        return new UserAnswerModel;
    }
}