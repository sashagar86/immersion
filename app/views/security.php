<?php
$this->layout('layouts/layout', ['title' => 'Безопасность']);
?>

<div class="container">
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-lock'></i> Безопасность
        </h1>

    </div>
    <form method="post">
        <div class="row">
            <div class="col-xl-6">
                <?php include('partials/security.php');?>
            </div>
        </div>
        <div class="col-md-12 mt-3 d-flex flex-row-reverse">
            <button class="btn btn-warning" type="submit">Изменить</button>
        </div>
    </form>
</div>


