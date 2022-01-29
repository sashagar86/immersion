<?php
    $this->layout('layouts/layout', ['title' => 'Редактировать общую информацию']);
?>

<div class="container">
    <form method="post">
        <div class="row">
            <div class="col-xl-6">
                <?php include('partials/general.php'); ?>
            </div>
        </div>
        <div class="col-md-12 mt-3 d-flex flex-row-reverse">
            <button class="btn btn-warning" type="submit">Редактировать</button>
        </div>
    </form>
</div>