<div id="panel-2" class="panel">
    <div class="panel-container">
        <div class="panel-hdr">
            <h2>Текущий аватар</h2>
        </div>
        <div class="panel-content">
            <div class="form-group">
                <img src="<?php echo $user['image']?>" alt="" class="img-responsive" width="200">
            </div>

            <div class="form-group">
                <label class="form-label" for="example-fileinput">Выберите аватар</label>
                <input type="file" id="example-fileinput" class="form-control-file" name="image">
            </div>
        </div>
    </div>
</div>