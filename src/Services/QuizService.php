<?PHP

namespace Quiz\Services;

use Quiz\Interfaces\AnswerRepositoryInterface;
use Quiz\Interfaces\QuestionRepositoryInterface;
use Quiz\Interfaces\QuizRepositoryInterface;
use Quiz\Interfaces\QuizResultRepositoryInterface;
use Quiz\Interfaces\UserAnswerRepositoryInterface;
use Quiz\Interfaces\UserRepositoryInterface;
use Quiz\Models\AnswerModel;
use Quiz\Models\QuestionModel;
use Quiz\Models\QuizModel;
use Quiz\Models\ResultModel;
use Quiz\Models\UserAnswerModel;
use Quiz\Models\UserModel;

//TODO: refactor / split the service up

class QuizService
{
    /** @var QuizRepositoryInterface */
    private $quizzes;
    /** @var QuestionRepositoryInterface */
    private $questions;
    /** @var AnswerRepositoryInterface */
    private $answers;
    /** @var UserRepositoryInterface */
    private $users;
    /** @var UserAnswerRepositoryInterface */
    private $userAnswers;
    /** @var QuizResultRepositoryInterface */
    private $results;

    /**
     * QuizService constructor.
     * @param QuizRepositoryInterface $quizzes
     * @param QuestionRepositoryInterface $questions
     * @param AnswerRepositoryInterface $answers
     * @param UserRepositoryInterface $users
     * @param UserAnswerRepositoryInterface $userAnswers
     * @param QuizResultRepositoryInterface $results
     */
    public function __construct(
        QuizRepositoryInterface $quizzes,
        QuestionRepositoryInterface $questions,
        AnswerRepositoryInterface $answers,
        UserRepositoryInterface $users,
        UserAnswerRepositoryInterface $userAnswers,
        QuizResultRepositoryInterface $results
    ) {
        $this->quizzes = $quizzes;
        $this->questions = $questions;
        $this->answers = $answers;
        $this->users = $users;
        $this->userAnswers = $userAnswers;
        $this->results = $results;
    }

    /**
     * Gets user by id
     *
     * @param int $userId
     * @return UserModel
     */
    public function getUser(int $userId): UserModel
    {
        return $this->users->getUserById($userId);
    }

    /**
     * Gets all quizzes
     *
     * @return QuizModel[]
     */
    public function getAllQuizzes(): array
    {
        $allQuizzes = $this->quizzes->getQuizzes();
        return $allQuizzes;
    }

    /**
     * Gets all quizzes that are not yet completed
     *
     * @param int $userId
     * @return QuizModel[]
     */
    public function getAvailableQuizzes(int $userId): array
    {
        $allQuizzes = $this->quizzes->getQuizzes();
        $availableQuizzes = [];
        foreach ($allQuizzes as $quiz) {
            if (!$this->isQuizCompleted($userId, $quiz->id)) {
                $availableQuizzes[] = $quiz;
            }
        }
        return $availableQuizzes;
    }

    /**
     * Gets all questions from a quiz
     *
     * @param int $quizId
     * @return QuestionModel[]
     */
    public function getQuestions(int $quizId): array
    {
        return $this->questions->getQuestions($quizId);
    }

    /**
     * Gets a question by id
     *
     * @param int $quizId
     * @param int $questionNo
     * @return QuestionModel
     */
    public function getQuestionByNo(int $quizId, int $questionNo): QuestionModel
    {
        return $this->questions->getQuestionByNo($quizId, $questionNo);
    }

    /**
     * Gets all answers to a question
     *
     * @param int $questionId
     * @return AnswerModel[]
     */
    public function getAnswers(int $questionId): array
    {
        return $this->answers->getAnswers($questionId);
    }

    /**
     * Gets all user answers to a specific quiz
     *
     * @param int $userId
     * @param int $quizId
     * @return UserAnswerModel[]
     */
    public function getUserAnswers(int $userId, int $quizId): array
    {
        return $this->userAnswers->getAnswers($userId, $quizId);
    }

    /**
     * Gets user answer to a question
     *
     * @param int $userId
     * @param int $quizId
     * @param int $questionId
     * @return UserAnswerModel
     */
    public function getUserAnswerToQuestion(int $userId, int $quizId, int $questionId): UserAnswerModel
    {
        return $this->userAnswers->getAnswerToQuestion($userId, $quizId, $questionId);
    }

    /**
     * Gets the correct answer count form the supplied user answers.
     *
     * @param UserAnswerModel[] $userAnswers
     * @return int
     */
    public function getCorrectAnswerCount(array $userAnswers): int
    {
        $correctAnswers = 0;
        foreach ($userAnswers as $userAnswer) {
            if ($this->isUserAnswerCorrect($userAnswer)) {
                $correctAnswers++;
            }
        }
        return $correctAnswers;
    }

    /**
     * Returns the count of answered questions in a quiz.
     *
     * @param int $userId
     * @param int $quizId
     * @return int
     */
    public function getAnsweredQuestionCount(int $userId, int $quizId): int
    {
        $answeredQuestionCount = sizeof($this->userAnswers->getAnswers($userId, $quizId));
        return $answeredQuestionCount;
    }

    /**
     * Gets the next question
     *
     * Returns an invalid question if the quiz is complete.
     *
     * @param int $userId
     * @param int $quizId
     * @return QuestionModel
     */
    public function getNextQuestion(int $userId, int $quizId): QuestionModel
    {
        $currentQuestionNo = $this->getAnsweredQuestionCount($userId, $quizId);
        $nextQuestion = $this->getQuestionByNo($quizId, $currentQuestionNo + 1);

        return $nextQuestion;
    }

    /**
     * Gets quiz result
     *
     * @param int $userId
     * @param int $quizId
     * @return ResultModel
     */
    public function getResult(int $userId, int $quizId): ResultModel
    {
        $result = $this->results->getResult($userId, $quizId);
        return $result;
    }

    /**
     * Returns correct answers as a percentage
     *
     * @param int $userId
     * @param int $quizId
     * @return int 0-100
     */
    public function getScore(int $userId, int $quizId): int
    {
        $result = $this->getResult($userId, $quizId);

        //Prevent division by 0
        if ($result->totalAnswers == 0) {
            return 0;
        }

        return round($result->correctAnswers / $result->totalAnswers * 100);
    }

    /**
     * Edit and save a given quiz
     *
     * @param QuizModel $quiz
     * @return QuizModel
     */
    public function updateQuiz(QuizModel $quiz): QuizModel
    {
        return $this->quizzes->updateQuiz($quiz);
    }

    /**
     * Edit and save a given question
     *
     * @param QuestionModel $question
     * @return QuestionModel
     */
    public function updateQuestion(QuestionModel $question): QuestionModel
    {
        return $this->questions->updateQuestion($question);
    }

    /**
     * Edit and save a given answer
     *
     * @param AnswerModel $answer
     * @return AnswerModel
     */
    public function updateAnswer(AnswerModel $answer): AnswerModel
    {
        return $this->answers->updateAnswer($answer);
    }

    /**
     * Add a quiz
     *
     * @param QuizModel $quiz
     * @return QuizModel
     */
    public function addQuiz(QuizModel $quiz): QuizModel
    {
        return $this->quizzes->addQuiz($quiz);
    }

    /**
     * Add a question
     *
     * @param QuestionModel $question
     * @return QuestionModel
     */
    public function addQuestion(QuestionModel $question): QuestionModel
    {
        return $this->questions->addQuestion($question);
    }

    /**
     * Add an answer
     *
     * @param AnswerModel $answer
     * @return AnswerModel
     */
    public function addAnswer(AnswerModel $answer): AnswerModel
    {
        return $this->answers->addAnswer($answer);
    }

    /**
     * Deletes a quiz by its id
     *
     * @param QuizModel $quiz
     * @return bool
     */
    public function deleteQuiz(QuizModel $quiz): bool
    {
        $questions = $this->questions->getQuestions($quiz->id);
        foreach($questions as $question) {
            if (!$this->deleteQuestion($question)) {
                return false;
            }
        }
        return $this->quizzes->deleteQuiz($quiz);
    }

    /**
     * Deletes a question by its id and quiz id
     *
     * @param QuestionModel $question
     * @return bool
     */
    public function deleteQuestion(QuestionModel $question): bool
    {
        $answers = $this->answers->getAnswers($question->id);
        foreach($answers as $answer) {
            if (!$this->deleteAnswer($answer)) {
                return false;
            }
        }
        return $this->questions->deleteQuestion($question);
    }


    /**
     * Deletes an answer by its id and question id
     *
     * @param AnswerModel $answer
     * @return bool
     */
    public function deleteAnswer(AnswerModel $answer): bool
    {
        return $this->answers->deleteAnswer($answer);
    }

    /**
     * Helper function for getResult
     *
     * @param UserAnswerModel $userAnswer
     * @return bool
     */
    public function isUserAnswerCorrect(UserAnswerModel $userAnswer): bool
    {
        $answers = $this->answers->getAnswers($userAnswer->questionId);
        foreach ($answers as $answer) {
            if ($answer->id == $userAnswer->answerId) {
                return $answer->isCorrect;
            }
        }

        return false;
    }

    /**
     * Checks if user exists in the system
     *
     * @param int $userId
     * @return bool
     */
    public function isExistingUser(int $userId): bool
    {
        $existingUser = $this->users->getUserById($userId);
        return $existingUser->isValid();
    }

    /**
     * Checks if quiz exists in the system
     *
     * @param int $quizId
     * @return bool
     */
    public function isExistingQuiz(int $quizId): bool
    {
        $existingQuiz = $this->quizzes->getQuiz($quizId);
        return $existingQuiz->isValid();
    }

    /**
     * Checks if question exists in the system
     *
     * @param int $questionId
     * @return bool
     */
    public function isExistingQuestion(int $questionId): bool
    {
        $existingQuestion = $this->questions->getQuestion($questionId);
        return $existingQuestion->isValid();
    }

    /**
     * Checks if answer exists in the system
     *
     * @param int $answerId
     * @return bool
     */
    public function isExistingAnswer(int $answerId): bool
    {
        $existingAnswer = $this->answers->getAnswer($answerId);
        return $existingAnswer->isValid();
    }

    /**
     * Checks if a user has completed a given quiz
     *
     * @param int $userId
     * @param int $quizId
     * @return bool
     */
    public function isQuizCompleted(int $userId, int $quizId): bool
    {
        $result = $this->results->getResult($userId, $quizId);
        return $result->isValid();
    }

    /**
     * Checks if the answer is referencing real IDs
     *
     * @param UserAnswerModel $userAnswer
     * @return bool
     */
    public function isValidAnswer(UserAnswerModel $userAnswer): bool
    {
        if (!$this->isExistingUser($userAnswer->userId) ||
            !$this->isExistingQuiz($userAnswer->quizId) ||
            !$this->isExistingQuestion($userAnswer->questionId) ||
            !$this->isExistingAnswer($userAnswer->answerId)
        ) {
            //Invalid answer
            return false;
        }
        return true;
    }


    /**
     * Register a user
     *
     * @param string $name
     * @return UserModel
     */
    public function registerUser(string $name): UserModel
    {
        $user = new UserModel;
        $user->name = $name;

        return $this->users->saveOrCreate($user);
    }

    /**
     * Determines if this answer has already been submitted.
     *
     * @param int $userId
     * @param int $quizId
     * @param $questionId
     * @return bool
     */
    public function hasAnswerBeenSubmitted($userId, $quizId, $questionId): bool
    {
        $userAnswer = $this->getUserAnswerToQuestion($userId, $quizId, $questionId);

        return $userAnswer->isValid();
    }

    /**
     * Tries to submit an answer
     *
     * @param UserAnswerModel $userAnswer
     * @return UserAnswerModel
     */
    public function submitAnswer(UserAnswerModel $userAnswer): UserAnswerModel
    {
        if (!$this->isValidAnswer($userAnswer)) {
            //Answer is invalid...
            return new UserAnswerModel;
        }

        if ($this->hasAnswerBeenSubmitted($userAnswer->userId, $userAnswer->quizId, $userAnswer->questionId)) {
            //Answer has already been submitted...
            return new UserAnswerModel;
        }

        //Submit the answer...
        $this->userAnswers->saveAnswer($userAnswer);
        return $userAnswer;
    }

    /**
     * Calculates and submits a result from a quiz
     *
     * @param int $userId
     * @param int $quizId
     * @return ResultModel
     */
    public function submitResult(int $userId, int $quizId): ResultModel
    {
        $userAnswers = $this->userAnswers->getAnswers($userId, $quizId);
        $totalQuestionCount = sizeof($this->questions->getQuestions($quizId));

        if ($totalQuestionCount != sizeof($userAnswers)) {
            //Quiz isn't completed yet...
            return new ResultModel;
        }

        $result = new ResultModel;

        $result->totalAnswers = $totalQuestionCount;
        $result->correctAnswers = $this->getCorrectAnswerCount($userAnswers);
        $result->userId = $userId;
        $result->quizId = $quizId;

        return $this->results->saveResult($result);
    }
}