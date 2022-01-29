<div id="panel-1" class="panel">
    <div class="panel-container">
        <div class="panel-hdr">
            <h2>Установка текущего статуса</h2>
        </div>
        <div class="panel-content">
            <div class="row">
                <div class="col-md-4">
                    <!-- status -->
                    <div class="form-group">
                        <label class="form-label" for="example-select">Выберите статус</label>

                        <select class="form-control" id="example-select" name="status">
                            <?php foreach( $statuses as $key => $status ):?>
                                <option value="<?php echo $key?>" <?php if ($key == $user['status']):?>selected<?php endif;?>><?php echo $status?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>