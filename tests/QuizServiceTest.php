<?php

namespace Quiz\Tests;

use PHPUnit\Framework\TestCase;
use Quiz\Models\AnswerModel;
use Quiz\Models\QuestionModel;
use Quiz\Models\QuizModel;
use Quiz\Models\UserAnswerModel;
use Quiz\Models\UserModel;
use Quiz\Services\QuizService;
use Quiz\Repositories\AnswerRepository;
use Quiz\Repositories\QuestionRepository;
use Quiz\Repositories\QuizRepository;
use Quiz\Repositories\UserAnswerRepository;
use Quiz\Repositories\UserRepository;


class QuizServiceTest extends TestCase
{

    /** @var QuizService */
    public $service;

    /** @var UserAnswerModel[] */
    public $testUserAnswers = [];

    /** @var UserModel */
    public $user;

    /** @var int */
    public $quizId;


    private $data = [
        'Country capitals' => [
            'Latvia' => [
                'Riga' => true,
                'Ventspils' => false,
                'Jurmala' => false,
                'Daugavpils' => false,
            ],
            'Lithuania' => [
                'Kaunas' => false,
                'Siaulia' => false,
                'Vilnius' => true,
                'Mazeikiai' => false,
            ],
            'Estonia' => [
                'Talling' => true,
                'Paarnu' => false,
                'Tartu' => false,
                'Valga' => false,
            ],
        ],
    ];

    /**
     * Creates a QuizRepository and loads it with some test data.
     *
     * @return QuizRepository
     */
    private function initTestQuizRepo(): QuizRepository
    {
        $repo = new QuizRepository;
        $quizIds = 0;

        foreach ($this->data as $quizTitle => $questions) {
            $quiz = new QuizModel;
            $quiz->id = ++$quizIds;
            $quiz->name = $quizTitle;

            $repo->addQuiz($quiz);
        }

        return $repo;
    }

    /**
     * Creates a QuestionRepository and loads it with some test data.
     *
     * @return QuestionRepository
     */
    private function initTestQuestionRepo(): QuestionRepository
    {
        $repo = new QuestionRepository();
        $quizIds = 0;
        $questionIds = 0;

        foreach ($this->data as $quizTitle => $questions) {
            ++$quizIds;
            foreach ($questions as $questionText => $answers) {
                $question = new QuestionModel;
                $question->quizId = $quizIds;
                $question->id = ++$questionIds;
                $question->question = $questionText;

                $repo->addQuestion($question);
            }
        }

        return $repo;
    }

    /**
     * Creates an AnswerRepository and loads it with some test data.
     *
     * @return AnswerRepository
     */
    private function initTestAnswerRepo(): AnswerRepository
    {
        $repo = new AnswerRepository();

        $quizIds = 0;
        $questionIds = 0;
        $answerIds = 0;

        foreach ($this->data as $quizTitle => $questions) {
            ++$quizIds;
            foreach ($questions as $questionText => $answers) {
                ++$questionIds;
                foreach ($answers as $answerText => $isCorrect) {
                    $a = new AnswerModel;
                    $a->id = ++$answerIds;
                    $a->answer = $answerText;
                    $a->isCorrect = $isCorrect;
                    $a->questionId = $questionIds;

                    $repo->addAnswer($a);
                }
            }
        }

        return $repo;
    }

    private function initTestUserAnswers()
    {
        //Submit 3 user answers
        $userAnswer = new UserAnswerModel(0, $this->user->id, $this->quizId);
        $userAnswer->questionId = 1;
        $userAnswer->answerId = 1;
        $submittedAnswer = $this->service->submitAnswer($userAnswer); //correct(riga)
        $this->testUserAnswers[] = $submittedAnswer;

        $userAnswer = new UserAnswerModel(0, $this->user->id, $this->quizId);
        $userAnswer->questionId = 2;
        $userAnswer->answerId = 6;
        $submittedAnswer = $this->service->submitAnswer($userAnswer); //incorrect(Siaulia)
        $this->testUserAnswers[] = $submittedAnswer;


        $userAnswer = new UserAnswerModel(0, $this->user->id, $this->quizId);
        $userAnswer->questionId = 3;
        $userAnswer->answerId = 9;
        $submittedAnswer = $this->service->submitAnswer($userAnswer); //correct(talling)
        $this->testUserAnswers[] = $submittedAnswer;
    }

    public function setUp()
    {
        parent::setUp();

        $quizRepo = $this->initTestQuizRepo();
        $questionRepo = $this->initTestQuestionRepo();
        $answerRepo = $this->initTestAnswerRepo();
        $this->service = new QuizService($quizRepo, $questionRepo, $answerRepo, new UserRepository, new UserAnswerRepository);
        $this->user = $this->service->registerUser('test user');

        $this->quizId = 1;
    }

    public function testGetQuizzes()
    {
        $service = $this->service;

        //There is 1 quiz in the test data.
        $quizzes = $service->getQuizzes();
        self::assertCount(1, $quizzes);
    }

    public function testGetQuestions()
    {
        $service = $this->service;
        $quizId = $this->quizId;

        //There are 3 questions in the 1st quiz.
        $questions = $service->getQuestions($quizId);
        self::assertCount(3, $questions);
    }

    public function testGetAnswers()
    {
        $service = $this->service;
        $questionId = 1;

        //There are 4 answers in every question.
        $answers = $service->getAnswers($questionId);
        self::assertCount(4, $answers);
    }

    public function testGetUserAnswers()
    {
        $service = $this->service;
        $userId = $this->user->id;
        $quizId = $this->quizId;

        $this->initTestUserAnswers();

        $userAnswers = $service->getUserAnswers($userId, $quizId);
        self::assertCount(3, $userAnswers);
    }

    public function testGetUserAnswerToQuestion()
    {
        $service = $this->service;
        $userId = $this->user->id;
        $quizId = $this->quizId;

        $this->initTestUserAnswers();
        $expectedAnswer = $this->testUserAnswers[1];

        //Get answer to question #2
        $requestedUserAnswer = $service->getUserAnswerToQuestion($userId, $quizId, 2);
        self::assertEquals($expectedAnswer, $requestedUserAnswer);
    }

    public function testHasAnswerBeenSubmitted()
    {
        $service = $this->service;
        $userId = $this->user->id;
        $quizId = $this->quizId;

        $testUserAnswer = new UserAnswerModel(0, $userId, $quizId, 1, 1);

        $isSubmitted = $service->hasAnswerBeenSubmitted($testUserAnswer->userId,
            $testUserAnswer->questionId,
            $testUserAnswer->answerId);
        self::assertFalse($isSubmitted);

        //Submit the answer
        $service->submitAnswer($testUserAnswer);

        $isSubmitted = $service->hasAnswerBeenSubmitted($testUserAnswer->userId,
            $testUserAnswer->questionId,
            $testUserAnswer->answerId);
        self::assertTrue($isSubmitted);
    }

    public function testRepeatedAnswer()
    {
        $service = $this->service;
        $userId = $this->user->id;
        $quizId = $this->quizId;

        $testUserAnswer = new UserAnswerModel(0, $userId, $quizId, 1, 1);

        //Submit an answer once
        $submittedAnswer = $service->submitAnswer($testUserAnswer);
        self::assertTrue($submittedAnswer->isValid());

        //Resubmit the same answer
        $submittedAnswer = $service->submitAnswer($testUserAnswer);
        self::assertFalse($submittedAnswer->isValid());
    }

    public function testInvalidAnswer()
    {
        $service = $this->service;
        $userId = $this->user->id;
        $quizId = $this->quizId;

        //Create 5 user answers
        $invalidUser = new UserAnswerModel(0, 20, $quizId, 1, 1);
        $invalidQuiz = new UserAnswerModel(0, $userId, 200, 1, 1);
        $invalidQuestion = new UserAnswerModel(0, $userId, $quizId, 200, 1);
        $invalidAnswer = new UserAnswerModel(0, $userId, $quizId, 1, 200);
        $validAnswer = new UserAnswerModel(0, $userId, $quizId, 1, 1);

        //Assert
        self::assertFalse($service->isValidAnswer($invalidUser));
        self::assertFalse($service->isValidAnswer($invalidQuiz));
        self::assertFalse($service->isValidAnswer($invalidQuestion));
        self::assertFalse($service->isValidAnswer($invalidAnswer));
        self::assertTrue($service->isValidAnswer($validAnswer));
    }

    public function testIsAnswerCorrect()
    {
        $service = $this->service;

        $this->initTestUserAnswers();

        //Assert
        self::assertTrue($service->isUserAnswerCorrect($this->testUserAnswers[0]));
        self::assertFalse($service->isUserAnswerCorrect($this->testUserAnswers[1]));
        self::assertTrue($service->isUserAnswerCorrect($this->testUserAnswers[2]));

    }

    public function testResults()
    {
        $service = $this->service;
        $userId = $this->user->id;
        $quizId = $this->quizId;

        $this->initTestUserAnswers();

        //Assert
        $result = $service->getResult($userId, $quizId);
        self::assertEquals(2, $result->correctAnswers);
        self::assertEquals(3, $result->totalAnswers);
    }

    public function testScore()
    {
        $service = $this->service;
        $userId = $this->user->id;
        $quizId = $this->quizId;

        $this->initTestUserAnswers();

        //Assert
        $score = $service->getScore($userId, $quizId);
        $expectedScore = round((2 / 3) * 100);
        self::assertEquals($expectedScore, $score);
    }

    public function testExistingUser()
    {
        $service = $this->service;

        $existingUser = $service->registerUser('existing user');

        $unknownUser = new UserModel;
        $unknownUser->id = 10;
        $unknownUser->name = 'unknown user';

        self::assertTrue($service->isExistingUser($existingUser->id));
        self::assertFalse($service->isExistingUser($unknownUser->id));
    }

    public function testQuizCompleted()
    {
        $service = $this->service;
        $userId = $this->user->id;
        $quizId = $this->quizId;

        //No answers are submitted yet.
        self::assertFalse($service->isQuizCompleted($userId, $quizId));

        //In quiz no. 1 there are 3 questions.

        //1/3 complete
        $userAnswer = new UserAnswerModel(0, $userId, $quizId);
        $userAnswer->questionId = 1;
        $userAnswer->answerId = 1;
        $service->submitAnswer($userAnswer); //correct(riga)
        self::assertFalse($service->isQuizCompleted($userId, $quizId));

        //2/3 complete
        $userAnswer = new UserAnswerModel(0, $userId, $quizId);
        $userAnswer->questionId = 2;
        $userAnswer->answerId = 6;
        $service->submitAnswer($userAnswer); //incorrect(Siaulia)
        self::assertFalse($service->isQuizCompleted($userId, $quizId));

        //3/3 complete
        $userAnswer = new UserAnswerModel(0, $userId, $quizId);
        $userAnswer->questionId = 3;
        $userAnswer->answerId = 9;
        $service->submitAnswer($userAnswer); //correct(talling)
        self::assertTrue($service->isQuizCompleted($userId, $quizId));
    }
}