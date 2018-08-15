<?php

namespace Quiz\Tests;

use PHPUnit\Framework\TestCase;
use Quiz\Models\UserAnswerModel;
use Quiz\Repositories\UserAnswerRepository;


class UserAnswerRepositoryTest extends TestCase
{
    public function testGetUserAnswers()
    {
        $userId = 1;
        $quizId = 1;
        $repo = new UserAnswerRepository;

        //Save 2 answers to repo
        $userAnswer1 = new UserAnswerModel(1, $userId, $quizId, 1, 1);
        $userAnswer2 = new UserAnswerModel(2, $userId, $quizId, 5, 20);
        $repo->saveAnswer($userAnswer1);
        $repo->saveAnswer($userAnswer2);

        //Retrieve them
        $savedAnswers = $repo->getAnswers($userId, $quizId);

        //Assert
        self::assertCount(2, $savedAnswers);
        self::assertEquals($userAnswer1, $savedAnswers[0]);
        self::assertEquals($userAnswer2, $savedAnswers[1]);
    }

    public function testGetUserAnswerToQuestion() {
        $userId = 1;
        $quizId = 1;
        $repo = new UserAnswerRepository;

        //Save 3 answers to repo
        $userAnswer1 = new UserAnswerModel(1, $userId, $quizId, 1, 1);
        $userAnswer2 = new UserAnswerModel(2, $userId, $quizId, 5, 20);
        $userAnswer3 = new UserAnswerModel(3, $userId, $quizId, 6, 25);
        $repo->saveAnswer($userAnswer1);
        $repo->saveAnswer($userAnswer2);
        $repo->saveAnswer($userAnswer3);

        //Retrieve them
        $receivedAnswer1 = $repo->getAnswerToQuestion($userId, $quizId, 1);
        $receivedAnswer2 = $repo->getAnswerToQuestion($userId, $quizId, 5);
        $receivedAnswer3 = $repo->getAnswerToQuestion($userId, $quizId, 6);

        //Assert
        self::assertEquals($userAnswer1, $receivedAnswer1);
        self::assertEquals($userAnswer2, $receivedAnswer2);
        self::assertEquals($userAnswer3, $receivedAnswer3);
    }
}