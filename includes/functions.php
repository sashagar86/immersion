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

    return $error;
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

// add user
function edit($user_id, $fullname, $post, $phone, $address) {
    $db = connect_db();
    $sql = "UPDATE users SET fullname = :fullname, post = :post, phone = :phone, address = :address WHERE id = :id";
    $stmt = $db->prepare($sql);

    $stmt->execute([
        'fullname' => $fullname,
        'post' => $post,
        'phone' => $phone,
        'address' => $address,
        'id' => $user_id
    ]);

    return $stmt->rowCount();
}

function set_status($user_id, $status) {
    $db = connect_db();
    $sql = "UPDATE users SET status = :status WHERE id = :id";
    $stmt = $db->prepare($sql);

    $stmt->execute([
        'status' => $status,
        'id' => $user_id
    ]);
}

function upload_image($user_id, $image) {
    $name = $image['image']['name'];

    if ($name) {
        $name = explode('.', $name);
        $extension = '.' . end($name);

        $tmp_name = $image['image']['tmp_name'];
        $filename = uniqid() . $extension;
        $dir = getUploadsDir();
        if (move_uploaded_file($tmp_name, $dir . $filename)) {
            insertImageInDb($user_id, $filename);
        }
    }

    return false;
}

function add_social_links($user_id, $telegram, $vkontakte, $instagram) {
    $db = connect_db();
    $sql = "UPDATE users SET telegram = :telegram, vk = :vkontakte, instagram = :instagram WHERE id = :id";
    $stmt = $db->prepare($sql);

    $stmt->execute([
        'telegram' => $telegram,
        'vkontakte' => $vkontakte,
        'instagram' => $instagram,
        'id' => $user_id
    ]);
}

function insertImageInDb($user_id, $filename) {
    $db = connect_db();
    $sql = "UPDATE users SET image = :image WHERE id = :id";
    $stmt = $db->prepare($sql);

    $stmt->execute([
        'image' => $filename,
        'id' => $user_id
    ]);
}

function getUploadsDir() {
    $dir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';
    if(!is_dir($dir)) {
        mkdir($dir);
    }

    return $dir;
}

//edit profile

function get_user_by_id($id) {
    $db = connect_db();
    $sql = "SELECT * FROM users WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->execute(['id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function is_author($logged_user, $user){
    return $logged_user['id'] == $user['id'];
}

//edit credentials

function edit_credentials($user_id, $email, $password) {
    $db = connect_db();
    $password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $db->prepare("UPDATE `users` SET `email` = :email, password = :password WHERE id = :user_id");

    $stmt->execute(['email' => $email, 'password' => $password, 'user_id' => $user_id]);
}

function check_existing_email($email, $user_email) {
    if ($email == $user_email) {
        return false;
    }

    $user = get_user_by_email($email);
    return !empty($user);
}

//upload image

function get_image($user) {
    $uploads_dir = getUploadsDir();
    $uploads = 'uploads/';

    $file = $uploads_dir . ($user['image'] ?: 'avatar-m.png');

    if (!file_exists($file)) {
        $image = $uploads . 'avatar-m.png';
    }

    return $uploads . ($user['image'] ?: 'avatar-m.png');
}

function delete_file($filename) {
    $file = getUploadsDir() . $filename;

    if (file_exists($file)) {
        unlink($file);
    }
}

function remove_user($user) {
    $db = connect_db();
    $stmt = $db->prepare("DELETE FROM users where id = :id");
    $stmt->execute(['id' => $user['id']]);
    delete_file($user['image']);
}



