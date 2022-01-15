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
        redirect_to('users.php');
    }

    //general
    $status = $_POST['status'];

    set_status($user_id, $status);

    set_flash_message('Статус обновлен', 'success');
    redirect_to('users.php');
}