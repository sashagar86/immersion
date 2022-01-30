<?php

namespace App\Models;

use App\Components\Validator;
use App\Controllers\ImageController;
use DB\QueryBuilder;
use Delight\Auth\Auth;

class User
{
    private $auth;
    private $db;

    const ADMIN = 1;
    const STATUS_ACTIVE = 1;
    const STATUS_OUTSIDE = 2;
    const STATUS_NOT_DISTURB = 3;

    public $statuses = [self::STATUS_ACTIVE => 'Онлайн', self::STATUS_OUTSIDE => 'Отошел', self::STATUS_NOT_DISTURB => 'Не беспокоить'];

    public function __construct(QueryBuilder $db, Auth $auth)
    {
        $this->auth = $auth;
        $this->db = $db;
    }

    public function all()
    {
        $users = $this->db->getAll('users');
        $this->_processValues($users);
        return $users;
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

    public function changePassword($id, $password)
    {
        if(isset($password)) {
            if (!empty($password)) {
                $this->auth->admin()->changePasswordForUserById($id, $password);
            }
            unset($password);
        }
    }

    public function checkEmail($email)
    {
        $error = false;
        if (isset($email)) {
            if (!Validator::isEmail($email)) {
                flash()->error('Email is invalid');
                $error = true;
            }
        }

        return $error;
    }

    public function checkExistUser($user)
    {
        $error = false;
        $existUser = $this->db->getOne('users', $_POST['email'], 'email');

        if (!empty($existUser) && $existUser['id'] != $user['id']) {
            flash()->error('Email is exist');
            $error = true;
        }

        return $error;
    }

    public function checkPermission()
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