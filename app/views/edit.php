<?php
$this->layout('layouts/layout', ['title' => 'Edit User pofile']);

?>

<div class="container">
    <form method="post" enctype="multipart/form-data">

        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-pencil'></i> Edit profile
            </h1>
        </div>
        <div class="row">
            <div class="col-xl-6">
                <?php include('partials/security.php');?>
                <?php include('partials/general.php');?>
                <?php include('partials/media.php');?>
                <?php include('partials/status.php');?>
                <?php include('partials/socials.php');?>
            </div>
        </div>

        <div class="col-md-12 mt-3 d-flex flex-row-reverse">
            <button class="btn btn-warning">Save</button>
        </div>
    </form>
</div>
