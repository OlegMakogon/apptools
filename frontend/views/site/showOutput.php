<?php

$this->title = $operationName;
$this->params['breadcrumbs'][] = $this->title;

if (!$result['success']) {
    $boxStyleClass = 'box-danger';
} else {
    $boxStyleClass = 'box-success';
}

?>

<div class="row">
    <div class="col-md-12">
        <div class="box <?= $boxStyleClass ?> box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><?= $operationName ?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <?= $result['output'] ?>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
            </div>
        </div>
    </div>
</div>

