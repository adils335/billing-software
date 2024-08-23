<?php

namespace app\controllers;

use Yii;
use app\models\Documents;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * DocumentsController implements the CRUD actions for Documents model.
 */
class DocumentsController extends Controller
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
     * Lists all Documents models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Documents::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Documents model.
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
     * Creates a new Documents model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Documents;;
       
        if ( $model->load(Yii::$app->request->post()) ) {
            
            $file = UploadedFile::getInstance($model, 'file');
            $dir = 'upload/document/' . $model->typeLabel;
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            $model->file = $dir . "/" . time() . '-' . $file->name . '.' . $file->extension;
           
            if ($model->validate()) {   
                 $file->saveAs($model->file);
                 $model->save();
                 \Yii::$app->session->setFlash('success', 'Document Added successfully.');

            }else{
                $errors = array_map(function($items){
                    return implode(',',$items);
                },$model->getErrors());
                $errors = implode(',',$errors);
                \Yii::$app->session->setFlash('error', $errors);
            }
            return $this->redirect([$model->path,'id'=>$model->source_id]);
        }

        return $this->redirect(['site/index']);

        /*return $this->render('create', [
            'model' => $model,
        ]);*/
    }

    /**
     * Updates an existing Documents model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Documents model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = $model::STATUS_DELETED;
        $model->save();
        \Yii::$app->session->setFlash('success', 'Document deleted successfully.');
        return $this->redirect([$model->path,'id'=>$model->source_id]);
    }

    /**
     * Finds the Documents model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Documents the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Documents::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
