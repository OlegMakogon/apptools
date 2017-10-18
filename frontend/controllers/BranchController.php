<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use common\models\Branch;
use common\models\BranchDirectories;
use common\components\AppTools;
use frontend\models\BranchSearch;

/**
 * BranchController implements the CRUD actions for Branch model.
 */
class BranchController extends Controller
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

    /**
     * Lists all Branch models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BranchSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $countBranches = $dataProvider->getTotalCount();
        if ($countBranches === 0) {
            $branchDirectories = new BranchDirectories();
            $branchDirectories->scan();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Branch model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Branch model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Branch();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Branch model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Branch model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Branch model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Branch the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Branch::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionUpdateSources($branch)
    {
        $branchObj = Branch::findOne(['id'=>$branch]);
        $result = AppTools::updateSources($branchObj);

        return $this->render('showOutput', [
            'operationName' => Yii::t('app','Update sources'),
            'result' => $result,
        ]);
    }

    public function actionApplyMigrations($branch)
    {
        $branchObj = Branch::findOne(['id'=>$branch]);
        $result = AppTools::applyMigrations($branchObj);

        return $this->render('showOutput', [
            'operationName' => Yii::t('app','Apply migrations'),
            'result' => $result,
        ]);
    }

    public function actionFetchUnversioned($branch)
    {
        $branchObj = Branch::findOne(['id'=>$branch]);
        $result = AppTools::fetchUnversioned($branchObj);

        return $this->render('showOutput', [
            'operationName' => Yii::t('app','Fetch unversioned'),
            'result' => $result,
        ]);
    }

    public function actionScan()
    {
        $branchDirecotries = new BranchDirectories();
        $branchDirecotries->scan();

        $this->redirect(['branch/index']);
    }
}
