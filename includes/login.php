<?php

$flash = new \App\Flash();

if (!\App\Validator::is_not_looged_in()) {
    header('Location: /users');
}

if (!empty($_POST)) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $login = login($email, $password);

    if ($login) {
        \App\Flash::setMessage
("Здравствуйте $email", 'success');
        header('Location: /users'); exit;
    }

    \App\Flash::setMessage
("Поле email или пароль введены не корректно");
}

function login($email, $password) {
    $db = new \DB\QueryBuilder();
    $user = $db->getOne('users', $email, 'email');
    $check_password = password_verify($password, $user['password']);

    $login = !empty($user) && $check_password;

    if($login) {
        $_SESSION['user'] = $user;
    }

    return $login;
}

