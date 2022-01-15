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
        redirect_to('page_profile.php?id=' . $user_id);
    }

    //general
    $fullname = $_POST['fullname'];
    $post = $_POST['post'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    edit($user_id, $fullname, $post, $phone, $address);

    set_flash_message('Общая информация обновлена', 'success');
    redirect_to('users.php');
}