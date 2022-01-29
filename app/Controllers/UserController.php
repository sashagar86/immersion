<?php

namespace App\Controllers;

use App\Validator;


class UserController extends Controller
{
    const ADMIN = 1;
    const STATUS_ACTIVE = 1;
    const STATUS_OUTSIDE = 2;
    const STATUS_NOT_DISTURB = 3;

    private $statuses = [self::STATUS_ACTIVE => 'Онлайн', self::STATUS_OUTSIDE => 'Отошел', self::STATUS_NOT_DISTURB => 'Не беспокоить'];

    public function show($id)
    {
        $user = $this->findUser($id);
        echo $this->templates->render('profile', ['user' => $user]);
    }

    public function create()
    {
        $this->checkPermission();

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
        $this->checkPermission();

        $user = $this->findUser($id);

        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->_changePassword($id);

            $error = $this->checkEmail() && $this->checkExistUser($user);

            $image = new ImageController();
            $filename = $image->uploadImage();

            $_POST['image'] = $filename;

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

    public function delete($id)
    {
        $this->checkPermission();

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

    protected function _changePassword($id)
    {
        if(isset($_POST['password'])) {
            if (!empty($_POST['password'])) {
                $this->auth->admin()->changePasswordForUserById($id, $_POST['password']);
            }
            unset($_POST['password']);
        }
    }

    protected function checkEmail()
    {
        $error = false;
        if (isset($_POST['email'])) {
            if (!Validator::isEmail($_POST['email'])) {
                flash()->error('Email is invalid');
                $error = true;
            }
        }

        return $error;
    }

    protected function checkExistUser($user)
    {
        $error = false;
        $existUser = $this->db->getOne('users', $_POST['email'], 'email');

        if (!empty($existUser) && $existUser['id'] != $user['id']) {
            flash()->error('Email is exist');
            $error = true;
        }

        return $error;
    }

    protected function checkPermission()
    {
        if (!$this->auth->isLoggedIn()) {
            flash()->info("Please login");
            header("Location: /login"); exit;
        }

        if (!$this->isAdmin()) {
            flash()->error("You can not add/edit/delete the user. Please login as administrator.");
            header("Location: /users"); exit;
        }
    }
}
