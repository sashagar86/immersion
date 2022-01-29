<?php

namespace App\Controllers;

class AuthenticationController extends Controller
{
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
}