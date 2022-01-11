<?php

function get_user_by_email($email) {
    $db = connect_db();
    $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function add_user($email, $password) {
    $db = connect_db();
    $password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO `users`(`email`, `password`) VALUES (:email, :password)");
    $stmt->execute(['email' => $email, 'password' => $password]);
    return $db->lastInsertId();
}

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

function redirect_to($path) {
    header("Location: /$path");
    exit;
}

function is_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

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

function connect_db() {
    return new PDO('mysql:host=localhost;dbname=immersion', 'root', '');
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