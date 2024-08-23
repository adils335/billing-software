<?php

namespace app\controllers;

use Yii;
use app\models\Agreement;
use app\models\ScheduleRateMaster;
use app\models\AgreementRateSchedule;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AgreementRateScheduleController implements the CRUD actions for AgreementRateSchedule model.
 */
class AgreementRateScheduleController extends Controller
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
     * Lists all AgreementRateSchedule models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => AgreementRateSchedule::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AgreementRateSchedule model.
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
     * Creates a new AgreementRateSchedule model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($agreement_id = null,$type=null)
    {
		$agreement = Agreement::findOne($agreement_id);
        $model = [new AgreementRateSchedule()];
        
        
        if($type){
           $type = (urldecode($type));    
           $masterModel = ScheduleRateMaster::find()->select(['srmid','sno','type','item','hsn_no','unit'])->where(['srmid'=>$type])->orderBy(['sno'=>SORT_ASC])->asArray()->all();
           $model = [];
           foreach ($masterModel as $master) {
               $array['AgreementRateSchedule'] = $master;
               $rateScheduleArray = new AgreementRateSchedule;
               $rateScheduleArray->load($array);
               $rateScheduleArray->type = $master['srmid'];
               $model[] =  $rateScheduleArray;
           }
        }
        if (Yii::$app->request->post()) {
			
			$rateScheduleData = Yii::$app->request->post('AgreementRateSchedule');
			
			foreach($rateScheduleData as $rateSchedule){
				
				if(!empty($rateSchedule['id']))
					$rateScheduleModel = AgreementRateSchedule::findOne($rateSchedule['id']);
				else $rateScheduleModel = new AgreementRateSchedule;
				
				$loadArray['AgreementRateSchedule'] = $rateSchedule;
				
				$rateScheduleModel->load($loadArray);
                $rateScheduleModel->is_active = 1;
				$rateScheduleModel->save();
				
			}
			
			\Yii::$app->session->setFlash('success', 'Rate & Schedule has been Created Successfully');
            return $this->redirect(['agreement/view', 'id' => $agreement->id]);
        }
        
        return $this->render('create', [
            'model' => $model,
			'agreement' => $agreement,
        ]);
		
    }

    /**
     * Updates an existing AgreementRateSchedule model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($agreement_id = null)
    {
		$agreement = Agreement::findOne($agreement_id);
        $model = AgreementRateSchedule::find()->where(['agreement_id'=>$agreement_id,'is_active'=>1])->orderBy(['sno'=>SORT_ASC])->all();
        $flag = true;
        if (!empty(Yii::$app->request->post())) {
			//echo "<pre>";print_r( Yii::$app->request->post() );die();
			$rateScheduleData = Yii::$app->request->post('AgreementRateSchedule');
			$is_active = array_column($rateScheduleData,'is_active')[0];
			$transaction = \Yii::$app->db->beginTransaction();
			if( $is_active ){
			    AgreementRateSchedule::updateAll(['is_active'=>0],['agreement_id'=>$agreement_id]);
			    foreach($rateScheduleData as $rateSchedule){
			    	$rateScheduleModel = new AgreementRateSchedule;
			    	$loadArray['AgreementRateSchedule'] = $rateSchedule;
			    	$rateScheduleModel->load($loadArray);
			    	$rateScheduleModel->is_active = 1;
			    	if( !$rateScheduleModel->save() ){
			    	    $flag = false;
			    	    $error = json_encode( $rateScheduleModel->getErrors() );
			    	    break;
			    	}
			    }
			}else{
			    $ids = array_column($rateScheduleData,'id');
			    $ids = array_filter($ids);
			    $deleteCondition = ['AND',['agreement_id'=>$agreement_id,'is_active'=>1],['NOT IN','id',$ids]];
			    AgreementRateSchedule::deleteAll($deleteCondition);
			    foreach($rateScheduleData as $rateSchedule){
			    	if(!empty($rateSchedule['id']))
			    		$rateScheduleModel = AgreementRateSchedule::findOne($rateSchedule['id']);
			    	else $rateScheduleModel = new AgreementRateSchedule;
			    	$loadArray['AgreementRateSchedule'] = $rateSchedule;
			    	$rateScheduleModel->load($loadArray);
			    	$rateScheduleModel->is_active = 1;
			    	if( !$rateScheduleModel->save() ){
			    	    $flag = false;
			    	    $error = json_encode( $rateScheduleModel->getErrors() );
			    	    break;
			    	}
			    }
			}
			if( $flag ){
			    \Yii::$app->session->setFlash('success', 'Rate & Schedule has been Updated Successfully');
			    $transaction->commit();
                return $this->redirect(['agreement/view', 'id' => $agreement->id]);
			}else{
			    $transaction->rollBack();
			    \Yii::$app->session->setFlash('error', $error);
			}
        }

        return $this->render('update', [
            'model' => $model,
			'agreement' => $agreement,
        ]);
    }

    /**
     * Deletes an existing AgreementRateSchedule model.
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
     * Finds the AgreementRateSchedule model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AgreementRateSchedule the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AgreementRateSchedule::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
