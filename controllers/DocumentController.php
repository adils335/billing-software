<?php

namespace app\controllers;

use Yii;
use app\models\Document;
use app\models\Search\Document as DocumentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * DocumentController implements the CRUD actions for Document model.
 */
class DocumentController extends Controller
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
     * Lists all Document models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DocumentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Document model.
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
     * Creates a new Document model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Document();
       
        if (Yii::$app->request->isPost) {
            
            $model->load(Yii::$app->request->post());
            
            $file = UploadedFile::getInstance($model, 'file');

            $model->file = 'upload/document/'.time().'-'.$file->name;
           
            if ($model->validate()) {   
                 $file->saveAs($model->file);
                 $model->save();
                 \Yii::$app->session->setFlash('success', 'Document Added successfully.');
                                                       
                 if($model->employee_id){
                     return $this->redirect(['employee/view','id'=>$model->employee_id]);
                 }elseif ($model->vendor_id) {
                     return $this->redirect(['vendor/view','id'=>$model->vendor_id]);
                 }elseif ($model->worker_id) {
                     return $this->redirect(['worker/view','id'=>$model->worker_id]);
                 }elseif ($model->worker_vendor_id) {
                     return $this->redirect(['worker-vendor/view','id'=>$model->worker_vendor_id]);
                 }elseif ($model->site_dues_id) {
                     return $this->redirect(['site-dues/view','id'=>$model->site_dues_id]);
                 }elseif ($model->company_dues_id) {
                     return $this->redirect(['company-dues/view','id'=>$model->company_dues_id]);
                 }

            }else{
                echo "<pre>";print_r($model->getErrors());die();
                $this->render('../employee/view', array('errors' => $model->getErrors(),'id'=>$model->employee_id));
            }
            
            
        }

        return $this->redirect(['employee/index']);

    }

    /**
     * Updates an existing Document model.
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
     * Deletes an existing Document model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        unlink(Yii::getAlias('@webroot').'/'. $model->file);
        $this->findModel($id)->delete();
        
        \Yii::$app->session->setFlash('success', 'Document deleted successfully.');
        if($model->employee_id){
            return $this->redirect(['employee/view','id'=>$model->employee_id]);
        }elseif ($model->vendor_id) {
            return $this->redirect(['vendor/view','id'=>$model->vendor_id]);
        }elseif ($model->worker_id) {
            return $this->redirect(['worker/view','id'=>$model->worker_id]);
        }elseif ($model->worker_vendor_id) {
            return $this->redirect(['worker-vendor/view','id'=>$model->worker_vendor_id]);
        }elseif ($model->site_dues_id) {
            return $this->redirect(['site-dues/view','id'=>$model->site_dues_id]);
        }elseif ($model->company_dues_id) {
            return $this->redirect(['company-dues/view','id'=>$model->company_dues_id]);
        }
    }

    /**
     * Finds the Document model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Document the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Document::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
