<?PHP

namespace Quiz;

use Quiz\Models\AnswerModel;
use Quiz\Models\QuestionModel;
use Quiz\Models\QuizModel;
use Quiz\Models\ResultModel;
use Quiz\Models\UserAnswerModel;
use Quiz\Models\UserModel;
use Quiz\Repositories\QuizRepository;
use Quiz\Repositories\UserAnswerRepository;
use Quiz\Repositories\UserRepository;

class QuizService
{
    /** @var QuizRepository */
    private $quizzes;
    /** @var UserRepository */
    private $users;
    /** @var UserAnswerRepository */
    private $userAnswers;

    /** @var int */
    private $userAnswerId = 1;

    /**
     * QuizService constructor.
     * @param QuizRepository $quizzes
     * @param UserRepository $users
     * @param UserAnswerRepository $userAnswers
     */
    public function __construct(
        QuizRepository $quizzes,
        UserRepository $users,
        UserAnswerRepository $userAnswers
    ) {
        $this->quizzes = $quizzes;
        $this->users = $users;
        $this->userAnswers = $userAnswers;
    }

    /**
     * Gets all quizzes
     *
     * @return QuizModel[]
     */
    public function getQuizzes(): array
    {
        return $this->quizzes->getQuizzes();
    }

    /**
     * Gets all questions from a quiz
     *
     * @param int $quizId
     * @return QuestionModel[]
     */
    public function getQuestions(int $quizId): array
    {
        return $this->quizzes->getQuestions($quizId);
    }

    /**
     * Gets all answers to a question
     *
     * @param int $questionId
     * @return AnswerModel[]
     */
    public function getAnswers(int $questionId): array
    {
        return $this->quizzes->getAnswers($questionId);
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
     * Gets quiz result
     *
     * @param int $userId
     * @param int $quizId
     * @return ResultModel
     */
    public function getResult(int $userId, int $quizId): ResultModel
    {
        $result = new ResultModel;
        $userAnswers = $this->userAnswers->getAnswers($userId, $quizId);

        $correctAnswers = 0;
        foreach ($userAnswers as $userAnswer) {
            if ($this->isUserAnswerCorrect($userAnswer)) {
                $correctAnswers++;
            }
        }

        $result->totalAnswers = sizeof($userAnswers);
        $result->correctAnswers = $correctAnswers;
        $result->user = $this->users->getById($userId);
        $result->quizId = $quizId;

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
        if ($result->totalAnswers === 0) {
            return 0;
        }

        return round($result->correctAnswers / $result->totalAnswers * 100);
    }


    /**
     * Helper function for getResult
     *
     * @param UserAnswerModel $userAnswer
     * @return bool
     */
    public function isUserAnswerCorrect(UserAnswerModel $userAnswer): bool
    {
        $answers = $this->quizzes->getAnswers($userAnswer->questionId);
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
        $existingUser = $this->users->getById($userId);
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
        $existingQuestion = $this->quizzes->getQuestion($questionId);
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
        $existingAnswer = $this->quizzes->getAnswer($answerId);
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
        $questionCount = sizeof($this->quizzes->getQuestions($quizId));

        $answeredQuestionCount = sizeof($this->userAnswers->getAnswers($userId, $quizId));

        return ($questionCount === $answeredQuestionCount);
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
        $userAnswer->id = $this->userAnswerId++;
        $this->userAnswers->saveAnswer($userAnswer);
        return $userAnswer;
    }

}