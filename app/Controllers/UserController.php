<?php

namespace App\Controllers;

use DB\Connection;
use DB\QueryBuilder;
use League\Plates\Engine;
use Delight\Auth\Auth;
use SimpleMail;
use App\Validator;

class UserController
{
    const ADMIN = 1;
    const STATUS_ACTIVE = 1;
    const STATUS_OUTSIDE = 2;
    const STATUS_NOT_DISTURB = 3;

    private $statuses = [self::STATUS_ACTIVE => 'Онлайн', self::STATUS_OUTSIDE => 'Отошел', self::STATUS_NOT_DISTURB => 'Не беспокоить'];

    private $db;
    private $templates;
    private $auth;
    private $mailer;

    public function __construct()
    {
        $this->db = new QueryBuilder();
        $this->templates = new Engine('../app/views');
        $this->auth = new Auth(Connection::make());
        $this->mailer = new SimpleMail();
    }

    public function show($id)
    {
        $user = $this->findUser($id);
        echo $this->templates->render('user', ['user' => $user]);
    }

    public function create()
    {
        if (!$this->auth->isLoggedIn()) {
            flash()->info("Please login to add user.");
            header("Location: /login"); exit;
        }

        if (!$this->isAdmin()) {
            flash()->error("You can not add the user. Please login as administrator.");
            header("Location: /users"); exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $userId = $this->auth->admin()->createUser($_POST['email'], $_POST['password'], $_POST['username']);
            }
            catch (\Delight\Auth\InvalidEmailException $e) {
                flash()->error('Invalid email address');
            }
            catch (\Delight\Auth\InvalidPasswordException $e) {
                flash()->error('Invalid password');
            }
            catch (\Delight\Auth\UserAlreadyExistsException $e) {
                flash()->error('User already exists');
            }

            if ($userId) {
                $this->edit($userId);
                flash()->success("Пользователь добавлен");
                header('Location: /users');
            } else {
                flash()->error("Что-то пошло не так");
                header('Location: /user/add');
            }
        }

        echo $this->templates->render('create', ['statuses' => $this->getStatuses()]);
    }

    public function edit($id, $action = null)
    {
        if (!$this->auth->isLoggedIn()) {
            header("Location: /login"); exit;
        }

        if (!$this->canEdit($id)) {
            flash()->error("You can not edit this user");
            header("Location: /users"); exit;
        }

        $user = $this->findUser($id);

        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $error = false;

            if(isset($_POST['password'])) {
                if (!empty($_POST['password'])) {
                    $this->auth->admin()->changePasswordForUserById($id, $_POST['password']);
                }
                unset($_POST['password']);
            }

            if (isset($_POST['email'])) {
                if (!Validator::isEmail($_POST['email'])) {
                    flash()->error('Email is invalid');
                    $error = true;
                }

                $existUser = $this->db->getOne('users', $_POST['email'], 'email');

                if (!empty($existUser) && $existUser['id'] != $user['id']) {
                    flash()->error('Email is exist');
                    $error = true;
                }
            }

            $image = new ImageController();
            $filename = $image->uploadImage();

            if ($filename) {
                $_POST['image'] = $filename;
            }

            if (!$error && !empty($_POST)) {
                $this->db->update('users', $_POST, $id);
            } else {
                $url = $_SERVER['REQUEST_URI'];
                header("Location: $url"); exit;
            }

            header("Location: /user/{$id}");
        }

        echo $this->templates->render($action ?? 'edit', [
            'user' => $user,
            'id' => $id,
            'statuses' => $this->getStatuses(),
            'image' => ImageController::getImage($user['image'])
        ]);
    }

    public function register()
    {
        $success = false;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $this->auth->register($_POST['email'], $_POST['password'], '', function ($selector, $token) {
                    $this->mailer::make()
                        ->setTo($_POST['email'], $_POST['email'])
                        ->setFrom('test@immersion.marlin', 'administrator')
                        ->setSubject('Verify your email')
                        ->setMessage("To verify your account please go to link http://immersion.marlin/verify-email?selector=$selector&token=$token")
                        ->send();
                });
                $success = true;
                flash()->success('You was registered. Please check your email to verify account. Thanks.');
            }
            catch (\Delight\Auth\InvalidEmailException $e) {
                flash()->error('Invalid email address');
            }
            catch (\Delight\Auth\InvalidPasswordException $e) {
                flash()->error('Invalid password');
            }
            catch (\Delight\Auth\UserAlreadyExistsException $e) {
                flash()->error('User already exists');
            }
            catch (\Delight\Auth\TooManyRequestsException $e) {
                flash()->error('Too many requests');
            }
        }

        echo $this->templates->render('registration', ['success' => $success]);
    }

    public function delete($id)
    {
        $user = $this->findUser($id);

        if(empty($user)) {
            flash()->error('This user is not exist');
            header("Location: /users"); exit;
        }

        if (!$this->isAdmin() && $id != $this->currentUser()) {
            flash()->error('Выможете удалить только совй профиль');
            header("Location: /users"); exit;
        }

        $this->db->delete('users', $id);

        if ($id == $this->currentUser()) {
            flash()->success('You remove own account');
            $this->logout();
        }

        if ($this->isAdmin()) {
            flash()->success("You remove profile");
            header("Location: /users"); exit;
        }
    }

    public function login()
    {
        if ($this->auth->isLoggedIn()) {
            header('Location: /users');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $this->auth->login($_POST['email'], $_POST['password']);
                flash()->success('User is logged in');
                header('Location: /users');
            }
            catch (\Delight\Auth\InvalidEmailException $e) {
                flash()->error('Wrong email address');
            }
            catch (\Delight\Auth\InvalidPasswordException $e) {
                flash()->error('Wrong password');
            }
            catch (\Delight\Auth\EmailNotVerifiedException $e) {
                flash()->error('Email not verified');
            }
            catch (\Delight\Auth\TooManyRequestsException $e) {
                flash()->error('Too many requests');
            }
        }

        echo $this->templates->render('login');
    }

    public function logout()
    {
        $this->auth->logOut();
        header('Location: /login');
    }

    public function all()
    {
        $users = $this->db->getAll('users');
        $this->_processValues($users);

        $isLoggedIn = $this->auth->isLoggedIn();
        echo $this->templates->render('users', [
            'users' => $users,
            'isLoggedIn' => $isLoggedIn,
            'currentUser' => $this->currentUser(),
            'isAdmin' => $this->isAdmin()
        ]);
    }

    public function verifyEmail()
    {
        try {
            $this->auth->confirmEmail($_GET['selector'], $_GET['token']);
            flash()->success('Email address has been verified');
            header('Location: /login');
        }
        catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
            flash()->error('Invalid token');
        }
        catch (\Delight\Auth\TokenExpiredException $e) {
            flash()->error('Token expired');
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            flash()->error('Email address already exists');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->error('Too many requests');
        }
    }

    public function currentUser() {
        return $this->auth->getUserId();
    }

    public function isAdmin()
    {
        return $this->auth->hasRole(self::ADMIN);
    }

    public function getStatuses()
    {
        return $this->statuses;
    }

    public function findUser($id)
    {
        $user = $this->db->getOne('users', $id);

        $this->_processValues($user, true);

        if (empty($user)) {
            flash()->error("The user with $id not exists");
            header("Location: /users"); exit;
        }

        return $user;
    }

    public function canEdit($id)
    {
        return $this->isAdmin() || $id == $this->currentUser();
    }

    protected function _processValues(&$rows, $singleRow = false)
    {
        if (is_array($rows)) {
            $singleRow && $rows = [$rows];

            foreach ($rows as &$row) {
                $row['image'] = ImageController::getImage($row['image']);
            }

            $singleRow && $rows = array_shift($rows);
        }
    }
}
