<?php

namespace App\Controllers;

use DB\QueryBuilder;
use Delight\Auth\Auth;
use League\Plates\Engine;
use SimpleMail;
use App\Models\User;

class Controller
{
    public $auth;
    public $templates;
    public $mailer;
    public $db;
    public $model;

    public function __construct(QueryBuilder $db, Engine $templates, Auth $auth, User $model, SimpleMail $mailer)
    {
        $this->db = $db;
        $this->templates = $templates;
        $this->auth = $auth;
        $this->mailer = $mailer;
        $this->model = $model;
    }
}