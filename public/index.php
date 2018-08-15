Hello world

<?php

use Quiz\Models\AnswerModel;

include '../vendor/autoload.php';

$quizRepo = new \Quiz\Repositories\QuizRepository;

$answers = [
    [1, 10, 'Rīga', true],
    [2, 10, 'Rēzekne', false],
    [3, 10, 'Ventspils', false],
    [4, 10, 'Viļāni', false],
];

foreach ($answers as $a) {
    $answer = new AnswerModel;
    $answer->id = $a[0];
    $answer->questionId = $a[1];
    $answer->answer = $a[2];
    $answer->isCorrect = $a[3];
    $quizRepo->addAnswer($answer);
}


$val = $quizRepo->getAnswers(10);

foreach ($val as $v) {
    if ($v->id == 2) {
        $v->answer = 'test';
    }
}

var_dump($val);

echo 'test\n\n\n\n';
$test = [1, 2, 3, 4];
var_dump($test);
$test[] = [5, 6, 7, 8];
var_dump($test);
