<?php

namespace app\controllers;

use Yii;
use app\models\Ledger;
use app\models\Payment;
use app\models\Search\Ledger as LedgerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * LedgerController implements the CRUD actions for Ledger model.
 */
class LedgerController extends Controller
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
    
    public function actionBalanceReport(){
        $params = Yii::$app->request->queryParams;
        $searchModel = new LedgerSearch;
        $searchModel->load($params);
        $ledger = new Ledger;
        $ledger->load($params);
        $models = $ledger->account();
        if(Yii::$app->user->identity->isSelf()){
            $models = array(Yii::$app->user->identity->employee->id =>Yii::$app->user->identity->employee->id);
        }
        $models = array_filter($models);
        $salaryReports = NULL;
        foreach($models as $index => $model){
            $ledgerSearch = new LedgerSearch;
            $params['Ledger']['account'] = $index;
            $filterledger = $ledgerSearch->dataSearch($params);
            $salaryReports[] = ['account'=>$model,'ledger'=>$filterledger];
        }
        if($params['ispdf']){
            $ledger->generateBalanceReportPdf($searchModel,$salaryReports);
        }
       // echo "<pre>";print_r($salaryReports);die();
        return $this->render('balance-report',[
           'model' => $salaryReports,  
           'searchModel' => $searchModel
        ]);
    }
    /**
     * Deletes an existing Payment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionVerify($id = null)
    {
        if( Yii::$app->request->isPost ){
            $ids = array_values(Yii::$app->request->post()['data']);
            Ledger::updateAll(['status'=>Ledger::STATUS_VERIFIED],['IN','id',$ids]);
            //echo "<pre>";print_r( Ledger::find()->where(['status'=>1])->asArray()->all() );die();
        }else{
            $ledger = Ledger::findOne($id);
            $ledger->status = Ledger::STATUS_VERIFIED;
            $ledger->save();
        }
        \Yii::$app->session->setFlash('success', 'Payment Verified');
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Lists all Ledger models.
     * @return mixed
     */
     
    public function actionUnverifyLedger(){
        
        $searchModel = new ledgerSearch();
        $query = Yii::$app->request->queryParams;
        $query['Ledger']['status'] = Ledger::STATUS_UNVERIFIED;
        $dataProvider = $searchModel->search($query);
        //echo "<pre>";print_r( $dataProvider->query->all() );die();
        $dataProvider->pagination = false;
        $data = $searchModel->unverifyData($query);
        if( $dataProvider->totalCount <= 0 ){
           return $this->redirect(['unverify-list','Ledger[type]'=>$query['Ledger']['type'],'Ledger[company_id]'=>$query['Ledger']['company_id']]);
        }
        return $this->render('unverify-ledger', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'data' => $data,
        ]);

    }

    /**
     * Lists all Ledger models.
     * @return mixed
     */
     
    public function actionUnverifyList(){
        
        $searchModel = new ledgerSearch();
        $query = Yii::$app->request->queryParams;
        $query['Ledger']['status'] = Ledger::STATUS_UNVERIFIED;
        $dataProvider = $searchModel->unverifyList(Yii::$app->request->queryParams);

        return $this->render('unverify-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    public function actionAjaxAccountByVendor($vendor){
        
        Yii::$app->response->format = Response::FORMAT_JSON;

        $vendors = \app\models\Worker::find()->where(['worker_vendor_id'=>$vendor])->andWhere(['status'=>\app\models\Worker::STATUS_ACTIVE])->orderBy('id')->all();
        
        $data = [['id' => '', 'text' => '']];
        foreach ($vendors as $vendor) {
            $data[] = ['id' => $vendor->id, 'text' => $vendor->code." ".$vendor->name];
        }
                
        return ['data' => $data];
    }
     
	public function actionAjaxAccountType($type,$company_id){
		
		Yii::$app->response->format = Response::FORMAT_JSON;
		
		if($type == Ledger::TYPE_ACCOUNT){	   
		   $accounts = \app\models\BankAccount::find()->where(['company_id'=>$company_id])->orderBy('id')->all();
		   
		   $data = [['id' => '', 'text' => '']];
           foreach ($accounts as $account) {
               $data[] = ['id' => $account->id, 'text' => $account->bank_name . "-" .$account->account_no];
           }
		}else if($type == Ledger::TYPE_EMPLOYEE){	   
		   $employees = \app\models\Employee::find()->where(['emp_company'=>$company_id])->andWhere(['status'=>\app\models\Employee::STATUS_ACTIVE])->orderBy('id')->all();
		   
		   $data = [['id' => '', 'text' => '']];
           foreach ($employees as $employee) {
               $data[] = ['id' => $employee->id, 'text' => $employee->emp_code." ".$employee->emp_name];
           }
		}else if($type == Ledger::TYPE_VENDOR){	   
		   $vendors = \app\models\Vendor::find()->where(['company_id'=>$company_id])->andWhere(['status'=>\app\models\Vendor::STATUS_ACTIVE])->orderBy('id')->all();
		   
		   $data = [['id' => '', 'text' => '']];
           foreach ($vendors as $vendor) {
               $data[] = ['id' => $vendor->id, 'text' => $vendor->code." ".$vendor->name];
           }
		}else if($type == Ledger::TYPE_WORKER_VENDOR){	   
		   $vendors = \app\models\WorkerVendor::find()->where(['company_id'=>$company_id])->andWhere(['status'=>\app\models\WorkerVendor::STATUS_ACTIVE])->orderBy('id')->all();
		   
		   $data = [['id' => '', 'text' => '']];
           foreach ($vendors as $vendor) {
               $data[] = ['id' => $vendor->id, 'text' => $vendor->code." ".$vendor->name];
           }
		}else if($type == Ledger::TYPE_WORKER){	   
		   $vendors = \app\models\Worker::find()->where(['company_id'=>$company_id])->andWhere(['status'=>\app\models\Worker::STATUS_ACTIVE])->orderBy('id')->all();
		   
		   $data = [['id' => '', 'text' => '']];
           foreach ($vendors as $vendor) {
               $data[] = ['id' => $vendor->id, 'text' => $vendor->code." ".$vendor->name];
           }
		}else if($type == Ledger::TYPE_SITE_DUES){	   
		   $vendors = \app\models\SiteDues::find()->where(['company_id'=>$company_id])->andWhere(['status'=>\app\models\SiteDues::STATUS_ACTIVE])->orderBy('id')->all();
		   
		   $data = [['id' => '', 'text' => '']];
           foreach ($vendors as $vendor) {
               $data[] = ['id' => $vendor->id, 'text' => $vendor->code." ".$vendor->name];
           }
		}else if($type == Ledger::TYPE_COMPANY_DUES){	   
		   $vendors = \app\models\CompanyDues::find()->where(['company_id'=>$company_id])->andWhere(['status'=>\app\models\CompanyDues::STATUS_ACTIVE])->orderBy('id')->all();
		   
		   $data = [['id' => '', 'text' => '']];
           foreach ($vendors as $vendor) {
               $data[] = ['id' => $vendor->id, 'text' => $vendor->code." ".$vendor->name];
           }
		}else if($type == Ledger::TYPE_CONTRACT_COMPANY){	   
		   $vendors = \app\models\ContractCompany::find()/*->andWhere(['status'=>\app\models\ContractCompany::STATUS_ACTIVE])*/->orderBy('id')->all();
		   
		   $data = [['id' => '', 'text' => '']];
           foreach ($vendors as $vendor) {
               $data[] = ['id' => $vendor->id, 'text' => $vendor->name];
           }
		}
        
        return ['data' => $data];
	}
	 
    public function actionIndex()
    {
        $searchModel = new LedgerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $data = $searchModel->dataSearch(Yii::$app->request->queryParams);
		$formatter = \Yii::$app->formatter;
        $dataProvider->pagination = false;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'data' => $data,
        ]);
    }

    /**
     * Displays a single Ledger model.
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
     * Creates a new Ledger model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Ledger();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Ledger model.
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
     * Deletes an existing Ledger model.
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
     * Finds the Ledger model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ledger the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ledger::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
