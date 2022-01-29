<?php

namespace App\Controllers;


class UserController extends Controller
{
    public function show($id)
    {
        $user = $this->model->findUser($id);
        echo $this->templates->render('profile', ['user' => $user]);
    }

    public function create()
    {
        $this->model->checkPermission();

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
        $this->model->checkPermission();

        $user = $this->model->findUser($id);

        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->model->_changePassword($id);

            $error = $this->model->checkEmail() && $this->model->checkExistUser($user);

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
            'statuses' => $this->model->getStatuses(),
            'image' => ImageController::getImage($user['image'])
        ]);
    }

    public function delete($id)
    {
        $this->model->checkPermission();

        $user = $this->model->findUser($id);

        if(empty($user)) {
            flash()->error('This user is not exist');
            header("Location: /users"); exit;
        }

        if (!$this->isAdmin() && $id != $this->currentUser()) {
            flash()->error('Выможете удалить только совй профиль');
            header("Location: /users"); exit;
        }

        $this->db->delete('users', $id);

        if ($id == $this->model->currentUser()) {
            flash()->success('You remove own account');
            $this->auth->logOut();
        }

        if ($this->model->isAdmin()) {
            flash()->success("You remove profile");
            header("Location: /users"); exit;
        }
    }

    public function index() {
        echo $this->templates->render('users', [
            'users' => $this->model->all(),
            'isLoggedIn' => $this->auth->isLoggedIn(),
            'currentUser' => $this->model->currentUser(),
            'isAdmin' => $this->model->isAdmin()
        ]);
    }
}
