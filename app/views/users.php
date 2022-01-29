<?php
    $this->layout('layouts/layout', ['title' => 'Список пользовтаелей']);
    $socials = ['telegram' => '#38A1F3', 'vk' => '#4680C2', 'instagram' => '#E1306C'];
?>

<div class="subheader">
    <h1 class="subheader-title">
        <i class='subheader-icon fal fa-users'></i> Список пользователей
    </h1>
</div>
<div class="row">
    <div class="col-xl-12">
        <?php if ($isAdmin):?>
            <a class="btn btn-success" href="/user/add">Добавить</a>
        <?php endif;?>

        <div class="border-faded bg-faded p-3 mb-g d-flex mt-3">
            <input type="text" id="js-filter-contacts" name="filter-contacts" class="form-control shadow-inset-2 form-control-lg" placeholder="Найти пользователя">
            <div class="btn-group btn-group-lg btn-group-toggle hidden-lg-down ml-3" data-toggle="buttons">
                <label class="btn btn-default active">
                    <input type="radio" name="contactview" id="grid" checked="" value="grid"><i class="fas fa-table"></i>
                </label>
                <label class="btn btn-default">
                    <input type="radio" name="contactview" id="table" value="table"><i class="fas fa-th-list"></i>
                </label>
            </div>
        </div>
    </div>
</div>
<div class="row" id="js-contacts">

    <?php if (!empty($users)):?>
        <?php foreach( $users as $user ):?>
            <?php
            $online = $user['online'] ? 'success' : 'warning';
            $is_owner = $user['id'] == $currentUser;
            $user_id = $user['id'];
            ?>
            <div class="col-xl-4">
                <div id="c_1" class="card border shadow-0 mb-g shadow-sm-hover" data-filter-tags="oliver kopyov">
                    <div class="card-body border-faded border-top-0 border-left-0 border-right-0 rounded-top">
                        <div class="d-flex flex-row align-items-center">
                                    <span class="status status-<?php echo $online;?> mr-3">
                                        <span class="rounded-circle profile-image d-block " style="background-image:url('<?php echo $user['image']?>'); background-size: cover;"></span>
                                    </span>
                            <div class="info-card-text flex-1">
                                <a href="javascript:void(0);" class="fs-xl text-truncate text-truncate-lg text-info" data-toggle="dropdown" aria-expanded="false">
                                    <?php echo $user['fullname'];?>
                                    <?php if ($isAdmin || $is_owner):?>
                                        <i class="fal fas fa-cog fa-fw d-inline-block ml-1 fs-md"></i>
                                        <i class="fal fa-angle-down d-inline-block ml-1 fs-md"></i>
                                    <?php endif;?>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="/user/<?php echo $user_id;?>">
                                        <i class="fa fa-user"></i>
                                        Смотреть профиль</a>
                                    <?php if ($isAdmin || $is_owner):?>
                                        <a class="dropdown-item" href="/user/<?php echo $user_id;?>/edit/general">
                                            <i class="fa fa-edit"></i>
                                            Редактировать</a>
                                        <a class="dropdown-item" href="/user/<?php echo $user_id;?>/edit/security">
                                            <i class="fa fa-lock"></i>
                                            Безопасность</a>
                                        <a class="dropdown-item" href="/user/<?php echo $user_id; ?>/edit/status">
                                            <i class="fa fa-sun"></i>
                                            Установить статус</a>
                                        <a class="dropdown-item" href="/user/<?php echo $user_id; ?>/edit/media">
                                            <i class="fa fa-camera"></i>
                                            Загрузить аватар
                                        </a>
                                        <a href="/remove?id=<?php echo $user_id?>" class="dropdown-item" onclick="return confirm('are you sure?');">
                                            <i class="fa fa-window-close"></i>
                                            Удалить
                                        </a>
                                    <?php endif;?>
                                </div>
                                <?php if (!empty($user['post'])):?>
                                    <span class="text-truncate text-truncate-xl"><?php echo $user['post']?></span>
                                <?php endif;?>

                            </div>
                            <button class="js-expand-btn btn btn-sm btn-default d-none" data-toggle="collapse" data-target="#c_1 > .card-body + .card-body" aria-expanded="false">
                                <span class="collapsed-hidden">+</span>
                                <span class="collapsed-reveal">-</span>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0 collapse show">
                        <div class="p-3">
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

                            <div class="d-flex flex-row">
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
                </div>
            </div>
        <?php endforeach;?>
    <?php endif;?>
</div>
