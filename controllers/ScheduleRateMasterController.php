<?php

namespace app\controllers;

use Yii;
use app\models\ScheduleRateMaster;
use app\models\Search\ScheduleRateMaster as ScheduleRateMasterSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Model;

/**
 * ScheduleRateMasterController implements the CRUD actions for ScheduleRateMaster model.
 */
class ScheduleRateMasterController extends Controller
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

    public function actionAjaxRecordBySrmid($srmid){
        
        $model = ScheduleRateMaster::find()->where(['srmid'=>$srmid])->all();
        return $this->renderAjax('view', [
            'model' => $model,
        ]);
        
    }
    
    /**
     * Lists all ScheduleRateMaster models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ScheduleRateMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->orderBy(['id'=>SORT_DESC]);
  
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ScheduleRateMaster model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($srmid)
    {
        $model = ScheduleRateMaster::find()->where(['srmid'=>$srmid])->orderBy(['sno'=>SORT_ASC])->all();
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new ScheduleRateMaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = [new ScheduleRateMaster()];
        $flag = true;
        if (Yii::$app->request->post()) {
             $type = Yii::$app->request->post()['ScheduleRateMaster'][0]['type'];
                $model = Model::createMultiple(ScheduleRateMaster::classname(), []);
                             Model::loadMultiple($model, Yii::$app->request->post());
             $flag = ScheduleRateMaster::find()->where(['type'=>$type])->exists();
             if($flag){
                \Yii::$app->session->setFlash('error', 'Type Already exist');
             }else{
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction(); 
                $srmid = ScheduleRateMaster::getSrmid();
                foreach ($model as  $schedule) {
                   $schedule->srmid = $srmid;
                   $saved = $schedule->save();
                   if(!$saved){
                      $transaction->rollback();
                      \Yii::$app->session->setFlash('error', $schedule->errors);
                      break;
                   }
                }   
                if($saved){
                   $transaction->commit();
                   \Yii::$app->session->setFlash('success', 'Rate & Schedule has been Created Successfully');
                   return $this->redirect(['schedule-rate-master/view', 'srmid' => $srmid]);
                }
             }
            
        }
        return $this->render('create', [
            'model' => $model,
        ]);
        
    }

    /**
     * Updates an existing ScheduleRateMaster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($srmid)
    {
        $model = ScheduleRateMaster::find()->where(['srmid'=>$srmid])->all();
        $flag = true;
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        if (Yii::$app->request->post()) {
            $scheduleRateData = Yii::$app->request->post('ScheduleRateMaster');
            $ids = array_column($scheduleRateData,'id');
            $ids = array_filter($ids);
            if($ids){
               ScheduleRateMaster::deleteAll(['AND',['srmid'=>$srmid],['NOT IN','id',$ids]]);
            }
            $srmid = $scheduleRateData[0]['srmid'];
            foreach($scheduleRateData as $scheduleRate){
                if(!empty($scheduleRate['id'])){
                    $ScheduleRateMasterModel = ScheduleRateMaster::findOne($scheduleRate['id']);
                }else{
                    $ScheduleRateMasterModel = new ScheduleRateMaster;
                    $check = ScheduleRateMaster::find()
                                                ->where(['type'=>$scheduleRate['type']])
                                                ->andWhere(['!=','srmid',$srmid])->one();
                    if(!empty($check)){
                        $transaction->rollback();
                        \Yii::$app->session->setFlash('error', 'Type should be unique.');
                        $flag = false;
                        break;
                    }                            
                }
                $loadArray['ScheduleRateMaster'] = $scheduleRate;
                $ScheduleRateMasterModel->load($loadArray);
                $ScheduleRateMasterModel->srmid = $srmid;
                $ScheduleRateMasterModel->type = trim($ScheduleRateMasterModel->type);
                $ScheduleRateMasterModel->save();
            }
            if($flag){
                $transaction->commit();
                \Yii::$app->session->setFlash('success', 'Rate & Schedule has been Created Successfully');
                return $this->redirect(['schedule-rate-master/view', 'srmid' => $ScheduleRateMasterModel->srmid]);
            }else{
                $transaction->rollback();
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ScheduleRateMaster model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($srmid)
    {
        $model = ScheduleRateMaster::deleteAll(['srmid'=>$srmid]);
        return $this->redirect(['index']);
    }

    /**
     * Finds the ScheduleRateMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScheduleRateMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ScheduleRateMaster::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
