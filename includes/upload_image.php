<?php

session_start();
require $_SERVER['DOCUMENT_ROOT'] . "/includes/functions.php";

if (!empty($_FILES)) {
    $user_id = (int)$_GET['id'];
    if (!$user_id) {
        set_flash_message('Неверно передан id пользователя');
        redirect_to('users.php');
    }

    $user = get_user_by_id($user_id);

    if (empty($user)) {
        set_flash_message('Такого пользовтаеля не существует');
        redirect_to('page_profile.php?id=' . $user_id);
    }

    upload_image($user_id, $_FILES);
}

set_flash_message('Поле с картинокй обновлено', 'success');
redirect_to('page_profile.php?id=' . $user_id);
