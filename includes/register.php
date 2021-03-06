<?php
session_start();

require $_SERVER['DOCUMENT_ROOT'] . "/includes/functions.php";

if (!empty($_POST)) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!is_email($email) && !empty($email)) {
        set_flash_message("Поле email заполнено не правильно.");
        $error = true;
    }

    $error = checking_required_fields(['email', 'password']);

    $error && redirect_to('page_register.php');

    $user = get_user_by_email($email);

    if (!empty($user)) {
        set_flash_message( "Такой email уже используется в системе");
        redirect_to("page_register.php");
    }

    $user_id = add_user($email, $password);

    if ($user_id) {
        set_flash_message ("Регистрация успешна", 'success');
        redirect_to("page_login.php");
    } else {
        set_flash_message ("Что-то пошло не так");
    }

    redirect_to("page_register.php");

}