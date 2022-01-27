<?php

$db = new \DB\QueryBuilder();

if (!empty($_POST)) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!App\Validator::isEmail($email) && !empty($email)) {
        App\Flash::setMessage
("Поле email заполнено не правильно.");
        $error = true;
    }

    $error = App\Validator::requiredFields(['email', 'password']);

    if ($error) {
        header("Location: /registration");
        exit;
    }

    $user = $db->getOne('users', $email, 'email');

    if (!empty($user)) {
        App\Flash::setMessage
( "Такой email уже используется в системе");
        header("Location: /registration"); exit;
    }

    $user_id = $db->create('users', ['email' => $email, 'password' => password_hash($password, PASSWORD_DEFAULT)]);

    if ($user_id) {
        App\Flash::setMessage
("Регистрация успешна", 'success');
        header("Location: /login"); exit;
    } else {
        App\Flash::setMessage
("Что-то пошло не так");
    }

    header("Location: /registration");

}