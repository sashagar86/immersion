<?php

$user_id = (int)$_GET['id'];
$db = new QueryBuilder();

if (!$user_id) {
    Flash::setMessage('Неверно передан id пользователя');
    header("Location: /users"); exit;
}

$user = $db->getOne('users', $user_id);

if (empty($user)) {
    Flash::setMessage('Такого пользовтаеля не существует');
    header("Location: /users"); exit;
}

$login_user = get_login_user();
$is_owner = Validator::is_author($login_user, $user);


if (!Validator::is_admin() && !$is_owner) {
    Flash::setMessage('Вы можете удалить только свой профиль');
    header("Location: /users"); exit;
}

$db->delete('users', $user['id']);

if ($is_owner) {
    Flash::setMessage('Вы удалили свой профиль', 'success');
    logout();
    header("Location: /registration"); exit;
}

if (Validator::is_admin()) {
    Flash::setMessage("Вы удалили профиль {$user['fullname']}", 'success');
    header("Location: /users"); exit;
}

function get_login_user() {
    return $_SESSION['user'];
}

function logout() {
    unset($_SESSION['user']);
    header("Location: /login"); exit;
}