<?php

namespace app\controllers;

use Yii;
use app\models\ControllerAction;
use app\models\Search\ControllerAction as ControllerActionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ControllerActionController implements the CRUD actions for ControllerAction model.
 */
class ControllerActionController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ControllerAction models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ControllerActionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ControllerAction model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ControllerAction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ControllerAction();
        $controllers = $model->getAllControllers();
        $actions = $model->allActions();

        if (Yii::$app->request->post()) {
            //echo "<pre>";print_r(Yii::$app->request->post()['ControllerAction']);die();
            $controllerActionData = Yii::$app->request->post()['ControllerAction'];
            foreach ($controllerActionData as $key => $value) {
                $controllerAction = ControllerAction::find()->where(['controller'=>$value['controller']])->one();
                if(empty($controllerAction)){
                   $controllerAction = new ControllerAction;
                }
                
                $controllerAction->controller = $value['controller'];
                $controllerAction->action = json_encode($value['action']);

                $controllerAction->save();
            }
            return $this->redirect(['create']);
        }

        return $this->render('create', [
            'model' => $model,
            'actionArray' => $actions,
            'controllers' => $controllers,
        ]);
    }

    /**
     * Updates an existing ControllerAction model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    
    /**
     * Deletes an existing ControllerAction model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ControllerAction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ControllerAction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ControllerAction::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
