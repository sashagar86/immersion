<?php
$this->layout('layouts/login_layout', ['title' => 'Войти']);
?>

<form method="post">
    <div class="form-group">
        <label class="form-label" for="username">Email</label>
        <input type="email" id="username" class="form-control" placeholder="Эл. адрес" value="" name="email">
    </div>
    <div class="form-group">
        <label class="form-label" for="password">Пароль</label>
        <input type="password" id="password" class="form-control" placeholder="" name="password">
    </div>
    <div class="form-group text-left">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="rememberme">
            <label class="custom-control-label" for="rememberme">Запомнить меня</label>
        </div>
    </div>
    <button type="submit" class="btn btn-default float-right">Войти</button>
</form>
