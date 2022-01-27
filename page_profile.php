<?php
session_start();
include_once "includes/functions.php";

if (is_not_looged_in()) {
    redirect_to('page_login.php');
}

$id = (int)$_GET['id'];
$login_user = get_login_user();
$user = get_user_by_id($id);

if(empty($user)) {
    set_flash_message("Пользователя с таким id не существует");
    redirect_to('users.php');
}

$socials = ['telegram' => '#38A1F3', 'vk' => '#4680C2', 'instagram' => '#E1306C'];

$image = get_image($user);

$messages = display_flash_messages();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Профиль пользователя</title>
    <meta name="description" content="Chartist.html">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
    <link id="vendorsbundle" rel="stylesheet" media="screen, print" href="css/vendors.bundle.css">
    <link id="appbundle" rel="stylesheet" media="screen, print" href="css/app.bundle.css">
    <link id="myskin" rel="stylesheet" media="screen, print" href="css/skins/skin-master.css">
    <link rel="stylesheet" media="screen, print" href="css/fa-solid.css">
    <link rel="stylesheet" media="screen, print" href="css/fa-brands.css">
    <link rel="stylesheet" media="screen, print" href="css/fa-regular.css">
</head>
    <body class="mod-bg-1 mod-nav-link">
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary bg-primary-gradient">
            <a class="navbar-brand d-flex align-items-center fw-500" href="#"><img alt="logo" class="d-inline-block align-top mr-2" src="img/logo.png"> Учебный проект</a> <button aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler" data-target="#navbarColor02" data-toggle="collapse" type="button"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarColor02">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item ">
                        <a class="nav-link" href="#">Главная</a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <?php if (is_not_looged_in()):?>
                        <li class="nav-item">
                            <a class="nav-link" href="page_login.html">Войти</a>
                        </li>
                    <?php else:?>
                        <li class="nav-item">
                            <a class="nav-link" href="/logout">Выйти</a>
                        </li>
                    <?php endif;?>
                </ul>
            </div>
        </nav>

        <?php if (App\Flash::getMessages()):?>
            <?php echo App\Flash::getMessages(); ?>
        <?php endif;?>

        <main id="js-page-content" role="main" class="page-content mt-3">
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
                                    <img src="<?php echo $image?>" class="rounded-circle shadow-2 img-thumbnail" alt="">

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
                        </div>
                    </div>
               </div>
            </div>
        </main>
    </body>

    <script src="js/vendors.bundle.js"></script>
    <script src="js/app.bundle.js"></script>
    <script>

        $(document).ready(function()
        {

        });

    </script>
</html>