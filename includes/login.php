<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/includes/functions.php";
if (!empty($_POST)) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $login = login($email, $password);

    if ($login) {
        set_flash_message("Здравствуйте $email", 'success');
        redirect_to('users.php');
    }

    set_flash_message("Поле email или пароль введены не корректно");
    redirect_to('page_login.php');
}
