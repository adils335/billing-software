<?php

namespace app\controllers;

use Yii;
use app\models\District;
use app\models\Search\District as DistrictSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DistrictController implements the CRUD actions for District model.
 */
class DistrictController extends Controller
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
     * Lists all District models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DistrictSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single District model.
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
     * Creates a new District model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new District();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
		\Yii::$app->session->setFlash('success', 'Session has been created Successfully');
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing District model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			
		\Yii::$app->session->setFlash('success', 'Session has been updated Successfully');
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing District model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

		\Yii::$app->session->setFlash('success', 'Session has been Deleted Successfully');
        return $this->redirect(['index']);
    }

    /**
     * Finds the District model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return District the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
	 
	 public function actionDistrictStateWise(){
		 
		 \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		 $state = $_POST['state'];
		 $company_id = isset($_POST['company_id'])?$_POST['company_id']:"";
		 $model = isset($_POST['model'])?$_POST['model']:"";
		 $isNewRecord = isset($_POST['isNewRecord'])?$_POST['isNewRecord']:true;
		 if( !empty( $company_id ) ){
		     if( $model == "ContractCompany" )
		     $companyGst = \app\models\ContractCompanyGst::find()->where(['state_id'=>$state,'company_id'=>$company_id])->one();
		     elseif( $model == "BillingCompany" )
		     $companyGst = \app\models\BillingCompanyGst::find()->where(['state_id'=>$state,'company_id'=>$company_id])->one();
		     if( !empty( $companyGst ) ){
		        $data = array('redirect'=>true,'company_id'=>$company_id,'gst_id'=>$companyGst->id);
		        return $data;
		     }
		 }
		 $model = District::find()->where(['state_id'=>$state])->orderBy(['district'=>SORT_ASC])->all();
		 $district = array();
		 foreach($model as $value){
			 $district[$value->id] = $value->district;
		 }
		 return $district;
	 }
	 
	 public function actionGst(){
		 
		 \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		 $state = $_POST['state'];
		 $model = $_POST['model'];
		 $company = $_POST['company'];
		 
		 if($model == "Company")
		     $gstModel = \app\models\CompanyGst::find()->where(['company_id'=>$company, 'state_id'=>$state])->one();
		 if($model == "BillingCompany" || $model == "ContractCompany")    
		     $gstModel = \app\models\ContractCompanyGst::find()->where(['company_id'=>$company, 'state_id'=>$state])->one();
		 if($model == "ShippingCompany")   
		     $gstModel = \app\models\BillingCompanyGst::find()->where(['company_id'=>$company, 'state_id'=>$state])->one();
		 $gst_no = $gstModel->gst_no;
		 
		 return $gst_no;
	 }
	 
    protected function findModel($id)
    {
        if (($model = District::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
