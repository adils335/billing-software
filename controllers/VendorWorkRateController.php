<?php

namespace app\controllers;

use Yii;
use app\models\VendorWorkRate;
use app\models\Search\VendorWorkRate as VendorWorkRateSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * VendorWorkRateController implements the CRUD actions for VendorWorkRate model.
 */
class VendorWorkRateController extends Controller
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

    public function actionAjaxWork($work_type){
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        if(!empty($work_type)){
           $works = \app\models\Work::find()->where(['work_type'=>$work_type])->orderBy('id')->all();
           
           $data = [['id' => '', 'text' => '']];
           foreach ($works as $work) {
               $data[] = ['id' => $work->id, 'text' => $work->name];
           }
        }
        
        return ['data' => $data];
    }
    
    /**
     * Lists all VendorWorkRate models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VendorWorkRateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single VendorWorkRate model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($vendor_id,$work_type)
    {
        $record = VendorWorkRate::find()->where(['vendor_id'=>$vendor_id,'work_type'=>$work_type])->one();
        $model = VendorWorkRate::find()->where(['vendor_id'=>$vendor_id,'work_type'=>$work_type])->all();
        return $this->render('view', [
            'model' => $model,
            'record' => $record,
        ]);
    }

    /**
     * Creates a new VendorWorkRate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = [new VendorWorkRate()];
        
        if (Yii::$app->request->post()) {
            //echo "<pre>";print_r(Yii::$app->request->post());die();
            $postData = Yii::$app->request->post()['VendorWorkRate'];
            
            foreach ($postData as $key => $workRate) {
                
               $vendorWorkRate = new VendorWorkRate;
               $loadArray['VendorWorkRate'] = [
                                               'vendor_id'=> $workRate['vendor_id'],
                                               'work_type'=> $workRate['work_type'],
                                               'company_id'=>$workRate['company_id'],
                                               'work_name'=>$workRate['work_name'],
                                               'rate'=>$workRate['rate']
                                              ]; 
                $vendorWorkRate->load($loadArray);
                
                $vendorWorkRate->save();
            }

            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing VendorWorkRate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($vendor_id,$work_type)
    {
        $record = VendorWorkRate::find()->where(['vendor_id'=>$vendor_id,'work_type'=>$work_type])->one();
        $model = VendorWorkRate::find()->where(['vendor_id'=>$vendor_id,'work_type'=>$work_type])->orderBy(['id'=>SORT_ASC])->all();
       
        if (Yii::$app->request->post()) {
            //echo "<pre>";print_r(Yii::$app->request->post());die();
            $postData = Yii::$app->request->post()['VendorWorkRate'];
            
            foreach ($postData as $key => $workRate) {
               
               if($workRate['id']){
                 $vendorWorkRate = VendorWorkRate::find()->where(['id'=>$workRate['id']])->one();;
               }else{ 
                 $vendorWorkRate = new VendorWorkRate;
               } 

               $loadArray['VendorWorkRate'] = [
                                               'vendor_id'=> $workRate['vendor_id'],
                                               'work_type'=> $workRate['work_type'],
                                               'company_id'=>$workRate['company_id'],
                                               'work_name'=>$workRate['work_name'],
                                               'rate'=>$workRate['rate']
                                              ]; 
                $vendorWorkRate->load($loadArray);
                
                $vendorWorkRate->save();
            }

            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'record' => $record,
        ]);
    }

    /**
     * Deletes an existing VendorWorkRate model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($vendor_id,$work_type)
    {
        vendorWorkRate::deleteAll(['vendor_id'=>$vendor_id,'work_type'=>$work_type]);

        return $this->redirect(['index']);
    }

    /**
     * Finds the VendorWorkRate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return VendorWorkRate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VendorWorkRate::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
