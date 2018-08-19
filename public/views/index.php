<?php
/**
 * @var UserModel $user
 */

use Quiz\Models\UserModel;
?>

<div class="container">
    <h1>Hello <?= $user->name ?>, id: <?= $user->id ?></h1>
    <form id="user-form" method="POST" action="ajax/saveUser">
        <label for="name">Vārds</label>
        <input type="text" id="name" name="name">
        <button type="submit">Save</button>
    </form>
    <p id="response"></p>

    <button id="send-req" onclick="sendAjax('ajax/index')">Send Ajax/Index Request</button>
    <button id="send-req" onclick="sendAjax('QuizAjax/start')">Send QuizAjax/start</button>
    <button id="send-req" onclick="sendAjax('QuizAjax/submitAndLoadNextQuestion')">Send QuizAjax/nextQuestion</button>
    <button id="send-req" onclick="sendAjax('QuizAjax/getQuizzes')">Send QuizAjax/getQuizzes</button>
    <br>
    <br>
    <button id="send-req" onclick="startQuiz(1)">Send QuizAjax/startQuiz</button>
    <button id="send-req" onclick="getNextQuestion(1)">Send QuizAjax/nextQuestion</button>


    <h1>send test user data</h1>
    <form id="user-form" method="POST" action="ajax/saveUser">
        <label for="name">userid</label>
        <input type="text" id="userId" name="userId">
        <label for="name">quizid</label>
        <input type="text" id="quizId" name="quizId">
        <button type="submit">Send</button>
    </form>


</div>

<script>
    function sendAjax(action) {
        let xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                let resp = document.getElementById('response');
                resp.innerText = this.responseText;
            }
        };
        xhr.open('POST', action, true);
        xhr.send();
    }

    function startQuiz(id) {
        let xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                let resp = document.getElementById('response');
                resp.innerText = this.responseText;
            }
        };
        xhr.open('POST', 'QuizAjax/startQuiz', true);
        var data = new FormData;
        data.append('quizId', id);
        xhr.send(data);
    }

    function getNextQuestion(answerId) {
        let xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                let resp = document.getElementById('response');
                resp.innerText = this.responseText;
            }
        };
        xhr.open('POST', 'quizAjax/submitAndLoadNextQuestion', true);
        var data = new FormData;
        data.append('answerId', answerId);
        xhr.send(data);
    }


    (window.onload = function () {
        let form = document.getElementById('user-form');
        form.addEventListener('submit', function(evt) {
            evt.preventDefault();

            let formData = new FormData(form);

            let xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (this.readyState === 4 && this.status === 200) {
                    let resp = document.getElementById('response');
                    resp.innerText = this.responseText;
                }
            };
            xhr.open(form.method, form.action, true);
            xhr.send(formData);
        })
    })

</script>