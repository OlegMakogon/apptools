<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\BranchSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Branches');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="box box-default box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'id',
                        'user_id',
                        'name',
                        'description',
                        'directory',
                        // 'yii_path',
                        // 'state',
                        // 'created_at',
                        // 'updated_at',

                        [
                            'class' => 'yii\grid\ActionColumn',

                            'template' => '{view} {update} {delete} {sync-sources} {apply-migrations}',
                            'buttons' => [
                                'sync-sources' => function ($url, $model, $key) {     // render sync-sources button
                                    return Html::a('<span class="fa fa-code"></span>',
                                        ['branch/update-sources','branch'=>$model->id],['title' => Yii::t('app','Sync sources')]);
                                },
                                'apply-migrations' => function ($url, $model, $key) {     // render apply-migrations button
                                    return Html::a('<span class="fa fa-database"></span>',
                                        ['branch/apply-migrations','branch'=>$model->id],['title' => Yii::t('app','Apply migrations')]);
                                },
                            ],
                        ],
                    ],
                ]); ?>

                <p>
                    <?= Html::a(Yii::t('app', 'Create Branch'), ['create'], ['class' => 'btn btn-success']) ?>
                </p>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
            </div>
        </div>
    </div>
</div>
