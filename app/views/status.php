<?php
    $this->layout('layouts/layout', ['title' => 'Установить статус']);
?>

<div class="container">
    <form method="post">
            <div class="row">
                <div class="col-xl-6">
                    <?php include('partials/status.php');?>
                    <div class="col-md-12 mt-3 d-flex flex-row-reverse">
                        <button class="btn btn-warning">Set Status</button>
                    </div>
                </div>
            </div>
        </form>
</div>


