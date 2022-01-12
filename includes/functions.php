<?php

//general
function get_user_by_email($email) {
    $db = connect_db();
    $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function redirect_to($path) {
    header("Location: /$path");
    exit;
}

function connect_db() {
    return new PDO('mysql:host=localhost;dbname=immersion', 'root', '');
}

function get_users() {
    $db = connect_db();
    $stmt = $db->prepare("SELECT * FROM users");
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//messages

function set_flash_message ($message, $label = 'danger') {
    $_SESSION['messages']['text'][] = $message;
    $_SESSION['messages']['label'] = $label;
}

function display_flash_messages () {
    if ($messages = $_SESSION['messages']['text']) {
        $messages = implode('<br/>', $messages);
        $label = $_SESSION['messages']['label'];
        unset($_SESSION['messages']);
        return '<div class="alert alert-' . $label . ' text-dark" role="alert">
                    ' . $messages . '
                </div>';
    }
    return '';
}

//checking
function checking_required_fields($required_fields) {
    $error = false;
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            set_flash_message("Поле $field должно быть заполнено");
            $error = true;
        }
    }

    if ($error) {
        redirect_to('page_register.php');
    }

    return false;
}

function is_not_looged_in()
{
    return empty($_SESSION['user']);
}

function is_admin() {
    return isset($_SESSION['user']['role']) &&  $_SESSION['user']['role'] == 'admin';

}

function is_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

//user
function add_user($email, $password) {
    $db = connect_db();
    $password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO `users`(`email`, `password`) VALUES (:email, :password)");
    $stmt->execute(['email' => $email, 'password' => $password]);
    return $db->lastInsertId();
}

function login($email, $password) {
    $user = get_user_by_email($email);

    $check_password = password_verify($password, $user['password']);

    $login = !empty($user) && $check_password;

    if($login) {
        $_SESSION['user'] = $user;
    }

    return $login;
}

function logout() {
    unset($_SESSION['user']);
    redirect_to('page_login.php');
}

function get_login_user() {
    return $_SESSION['user'];
}


