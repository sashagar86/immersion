<?php
    $this->layout('layouts/layout', ['title' => 'Профиль пользователя']);
    $socials = ['telegram' => '#38A1F3', 'vk' => '#4680C2', 'instagram' => '#E1306C'];
?>

<?php if ($user['fullname']):?>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-user'></i>
            <?php echo $user['fullname']?>
        </h1>
    </div>
<?php endif;?>

<div class="row">
    <div class="col-lg-6 col-xl-6 m-auto">
        <!-- profile summary -->
        <div class="card mb-g rounded-top">
            <div class="row no-gutters row-grid">
                <div class="col-12">
                    <div class="d-flex flex-column align-items-center justify-content-center p-4">
                        <img src="<?php echo $user['image']?>" class="rounded-circle shadow-2 img-thumbnail" alt="">

                        <h5 class="mb-0 fw-700 text-center mt-3">
                            <?php if ($user['fullname']):?>
                                <?php echo $user['fullname']?>
                            <?php endif;?>
                            <?php if ($user['address']):?>
                                <small class="text-muted mb-0"><?php echo $user['address']?></small>
                            <?php endif;?>
                        </h5>

                        <div class="mt-4 text-center demo">
                            <?php foreach( $socials as $key => $color ):?>
                                <?php if (!empty($user[$key])):?>
                                    <a href="javascript:void(0);" class="mr-2 fs-xxl" style="color:<?php echo $color?>">
                                        <i class="fab fa-<?php echo $key;?>"></i>
                                    </a>
                                <?php endif;?>
                            <?php endforeach;?>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="p-3 text-center">
                        <?php if (!empty($user['phone'])):?>
                            <a href="tel:<?php echo $user['phone'];?>>" class="mt-1 d-block fs-sm fw-400 text-dark">
                                <i class="fas fa-mobile-alt text-muted mr-2"></i>
                                <?php echo $user['phone'];?>
                            </a>
                        <?php endif;?>

                        <a href="mailto:<?php echo $user['email']?>" class="mt-1 d-block fs-sm fw-400 text-dark">
                            <i class="fas fa-mouse-pointer text-muted mr-2"></i>
                            <?php echo $user['email']?>
                        </a>

                        <?php if (!empty($user['address'])):?>
                            <address class="fs-sm fw-400 mt-4 text-muted">
                                <i class="fas fa-map-pin mr-2"></i>
                                <?php echo $user['address']; ?>
                            </address>
                        <?php endif;?>
                    </div>
                </div>
                <div class="col-12">
                    <div class="p-3">
                        <div class="button-actions text-right">
                            <a class="btn btn-success" href="/user/<?php echo $user['id']?>/edit"><i class="fa fa-pencil"></i></a>
                            <a class="btn btn-danger" href="/user/<?php echo $user['id']?>/remove"><i class="fa fa-trash"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
