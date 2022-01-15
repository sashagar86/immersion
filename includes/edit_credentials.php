<?php

session_start();
require $_SERVER['DOCUMENT_ROOT'] . "/includes/functions.php";

if (!empty($_POST)) {
    $user_id = (int)$_GET['id'];

    if (!$user_id) {
        set_flash_message('Неверно передан id пользователя');
        redirect_to('users.php');
    }

    $user = get_user_by_id($user_id);

    if (empty($user)) {
        set_flash_message('Такого пользовтаеля не существует');
    }

    $error = checking_required_fields(['email', 'password']);

    $error && redirect_to('security.php?id=' . $user_id);

    $email = $_POST['email'];
    $password = $_POST['password'];

    if (check_existing_email($email, $user['email'])) {
        set_flash_message('Пользователь с таким email существует');
        redirect_to('security.php?id=' . $user_id);
    }

    edit_credentials($user_id, $email, $password);

    set_flash_message('Учетные данные обновлены', 'success');
    redirect_to('page_profile.php?id=' . $user_id);
}