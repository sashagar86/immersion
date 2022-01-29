<?php

namespace App\Controllers;


class RegistrationController extends Controller
{
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
}