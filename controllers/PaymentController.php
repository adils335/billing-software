<?php

namespace app\controllers;

use Yii;
use app\models\Payment;
use app\models\Ledger;
use app\models\Search\Payment as PaymentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
	
/**
 * PaymentController implements the CRUD actions for Payment model.
 */
class PaymentController extends Controller
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
                    //'delete' => ['POST'],
                ],
            ],
        ];
    }
    
    public function actionSitewiseReport(){
        $formatter = Yii::$app->formatter;
        $searchModel = new PaymentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = $dataProvider->query->orderBy(['date'=>SORT_DESC,'id'=>SORT_DESC])->all();
        if( empty( $searchModel->from_date ) ){
            $searchModel->from_date = "01-04-".date("Y");
        }
        if( empty( $searchModel->to_date ) ){
            $searchModel->to_date = "31-03-".(date("Y")+1);
        }
        $where = ["BETWEEN","date", $formatter->asDate($searchModel->from_date,"php:Y-m-d"), $formatter->asDate($searchModel->to_date,"php:Y-m-d")];
        $pdfName = "sitewise-report";
        $data = [];
        //echo "<pre>";print_r( $data );die();
        if( $searchModel->from_account ){
            
           $data = Payment::find()
                        ->select(["contract_company_id","from_head","from_account","to_head","to_account","particular","date", "district_id","site_id",
                        "IF(from_account = $searchModel->from_account, amount, 0) AS debit","IF(to_account = $searchModel->from_account, amount, 0) as credit"])
                        ->andWhere(['OR',
                                        ['from_head'=>$searchModel->from_head,'from_account'=>$searchModel->from_account],
                                        ['to_head'=>$searchModel->from_head,'to_account'=>$searchModel->from_account]])
                        ->andWhere($where)
                        ->andFilterWhere(['district_id'=>$searchModel->district_id])
                        ->andFilterWhere(['site_id'=>$searchModel->site_id])
                        ->orderBy(['date'=>SORT_DESC,'id'=>SORT_DESC])
                        ->all();
                        //echo $data->createCommand()->getRawSql();die();
            $formatter = Yii::$app->formatter;
		    $tmp_path = Yii::getAlias('@webroot/'); 
		    $content = Yii::$app->controller->renderPartial("@app/views/pdf/".$pdfName, [
                                            'data' => $data,
                                        ]);						
		    $footer = Yii::$app->controller->renderPartial('@app/views/pdf/pdf-footer',[]);
		    $filename = "sitewise-report.pdf";
            $pdf = new \kartik\mpdf\Pdf([
                 'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
                 'format' => 'A4-L'
            ]); 
            $mpdf = $pdf->api; 
            $pdfTitle = ' Sitewise Report: '. $searchModel->fromAccount. " from ".$searchModel->from_date . " to ". $searchModel->to_date;
            $mpdf->SetHeader(Yii::t('app', $pdfTitle)); 
            $mpdf->setAutoBottomMargin ='stretch';
            $mpdf->SetHTMLFooter($footer); 
            $mpdf->WriteHtml($content); 
            $mpdf->Output($tmp_path.$filename,'I');
        }
        
        return $this->render('sitewise-report', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        
    }
    
    
	public function actionAjaxFromAccount($from_head,$company_id = null){
		Yii::$app->response->format = Response::FORMAT_JSON;
        if(Yii::$app->user->identity->isSelf()){
            if($from_head == Payment::FROM_EMPLOYEE){	   
		       $employees = \app\models\Employee::find()->andWhere(['status'=>\app\models\Employee::STATUS_ACTIVE,'id'=>Yii::$app->user->identity->employee->id])->orderBy('id')->all();
		       
		       $data = [['id' => '', 'text' => '']];
               foreach ($employees as $employee) {
                   $data[] = ['id' => $employee->id, 'text' => $employee->emp_code." ".$employee->emp_name];
               }
		    } 
		    return ['data'=>$data];
        }
		
		$where = [];
		if( $company_id ){
		     $where = $from_head == Payment::FROM_EMPLOYEE?['emp_company'=>$company_id]:$where = ['company_id'=>$company_id];
		}
		
		if($from_head == Payment::FROM_ACCOUNT){
		   $accounts = \app\models\BankAccount::find()->andWhere($where)->orderBy('id')->all();
		   
		   $data = [['id' => '', 'text' => '']];
           foreach ($accounts as $account) {
               $data[] = ['id' => $account->id, 'text' => $account->bank_name." ".$account->account_no];
           }
		}else if($from_head == Payment::FROM_EMPLOYEE){	   
		   $employees = \app\models\Employee::find()->andWhere(['status'=>\app\models\Employee::STATUS_ACTIVE])->andWhere($where)->orderBy('id')->all();
		   
		   $data = [['id' => '', 'text' => '']];
           foreach ($employees as $employee) {
               $data[] = ['id' => $employee->id, 'text' => $employee->emp_code." ".$employee->emp_name];
           }
		}else if($from_head == Payment::FROM_SITE_DUES){	   
		   $employees = \app\models\SiteDues::find()->andWhere(['status'=>\app\models\SiteDues::STATUS_ACTIVE])->andWhere($where)->orderBy('id')->all();
		   
		   $data = [['id' => '', 'text' => '']];
           foreach ($employees as $employee) {
               $data[] = ['id' => $employee->id, 'text' => $employee->code." ".$employee->name];
           }
		}else if($from_head == Payment::FROM_COMPANY_DUES){	   
		   $employees = \app\models\CompanyDues::find()->andWhere(['status'=>\app\models\CompanyDues::STATUS_ACTIVE])->andWhere($where)->orderBy('id')->all();
		   
		   $data = [['id' => '', 'text' => '']];
           foreach ($employees as $employee) {
               $data[] = ['id' => $employee->id, 'text' => $employee->code." ".$employee->name];
           }
		}else if($from_head == Payment::FROM_WORKER_VENDOR){	   
		   $employees = \app\models\WorkerVendor::find()->andWhere(['status'=>\app\models\WorkerVendor::STATUS_ACTIVE])->andWhere($where)->orderBy('id')->all();
		   
		   $data = [['id' => '', 'text' => '']];
           foreach ($employees as $employee) {
               $data[] = ['id' => $employee->id, 'text' => $employee->code." ".$employee->name];
           }
		}else if($from_head == Payment::FROM_CONTRACT_COMPANY){	   
		   $employees = \app\models\ContractCompany::find()->orderBy('id')->all();
		   
		   $data = [['id' => '', 'text' => '']];
           foreach ($employees as $employee) {
               $data[] = ['id' => $employee->id, 'text' => $employee->name];
           }
		}else if($from_head == Payment::FROM_VENDOR){	   
		   $employees = \app\models\Vendor::find()->andWhere(['status'=>\app\models\Vendor::STATUS_ACTIVE])->andWhere($where)->orderBy('id')->all();
		   
		   $data = [['id' => '', 'text' => '']];
           foreach ($employees as $employee) {
               $data[] = ['id' => $employee->id, 'text' => $employee->code." ".$employee->name];
           }
		}
        
        return ['data' => $data];
	}
	
	public function actionAjaxToAccount($to_head,$to_company){
		
		Yii::$app->response->format = Response::FORMAT_JSON;
		
		$where = [];
		if( $to_company ){
		     $where = $to_head == Payment::HEAD_EMPLOYEE_PERSONAL || $to_head == Payment::HEAD_EMPLOYEE_EXPENSE?['emp_company'=>$to_company]:$where = ['company_id'=>$to_company];
		}
		
		if($to_head == Payment::HEAD_SITE_EXPENSE){
		   
		   $data = [['id' => '0', 'text' => 'Expense']];
           
		}else if($to_head == Payment::HEAD_ACCOUNT){	   
		   $accounts = \app\models\BankAccount::find()->andWhere($where)->orderBy('id')->all();
		   
		   $data = [['id' => '', 'text' => '']];
           foreach ($accounts as $account) {
               $data[] = ['id' => $account->id, 'text' => $account->bank_name." ".$account->account_no];
           }
		}else if($to_head == Payment::HEAD_EMPLOYEE_PERSONAL){	   
		   $employees = \app\models\Employee::find()->andWhere(['status'=>\app\models\Employee::STATUS_ACTIVE])->andWhere($where)->orderBy('id')->all();
		   
		   $data = [['id' => '', 'text' => '']];
           foreach ($employees as $employee) {
               $data[] = ['id' => $employee->id, 'text' => $employee->emp_code." ".$employee->emp_name];
           }
		}else if($to_head == Payment::HEAD_EMPLOYEE_EXPENSE){	   
		   $employees = \app\models\Employee::find()->andWhere(['status'=>\app\models\Employee::STATUS_ACTIVE])->andWhere($where)->orderBy('id')->all();
		   
		   $data = [['id' => '', 'text' => '']];
           foreach ($employees as $employee) {
               $data[] = ['id' => $employee->id, 'text' => $employee->emp_code." ".$employee->emp_name];
           }
		}else if($to_head == Payment::HEAD_VENDOR_PAYMENT){	   
		   $vendors = \app\models\Vendor::find()->andWhere(['status'=>\app\models\Vendor::STATUS_ACTIVE])->andWhere($where)->orderBy('id')->all();
		   
		   $data = [['id' => '', 'text' => '']];
           foreach ($vendors as $vendor) {
               $data[] = ['id' => $vendor->id, 'text' => $vendor->code." ".$vendor->name];
           }
		}else if($to_head == Payment::HEAD_WORKER_VENDOR_PAYMENT){	   
		   $vendors = \app\models\WorkerVendor::find()->andWhere(['status'=>\app\models\WorkerVendor::STATUS_ACTIVE])->andWhere($where)->orderBy('id')->all();
		   
		   $data = [['id' => '', 'text' => '']];
           foreach ($vendors as $vendor) {
               $data[] = ['id' => $vendor->id, 'text' => $vendor->code." ".$vendor->name];
           }
		}else if($to_head == Payment::HEAD_WORKER_PAYMENT){	   
		   $vendors = \app\models\Worker::find()->andWhere(['status'=>\app\models\Worker::STATUS_ACTIVE])->andWhere($where)->orderBy('id')->all();
		   
		   $data = [['id' => '', 'text' => '']];
           foreach ($vendors as $vendor) {
               $data[] = ['id' => $vendor->id, 'text' => $vendor->code." ".$vendor->name];
           }
		}else if($to_head == Payment::HEAD_SITE_DUES){	   
		   $vendors = \app\models\SiteDues::find()->andWhere(['status'=>\app\models\SiteDues::STATUS_ACTIVE])->andWhere($where)->orderBy('id')->all();
		   
		   $data = [['id' => '', 'text' => '']];
           foreach ($vendors as $vendor) {
               $data[] = ['id' => $vendor->id, 'text' => $vendor->code." ".$vendor->name];
           }
		}else if($to_head == Payment::HEAD_COMPANY_DUES){	   
		   $vendors = \app\models\CompanyDues::find()->andWhere(['status'=>\app\models\CompanyDues::STATUS_ACTIVE])->andWhere($where)->orderBy('id')->all();
		   
		   $data = [['id' => '', 'text' => '']];
           foreach ($vendors as $vendor) {
               $data[] = ['id' => $vendor->id, 'text' => $vendor->code." ".$vendor->name];
           }
		}
        
        return ['data' => $data];
	}
	
    /**
     * Lists all Payment models.
     * @return mixed
     */
    public function actionIndex()
    {
        ini_set('memory_limit', '-1');
        $searchModel = new PaymentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if(Yii::$app->user->identity->isSelf()){
            $dataProvider->query->where(['from_head'=>Payment::FROM_EMPLOYEE,'from_account'=>Yii::$app->user->identity->employee->id])
                                ->orWhere(['to_head'=>Payment::HEAD_EMPLOYEE_PERSONAL,'to_account'=>Yii::$app->user->identity->employee->id])
                                ->orWhere(['to_head'=>Payment::HEAD_EMPLOYEE_EXPENSE,'to_account'=>Yii::$app->user->identity->employee->id]);
        }
        
        $dataProvider->query->orderBy(['id'=>SORT_DESC]);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Payment model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionHistory($ref_no)
    {
		$model = Payment::find()->where(['ref_no'=>$ref_no])->one();
		
		$payment = Payment::find()->where(['ref_no'=>$ref_no])->orderBy(['id'=>SORT_ASC])->all();
		
        return $this->render('history', [
            'model' => $model,
			'payment' => $payment,
        ]);
    }

    /**
     * Displays a single Payment model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($ref_no)
    {
		$model = Payment::find()->where(['ref_no'=>$ref_no])->one();
		
		$payment = Payment::find()->where(['ref_no'=>$ref_no])->orderBy(['id'=>SORT_ASC])->all();
		
        return $this->render('view', [
            'model' => $model,
			'payment' => $payment,
        ]);
    }

    /**
     * Creates a new Payment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = [new Payment()];
        
        if (Yii::$app->request->post()) {
			
			$formatter = Yii::$app->formatter;
			
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction(); 
			$paymentData = Yii::$app->request->post()['Payment'];
			$date = $paymentData[0]['date'];
            $paymentData = array_filter($paymentData, 'is_int', ARRAY_FILTER_USE_KEY);
			
			$flag = true;
			
			foreach($paymentData as $key=>$value){
					
				$paymentModel = new Payment;
				$loadArray['Payment'] = $value;
				$paymentModel->load($loadArray);
				$paymentModel->ref_no = $paymentModel->refNo();
				$paymentModel->date = $formatter->asDate($date,"php:Y-m-d");
				
				if($paymentModel->save()){
					
					if(! $paymentModel->saveLedger($paymentModel)) $flag = false;
					
				}else{
					
					$flag = false;
					
				}
				
			}
			
			if($flag){
				
				$transaction->commit();
				\Yii::$app->session->setFlash('success',  Yii::t('app', 'Ref No# <strong>{session}/{ref_no}</strong>', ['session'=>$paymentModel->session,'ref_no' => $paymentModel->ref_no]));
                return $this->redirect(['index']);
				
			}else{
				$transaction->rollback();
			}
			
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Payment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($ref_no)
    {
        $model = Payment::find()->where(['ref_no'=>$ref_no])->andWhere(['!=','status',Payment::STATUS_DELETE])->orderBy(['id'=>SORT_ASC])->all();
		
        if (Yii::$app->request->post()) {
			
			$formatter = Yii::$app->formatter;
			
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction(); 
			$paymentData = Yii::$app->request->post()['Payment'];
			$date = $paymentData[0]['date'];
            $paymentData = array_filter($paymentData, 'is_int', ARRAY_FILTER_USE_KEY);

			//Delete payment 
			$paymentId = array_column($paymentData, 'id');
            $deletedPayment = Payment::find()->select(['id'])->where(['ref_no'=>$ref_no])->andWhere(['NOT IN','id',$paymentId])->asArray()->all();
            $deletedPaymentId = array_column($deletedPayment, 'id');
            
			//Delete payment 

			$flag = true;
			
			foreach($paymentData as $key=>$value){
				
                if(!empty($value['id']))	
					$paymentModel = Payment::findOne($value['id']);
                else $paymentModel = new Payment;
				
				$loadArray['Payment'] = $value;
				$paymentModel->load($loadArray);
				$paymentModel->ref_no = $paymentModel->refNo($ref_no);
				$paymentModel->date = $formatter->asDate($date,"php:Y-m-d");
				
				if($paymentModel->save()){
					
					if(! $paymentModel->saveLedger($paymentModel)) $flag = false;
					
				}else{
					
					$flag = false;
					
				}
				
			}
			
			if($flag){

				Payment::updateAll(['status'=>Payment::STATUS_DELETE],['id'=>$deletedPaymentId]);
                Ledger::updateAll(['status'=>Ledger::STATUS_DELETE],['transaction_id'=>$deletedPaymentId,'entry_from'=>Ledger::FROM_PAYMENT_PAGE]);

				$transaction->commit();
				\Yii::$app->session->setFlash('success',  Yii::t('app', 'Updated Ref No# <strong>{session}/{ref_no}</strong>', ['session'=>$paymentModel->session,'ref_no' => $paymentModel->ref_no]));
                return $this->redirect(['index']);
				//return $this->redirect(Yii::$app->request->referrer);
			}else{
				$transaction->rollback();
			}
			
        }

        return $this->render('update', [
            'model' => $model,
			'ref_no' => $ref_no,
        ]);
    }

    /**
     * Deletes an existing Payment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($ref_no)
    {
		$payment = Payment::find()->select(['id'])->where(['ref_no'=>$ref_no])->asArray()->all();
		$payment = array_column($payment, 'id');
        Payment::updateAll(['status'=>Payment::STATUS_DELETE],['ref_no'=>$ref_no]);
		Ledger::updateAll(['status'=>Ledger::STATUS_DELETE],['transaction_id'=>$payment, 'entry_from'=>Ledger::FROM_PAYMENT_PAGE]);
        return $this->redirect(['index']);
    }

    /**
     * Finds the Payment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Payment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Payment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
