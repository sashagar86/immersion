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
        $data = $_POST;
        $this->model->checkPermission();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $userId = $this->auth->admin()->createUser($data['email'], $data['password'], $data['username']);
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

        echo $this->templates->render('create', ['statuses' => $this->model->getStatuses()]);
    }

    public function edit($id, $action = null)
    {
        $data = $_POST;
        $this->model->checkPermission();

        $user = $this->model->findUser($id);

        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->model->changePassword($id, $data['password']);

            $error = $this->model->checkEmail($data['email']) && $this->model->checkExistUser($user);

            $image = new ImageController();
            $filename = $image->uploadImage();

            $data['image'] = $filename;

            if (!$error && !empty($data)) {
                $this->db->update('users', $data, $id);
            } else {
                $url = $_SERVER['REQUEST_URI'];
                header("Location: $url"); exit;
            }

            header("Location: /user/{$id}");
        }

        echo $this->templates->render($action ?? 'edit', [
            'user' => $user,
            'id' => $id,
            'statuses' => $this->model->getStatuses()
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
