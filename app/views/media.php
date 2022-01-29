<?php
    $this->layout('layouts/layout', ['title' => 'Загрузить аватар']);
?>

<div class="container">
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-image'></i> Загрузить аватар
        </h1>
    </div>

    <form method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-xl-6">
                <?php include('partials/media.php');?>
            </div>
        </div>

        <div class="col-md-12 mt-3 d-flex flex-row-reverse">
            <button class="btn btn-warning">Upload</button>
        </div>
    </form>
</div>

