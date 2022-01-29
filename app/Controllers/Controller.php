<?php

namespace App\Controllers;

use App\Models\User;
use DB\Connection;
use DB\QueryBuilder;
use Delight\Auth\Auth;
use League\Plates\Engine;
use SimpleMail;

class Controller
{
    public $auth;
    public $templates;
    public $mailer;
    public $db;
    public $model;

    public function __construct()
    {
        $this->db = new QueryBuilder();
        $this->templates = new Engine('../app/views');
        $this->auth = new Auth(Connection::make());
        $this->mailer = new SimpleMail();
        $this->model = new User();
    }
}