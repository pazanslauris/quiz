<?php

namespace Quiz\Tests;

use PHPUnit\Framework\TestCase;
use Quiz\Models\AnswerModel;
use Quiz\Models\QuestionModel;
use Quiz\Models\QuizModel;
use Quiz\Repositories\QuizRepository;


class QuizRepositoryTest extends TestCase
{
    /** @var QuizModel[] */
    private $testQuizzes = [];
    /** @var QuestionModel[] */
    private $testQuestions = [];
    /** @var AnswerModel[] */
    private $testAnswers = [];

    /**
     * Fills $testQuizzes, $testQuestions, $testAnswers with test data...
     */
    public function setUp()
    {
        parent::setUp();
        $data = [
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


        $quizIds = 0;
        $questionIds = 0;
        $answerIds = 0;

        foreach ($data as $quizTitle => $questions) {
            $quiz = new QuizModel;
            $quiz->id = ++$quizIds;
            $quiz->name = $quizTitle;

            $this->testQuizzes[] = $quiz;

            foreach ($questions as $questionText => $answers) {
                $question = new QuestionModel;
                $question->quizId = $quiz->id;
                $question->id = ++$questionIds;
                $question->question = $questionText;

                $this->testQuestions[] = $question;

                foreach ($answers as $answerText => $isCorrect) {
                    $a = new AnswerModel;
                    $a->id = ++$answerIds;
                    $a->answer = $answerText;
                    $a->isCorrect = $isCorrect;
                    $a->questionId = $question->id;

                    $this->testAnswers[] = $a;
                }
            }
        }
    }

    public function testGetQuizzes()
    {
        $repo = new QuizRepository;

        $quiz1 = new QuizModel;
        $quiz1->id = 1;
        $quiz1->name = "first quiz";

        $quiz2 = new QuizModel;
        $quiz2->id = 2;
        $quiz2->name = "second quiz";

        $repo->addQuiz($quiz1);
        $repo->addQuiz($quiz2);

        $returnedQuizzes = $repo->getQuizzes();
        self::assertCount(2, $returnedQuizzes);
        self::assertEquals($quiz1, $returnedQuizzes[0]);
        self::assertEquals($quiz2, $returnedQuizzes[1]);
    }

    public function testGetQuiz()
    {
        $repo = new QuizRepository;
        $testQuiz = $this->testQuizzes[0];

        $repo->addQuiz($testQuiz);
        $retrievedQuiz = $repo->getQuiz($testQuiz->id);

        self::assertEquals($testQuiz, $retrievedQuiz);
    }

    public function testGetQuestions()
    {
        $repo = new QuizRepository;
        $quizId = $this->testQuizzes[0]->id;

        foreach ($this->testQuestions as $question) {
            $repo->addQuestion($question);
        }

        $returnedQuestions = $repo->getQuestions($quizId);

        self::assertCount(3, $returnedQuestions);
    }

    public function testGetQuestion()
    {
        $questionId = 20;
        $repo = new QuizRepository;
        $singleQuestion = new QuestionModel($questionId, 'test question', 1);
        //Add the single question
        $repo->addQuestion($singleQuestion);

        //Add other questions
        foreach ($this->testQuestions as $question) {
            $repo->addQuestion($question);
        }

        //Get the single question
        $returnedQuestion = $repo->getQuestion($questionId);
        self::assertEquals($singleQuestion, $returnedQuestion);
    }

    public function testGetAnswers()
    {
        $repo = new QuizRepository;

        //bulk add
        foreach ($this->testAnswers as $answer) {
            $repo->addAnswer($answer);
        }

        //4 answers for every question
        self::assertCount(4, $repo->getAnswers(1));
        self::assertCount(4, $repo->getAnswers(2));
        self::assertCount(4, $repo->getAnswers(3));
    }

    public function testGetAnswer()
    {
        $repo = new QuizRepository;

        //individual answer
        $singleAnswer = new AnswerModel;
        $singleAnswer->id = 70;
        $singleAnswer->questionId = 20;
        $singleAnswer->isCorrect = false;
        $singleAnswer->answer = "test";
        $repo->addAnswer($singleAnswer);

        //bulk add
        foreach ($this->testAnswers as $answer) {
            $repo->addAnswer($answer);
        }

        //check if we can still get the individual answer
        $returnedAnswer = $repo->getAnswer($singleAnswer->id);
        self::assertEquals($singleAnswer, $returnedAnswer);
    }
}