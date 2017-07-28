<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = '';

$branches = $model->get();

?>
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Branches</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Name</th>
                        <th>Path</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach ($branches as $id => $branch) : ?>
                        <tr>
                            <td><?= $id + 1 ?></td>
                            <td><?= $branch['name'] ?></td>
                            <td><span class="badge bg-green"><?= $branch['path'] ?></span></td>
                            <td><?= $branch['description'] ?></td>
                            <td>
                                <?= Html::a('<span class="fa fa-code"></span>', ['site/update-sources',['branch'=>111]],
                                    ['title' => "Sync sources"]) ?>
                                <?= Html::a('<span class="fa fa-database"></span>', ['apply-migration',['branch'=>$branch['path']]],
                                    ['title' => "Apply migration"]) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
            </div>
        </div>
    </div>
</div>
