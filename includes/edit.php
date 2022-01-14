<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . "/includes/functions.php";

if (!empty($_POST)) {
    $user_id = $_SESSION['edit_user_id'];

    //general
    $fullname = $_POST['fullname'];
    $post = $_POST['post'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    edit($user_id, $fullname, $post, $phone, $address);

    unset($_SESSION['edit_user_id']);

    set_flash_message('Общая информация обновлена', 'success');
    redirect_to('users.php');
}