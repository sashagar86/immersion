<?php

session_start();
require $_SERVER['DOCUMENT_ROOT'] . "/includes/functions.php";

$user_id = (int)$_GET['id'];

if (!$user_id) {
    set_flash_message('Неверно передан id пользователя');
    redirect_to('users.php');
}

$user = get_user_by_id($user_id);

if (empty($user)) {
    set_flash_message('Такого пользовтаеля не существует');
    redirect_to('users.php?id=' . $user_id);
}

$login_user = get_login_user();
$is_owner = is_author($login_user, $user);


if (!is_admin() && !$is_owner) {
    set_flash_message('Вы можете удалить только свой профиль');
    redirect_to('users.php');
}

remove_user($user);

if ($is_owner) {
    set_flash_message('Вы удалили свой профиль', 'success');
    logout();
    redirect_to('page_register.php');
}

if (is_admin()) {
    set_flash_message("Вы удалили профиль {$user['fullname']}", 'success');
    redirect_to('users.php');
}