<?php
session_start();

function logout() {
    unset($_SESSION['user']);
    header('Location: /login');
}

logout();