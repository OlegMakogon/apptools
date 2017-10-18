<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use common\models\Branch;
use common\models\BranchDirectories;
use common\models\VagrantBox;
use common\components\AppTools;
use frontend\models\BranchSearch;

/**
 * VagrantBoxController
 */
class VagrantBoxController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionVersion()
    {
        $vagrantBox = new VagrantBox();
        $result = [
            'success' => true,
            'output' => $vagrantBox->version()
        ];

        return $this->render('showOutput', [
            'operationName' => Yii::t('app','Vagrant box version'),
            'result' => $result,
        ]);
    }

    public function actionUpdate()
    {
        $vagrantBox = new VagrantBox();
        $result = $vagrantBox->update();

        return $this->render('showOutput', [
            'operationName' => Yii::t('app','Vagrant box update'),
            'result' => $result,
        ]);
    }
}
