<div id="panel-1" class="panel">
    <div class="panel-container">
        <div class="panel-hdr">
            <h2>Общая информация</h2>
        </div>
        <div class="panel-content">
            <!-- username -->
            <div class="form-group">
                <label class="form-label" for="simpleinput">Имя</label>
                <input type="text" id="simpleinput" class="form-control" value="<?php echo $user['fullname'];?>" name="fullname">
            </div>

            <!-- title -->
            <div class="form-group">
                <label class="form-label" for="simpleinput">Место работы</label>
                <input type="text" id="simpleinput" class="form-control" value="<?php echo $user['post']; ?>" name="post">
            </div>

            <!-- tel -->
            <div class="form-group">
                <label class="form-label" for="simpleinput">Номер телефона</label>
                <input type="text" id="simpleinput" class="form-control" value="<?php echo $user['phone']; ?>" name="phone">
            </div>

            <!-- address -->
            <div class="form-group">
                <label class="form-label" for="simpleinput">Адрес</label>
                <input type="text" id="simpleinput" class="form-control" value="<?php echo $user['address']; ?>" name="address">
            </div>
        </div>
    </div>
</div>