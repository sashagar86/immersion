<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . "/includes/functions.php";

if(!empty($_POST)) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    //general
    $fullname = $_POST['fullname'];
    $post = $_POST['post'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    //status
    $status = $_POST['status'];

    //socials
    $telegram = $_POST['telegram'];
    $vkontakte = $_POST['vkontakte'];
    $instagram = $_POST['instagram'];

    if (!is_email($email) && !empty($email)) {
        set_flash_message("Поле email заполнено не правильно.");
        $error = true;
    }

    $error = checking_required_fields(['email', 'password']);

    $error && redirect_to('create_user.php');

    $user = get_user_by_email($email);

    if (!empty($user)) {
        set_flash_message( "Такой email уже используется в системе");
        redirect_to("create_user.php");
    }

    $user_id = add_user($email, $password);

    edit($user_id, $fullname, $post, $phone, $address);

    add_social_links($user_id, $telegram, $vkontakte, $instagram);

    set_status($user_id, $status);

    upload_image($user_id);

    if ($user_id) {
        set_flash_message ("Пользователь добавлен", 'success');
        redirect_to('users.php');
    } else {
        set_flash_message ("Что-то пошло не так");
        redirect_to('create_user.php');
    }
}
