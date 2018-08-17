<?php
/**
 * @var UserModel $user
 */

use Quiz\Models\UserModel;

?>

<div class="container">
    <h1>Hello <?= $user->name ?></h1>
    <form id="user-form" method="POST" action="ajax/saveUser">
        <label for="name">Vārds</label>
        <input type="text" id="name" name="name">
        <button type="submit">Save</button>
    </form>
    <p id="response"></p>

    <button id="send-req" onclick="sendAjax('ajax/index')">Send Ajax/Index Request</button>
    <button id="send-req" onclick="sendAjax('QuizAjax/start')">Send QuizAjax/start</button>
    <button id="send-req" onclick="sendAjax('QuizAjax/loadNextQuestion')">Send QuizAjax/nextQuestion</button>
    <button id="send-req" onclick="sendAjax('QuizAjax/getQuizzes')">Send QuizAjax/getQuizzes</button>

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