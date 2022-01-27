<?php

if (Validator::is_not_looged_in()) {
    header("Location: /login"); exit;
}

if (!Validator::is_admin()) {
    header("Location: /users"); exit;
}

$db = new QueryBuilder();

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
    $vkontakte = $_POST['vk'];
    $instagram = $_POST['instagram'];

    if (!Validator::isEmail($email) && !empty($email)) {
        Flash::setMessage("Поле email заполнено не правильно.");
        $error = true;
    }

    $errorFields = Validator::requiredFields(['email', 'password']);

    if (!empty($errorFields)) {
        foreach ($errorFields as $field) {
            Flash::setMessage("Поле $field должно быть заполнено");
        }

        header("Location: /create-user"); exit;
    }

    if(!$error) {
        $user = $db->getOne('users', $email, 'email');

        if (!empty($user)) {
            Flash::setMessage( "Такой email уже используется в системе");
            header('Location: /create-user'); exit;
        }

        $user_id = $db->create('users', $_POST);

//    $image = $_FILES;

//    upload_image($user_id, $image);

        if ($user_id) {
            Flash::setMessage ("Пользователь добавлен", 'success');
        } else {
            Flash::setMessage ("Что-то пошло не так");
        }

        header('Location: /create-user');
    }


}
