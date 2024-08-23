<?php

namespace app\models;

use Yii;
use yii\helpers\Url;
use \app\models\base\Ledger as BaseLedger;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ledger".
 */
class Ledger extends BaseLedger
{
    public $ledger; 
    public $vendor; 

    const STATUS_UNVERIFIED = 1;
	const STATUS_VERIFIED = 2;
	const STATUS_DELETE = 3;
		
	const INOUT_CREDIT = 1;
	const INOUT_DEBIT = 2;
	
	const TYPE_ACCOUNT = 1;
	const TYPE_EMPLOYEE = 2;
	const TYPE_VENDOR = 3;
	const TYPE_WORKER_VENDOR = 4;
	const TYPE_WORKER = 5;
	const TYPE_SITE_DUES = 6;
	const TYPE_COMPANY_DUES = 7;
	const TYPE_CONTRACT_COMPANY = 8;
	
	const FROM_EMPLOYEE_PAGE = 1;
	const FROM_VENDOR_PAGE = 2;
	const FROM_VENDOR_BILL_PAGE = 3;
	const FROM_ACCOUNT_PAGE = 4;
	const FROM_WORKER_VENDOR_PAGE = 5;
	const FROM_WORKER_PAGE = 6;
	const FROM_SITE_DUES_PAGE = 7;
	const FROM_COMPANY_DUES_PAGE = 8;
	const FROM_PAYMENT_PAGE = 9;
	const FROM_EMPLOYEE_SALARY_PAGE = 10;
	const FROM_WORKER_SALARY_PAGE = 11;
	const FROM_EMPLOYEE_EXTRA_SALARY_PAGE = 12;


	const ACCOUNT_PERSONAL = 1;
	const ACCOUNT_EXPENSE = 2;
	
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                # custom behaviors
            ]
        );
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                ['vendor','safe']
            ]
        );
    }
	
	public static function buildEntryFrom(){
			return [
				self::FROM_EMPLOYEE_PAGE =>'Create Employee',
				self::FROM_VENDOR_PAGE	=>'Create Vendor',
				self::FROM_VENDOR_BILL_PAGE	=>'Vendor Bill',
				self::FROM_ACCOUNT_PAGE =>'Create Account',
				self::FROM_WORKER_VENDOR_PAGE	=>'Create Worker Vendor',
				self::FROM_WORKER_PAGE	=>'Create Worker',
				self::FROM_SITE_DUES_PAGE =>'Create Site Dues Member',
				self::FROM_COMPANY_DUES_PAGE	=>'Create Company Dues Member',
				self::FROM_PAYMENT_PAGE	=>'Payment',
		];
	}
	public  function getEntryFromLabel(){
		
			if(isset(self::buildStatus()[$this->status])){
				return self::buildStatus()[$this->status];
			}
		
	}
	
	public static function buildStatus(){
			return [
				self::STATUS_UNVERIFIED =>'Unverified',
				self::STATUS_VERIFIED	=>'Verified',
				self::STATUS_DELETE	=>'Deleted',
		];
	}
	public  function getStatusLabel(){
		
			if(isset(self::buildStatus()[$this->status])){
				return self::buildStatus()[$this->status];
			}
		
	}
	
	public static function buildInout(){
			return [
				self::INOUT_CREDIT =>'Credit',
				self::INOUT_DEBIT	=>'Debit',
		];
	}
	public  function getInoutLabel(){
		
			if(isset(self::buildInout()[$this->inout])){
				return self::buildInout()[$this->inout];
			}
		
	}
	
	public static function buildType(){
			return [
				self::TYPE_ACCOUNT =>'Account',
				self::TYPE_EMPLOYEE	=>'Employe',
				self::TYPE_VENDOR	=>'Vendor',
				self::TYPE_WORKER_VENDOR	=>'Worker Vendor',
				self::TYPE_WORKER	=>'Worker',
				self::TYPE_SITE_DUES	=>'Site Dues',
				self::TYPE_COMPANY_DUES	=>'Company Dues',
				self::TYPE_CONTRACT_COMPANY	=>'Contract Company',
		];
	}
	
	public  function getTypeLabel(){
		
			if(isset(self::buildType()[$this->type])){
				return self::buildType()[$this->type];
			}
		
	}
	
	public function getAccountName(){
		
		if($this->type == Self::TYPE_ACCOUNT){
			
			$account = \app\models\BankAccount::findOne($this->account);
			return $account->bank_name."-".$account->account_no;
			
		}elseif($this->type == Self::TYPE_EMPLOYEE){
			
			$account = \app\models\Employee::findOne($this->account);
			return $account->emp_name."-".$account->emp_code;
			
		}elseif($this->type == Self::TYPE_VENDOR){
			
			$account = \app\models\Vendor::findOne($this->account);
			return $account->name."-".$account->code;
			
		}elseif($this->type == Self::TYPE_WORKER_VENDOR){
			
			$account = \app\models\WorkerVendor::findOne($this->account);
			return $account->name."-".$account->code;
			
		}elseif($this->type == Self::TYPE_WORKER){
			
			$account = \app\models\Worker::findOne($this->account);
			return $account->name."-".$account->code;
			
		}elseif($this->type == Self::TYPE_SITE_DUES){
			
			$account = \app\models\SiteDues::findOne($this->account);
			return $account->name."-".$account->code;
			
		}elseif($this->type == Self::TYPE_COMPANY_DUES){
			
			$account = \app\models\CompanyDues::findOne($this->account);
			return $account->name."-".$account->code;
			
		}elseif($this->type == Self::TYPE_CONTRACT_COMPANY){
			
			$account = \app\models\ContractCompany::findOne($this->account);
			return $account->name;
			
		}
		
	}
	
	public function getPaymentLabel(){
	  
	  $payment = $this->transaction;
	  if(!$payment) return;
      	  
	  if($this->inout == Self::INOUT_CREDIT){
		  
		if($payment->from_head == $payment::FROM_ACCOUNT){
			
			$account = \app\models\BankAccount::findOne($payment->from_account);
			return $account->bank_name."-".$account->account_no;
			
		}elseif($payment->from_head == $payment::FROM_EMPLOYEE){
			
			$account = \app\models\Employee::findOne($payment->from_account);
			return $account->emp_name."-".$account->emp_code;
			
		}
		
	  }else if($this->inout == Self::INOUT_DEBIT){
		  
		if($payment->to_head == $payment::HEAD_ACCOUNT){
			
			$account = \app\models\BankAccount::findOne($payment->to_account);
			return $account->bank_name."-".$account->account_no;
			
		}elseif($payment->to_head == $payment::HEAD_EMPLOYEE_PERSONAL || $payment->to_head == $payment::HEAD_EMPLOYEE_EXPENSE){
			
			$account = \app\models\Employee::findOne($payment->to_account);
			return $account->emp_name."-".$account->emp_code;
			
		}elseif($payment->to_head == $payment::HEAD_VENDOR_PAYMENT){
			
			$account = \app\models\Vendor::findOne($payment->to_account);
			return $account->name."-".$account->code;
			
		}
		  
	  }
	  
	}
	
	public function saveLedger($date ,$account, $transaction_id, $particular, $credit, $debit, $inout, $type, $company_id, $session, $ef = null){
		
		if($particular == "Expense Opening Balance" || $particular == "Personal Opening Balance" || $particular == "Opening Balance" )
		    $ledger = Ledger::find()->where(['transaction_id'=>$transaction_id, 'entry_from'=>$ef, 'particular'=>$particular])->one();
		else
		    $ledger = Ledger::find()->where(['transaction_id'=>$transaction_id,'inout'=>$inout,'entry_from'=>$ef])->one();
		
		if(empty($ledger))
		   $ledger = new Ledger;
	         $loadArray['Ledger'] = [
			                           'date' => $date,
		                               'account' => $account,
									   'transaction_id' => $transaction_id,
									   'particular' => $particular,
									   'credit' => $credit,
									   'debit' => $debit,
									   'inout' => $inout,
									   'type' => $type,
									   'entry_from' => $ef,
									   'session' => $session,
									   'company_id' => $company_id
		                             ];
									 
		$ledger->load($loadArray);
		
		if($ledger->save())
			return true;
		else 
		{
	      foreach($ledger->getErrors() as $error){
              \Yii::$app->session->setFlash('error', $error[0]);
	      }
	      return false;
		};
		
	}
	
	public function getParticularLabel(){
	   
	   $particular = '';

	   if(Ledger::FROM_PAYMENT_PAGE == $this->entry_from){

	      $payment = $this->transaction;
	   	  if(Ledger::INOUT_CREDIT == $this->inout){

	   	  	 if($payment::HEAD_SITE_EXPENSE == $payment->to_head){
	             $particular = $this->particular." ".$payment->particular;
	         }else{
	             $particular = $this->particular." ".$payment->fromAccount." ".$payment->particular;
	         }

	   	  }else{
//echo $payment->to_head."-".$payment->from_head."-".$payment->toAccount."-".$payment->fromAccount."<br>";
	   	  	 if($payment::HEAD_SITE_EXPENSE == $payment->to_head){
	             $particular = $this->particular." ".$payment->particular;
	         }else{
	             $particular = $this->particular." ".$payment->toAccount." ".$payment->particular;
	         }

	   	  }

	   }elseif(Ledger::FROM_VENDOR_BILL_PAGE == $this->entry_from){

	      $vendorBill = $this->vendorBill;
	      $particular = $this->particular." ".$vendorBill->session."/".sprintf("%02d",$vendorBill->bill_no).", Invoice:".$vendorBill->invoice_no;
	           
	   }elseif( Ledger::FROM_EMPLOYEE_EXTRA_SALARY_PAGE == $this->entry_from ){
	       $particular = $this->particular." for leave till ".Yii::$app->formatter->asDate($this->extraSalary->month,"php:d-m-Y");
	   }else{

	      $particular = $this->particular;

	   }
	   
	   return $particular;
	       
	}
    
	public function account(){
		
		$data[''] = [];
		
		if(!isset($this->status)){
		    $this->status = 2;
		}
		
		$where = [];
		if($this->type != Ledger::TYPE_ACCOUNT){
		    $where['status'] = $this->status;
		}//var_dump(in_array($this->type,[Ledger::TYPE_VENDOR,Ledger::TYPE_EMPLOYEE]));die();
		if($this->status == 1 && $this->type != Ledger::TYPE_ACCOUNT && !in_array($this->type,[Ledger::TYPE_VENDOR,Ledger::TYPE_EMPLOYEE])){
		    $where['status'] = 2;
		}elseif($this->status == 2 && $this->type != Ledger::TYPE_ACCOUNT && !in_array($this->type,[Ledger::TYPE_VENDOR,Ledger::TYPE_EMPLOYEE])){
		    $where['status'] = 1;
		}
		
		if($this->company_id){
		  if($this->type == Ledger::TYPE_EMPLOYEE){
		      $where['emp_company'] = $this->company_id;
		  }else{
		      $where['company_id'] = $this->company_id;
		  }
		}
		//echo "<pre>";print_r($where);die();
		if($this->type == Ledger::TYPE_ACCOUNT){	   
		   $accounts = \app\models\BankAccount::find()->where($where)->orderBy('id')->all();
		   
           foreach ($accounts as $account) {
               $data[$account->id] = $account->bank_name."-".$account->account_no;
           }
		}else if($this->type == Ledger::TYPE_EMPLOYEE || $this->type == Ledger::TYPE_EMPLOYEE){	   
		   $employees = \app\models\Employee::find()->where($where)->orderBy('id')->all();
		   
           foreach ($employees as $employee) {
               $data[$employee->id] = $employee->emp_code." ".$employee->emp_name;
           }
		}else if($this->type == Ledger::TYPE_VENDOR){	   
		   $vendors = \app\models\Vendor::find()->where($where)->orderBy('id')->all();
		   
           foreach ($vendors as $vendor) {
               $data[$vendor->id] = $vendor->code." ".$vendor->name;
           }
		}else if($this->type == Ledger::TYPE_WORKER_VENDOR){	   
		   $vendors = \app\models\WorkerVendor::find()->where($where)->orderBy('id')->all();
		   
           foreach ($vendors as $vendor) {
               $data[$vendor->id] = $vendor->code." ".$vendor->name;
           }
		}else if($this->type == Ledger::TYPE_WORKER){	   
		   if(isset($this->vendor)) 
		   $vendors = \app\models\Worker::find()->where($where)->andWhere(['worker_vendor_id'=>$this->vendor])->orderBy('id')->all();
		   else
		   $vendors = \app\models\Worker::find()->where($where)->orderBy('id')->all();
		   //echo "<pre>";print_r($where);die();
		   //echo $vendor->createCommand()->getRawSql();die();
           foreach ($vendors as $vendor) {
               $data[$vendor->id] = $vendor->code." ".$vendor->name;
           }
		}else if($this->type == Ledger::TYPE_SITE_DUES){	   
		   $vendors = \app\models\SiteDues::find()->where($where)->orderBy('id')->all();
		   
           foreach ($vendors as $vendor) {
               $data[$vendor->id] = $vendor->code." ".$vendor->name;
           }
		}else if($this->type == Ledger::TYPE_COMPANY_DUES){	   
		   $vendors = \app\models\CompanyDues::find()->where($where)->orderBy('id')->all();
		   
           foreach ($vendors as $vendor) {
               $data[$vendor->id] = $vendor->code." ".$vendor->name;
           }
		}else if($this->type == Ledger::TYPE_CONTRACT_COMPANY){	   
		   $vendors = \app\models\ContractCompany::find()->orderBy('id')->all();
		   
		   $data = [['id' => '', 'text' => '']];
           foreach ($vendors as $vendor) {
               $data[$vendor->id] = $vendor->name;
           }
		}
        
        return $data;
	}
	
	
	public function generateBalanceReportPdf($search,$model){
		$formatter = Yii::$app->formatter;
		
		$tmp_path = Yii::getAlias('@webroot/agreement-bill/'); 
		$content = Yii::$app->controller->renderPartial("@app/views/ledger/balance-report-pdf", [
                                            'model' => $model,
                                            'gsts' => $gsts,
                                        ]);
		//echo $content;die();								
		$footer = Yii::$app->controller->renderPartial('@app/views/ledger/pdf-footer',[
		    'search' => $search,
            'model' => $model,
        ]);
		
		$filename = "balance-report.pdf";
        $pdf = new \kartik\mpdf\Pdf([
        'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
        ]); 
        $mpdf = $pdf->api; 
        $mpdf->SetHeader(Yii::t('app', $this->getTypeLabel().' Balance Report')); 
        $mpdf->setAutoBottomMargin ='stretch';
        $mpdf->SetHTMLFooter($footer); 
        $mpdf->WriteHtml($content); 
        $mpdf->Output($tmp_path.$filename,'I'); 
		
	}
	
	public function paymentEditLink(){
	    $entry_form = $this->entry_from;
	    $id = $this->transaction_id;
	    $type = $this->type;
	    $account = $this->account;
	    $url = "";
	    switch( $entry_form ){
	        case Self::FROM_EMPLOYEE_PAGE:
	             $url = Url::to(['employee/update','id' => $id]);
	             break;
	        case Self::FROM_VENDOR_PAGE:
	             $url = Url::to(['vendor/update','id' => $id]);
	             break;
	        case Self::FROM_VENDOR_BILL_PAGE:
	             $url = Url::to(['vendor-bill/update','id' => $id]);
	             break;
	        case Self::FROM_ACCOUNT_PAGE:
	             $url = Url::to(['back-account/update','id' => $id]);
	             break;
	        case Self::FROM_WORKER_VENDOR_PAGE:
	             $url = Url::to(['worker-vendor/update','id' => $id]);
	             break;
	        case Self::FROM_WORKER_PAGE:
	             $url = Url::to(['worker/update','id' => $id]);
	             break;
	        case Self::FROM_SITE_DUES_PAGE:
	             $url = Url::to(['site-dues/update','id' => $id]);
	             break;
	        case Self::FROM_COMPANY_DUES_PAGE:
	             $url = Url::to(['company-dues/update','id' => $id]);
	             break;
	        case Self::FROM_PAYMENT_PAGE:
	             $payment = \app\models\Payment::findOne($id);
	             $url = Url::to(['payment/update','ref_no' => $payment->ref_no]);
	             break;
	        case Self::FROM_EMPLOYEE_SALARY_PAGE:
	             $url = Url::to(['employee/update','id' => $id]);
	             break;
	        case Self::FROM_WORKER_SALARY_PAGE:
	             $url = Url::to(['worker/update','id' => $id]);
	             break;
	        case Self::FROM_EMPLOYEE_EXTRA_SALARY_PAGE:
	             $url = Url::to(['employee/extra-salary-form','id' => $id]);
	             break;
	    }
	    return $url;
	    
	}
	
	public function getSiteName(){
	    $entry_form = $this->entry_from;
	    $id = $this->transaction_id;
	    $type = $this->type;
	    $account = $this->account;
	    $siteName = "";
	    switch( $entry_form ){
	        case Self::FROM_EMPLOYEE_PAGE:
	             $siteName = "";
	             break;
	        case Self::FROM_VENDOR_PAGE:
	             $siteName = "";
	             break;
	        case Self::FROM_VENDOR_BILL_PAGE:
	             $siteName = "";
	             break;
	        case Self::FROM_ACCOUNT_PAGE:
	             $siteName = "";
	             break;
	        case Self::FROM_WORKER_VENDOR_PAGE:
	             $siteName = "";
	             break;
	        case Self::FROM_WORKER_PAGE:
	             $siteName = "";
	             break;
	        case Self::FROM_SITE_DUES_PAGE:
	             $siteName = "";
	             break;
	        case Self::FROM_COMPANY_DUES_PAGE:
	             $siteName = "";
	             break;
	        case Self::FROM_PAYMENT_PAGE:
	             $payment = \app\models\Payment::findOne($id);
	             $siteName = $payment->site->name;
	             break;
	        case Self::FROM_EMPLOYEE_SALARY_PAGE:
	             $siteName = "";
	             break;
	        case Self::FROM_WORKER_SALARY_PAGE:
	             $siteName = "";
	             break;
	       case Self::FROM_EMPLOYEE_EXTRA_SALARY_PAGE:    
	             $siteName = "";
	             break;  
	    }
	    return $siteName;
	    
	}
	
	public function getAccounts($type,$company_id){
		//$this = new Self;
		if( !$type || !$company_id){
		    return "";
		}
		
		$this->status = 2;
		$this->type = $type;
		$this->company_id = $company_id;
		
		$where = [];
		if($type != Ledger::TYPE_ACCOUNT){
		    $where['status'] = $this->status;
		}
		if($this->status == 1 && $this->type != Ledger::TYPE_ACCOUNT && !in_array($this->type,[Ledger::TYPE_VENDOR,Ledger::TYPE_EMPLOYEE])){
		    $where['status'] = 2;
		}elseif($this->status == 2 && $this->type != Ledger::TYPE_ACCOUNT && !in_array($this->type,[Ledger::TYPE_VENDOR,Ledger::TYPE_EMPLOYEE])){
		    $where['status'] = 1;
		}
		
		if($this->company_id){
		  if($this->type == Ledger::TYPE_EMPLOYEE){
		      $where['emp_company'] = $this->company_id;
		  }else{
		      $where['company_id'] = $this->company_id;
		  }
		}
		$data = [];
		if($this->type == Ledger::TYPE_ACCOUNT){	   
		   $accounts = \app\models\BankAccount::find()->where($where)->orderBy('id')->all();
		   
           foreach ($accounts as $account) {
               $data[] = $account->id;
           }
		}else if($this->type == Ledger::TYPE_EMPLOYEE || $this->type == Ledger::TYPE_EMPLOYEE){	   
		   $employees = \app\models\Employee::find()->where($where)->orderBy('id')->all();
		   
           foreach ($employees as $employee) {
               $data[] = $employee->id;
           }
		}else if($this->type == Ledger::TYPE_VENDOR){	   
		   $vendors = \app\models\Vendor::find()->where($where)->orderBy('id')->all();
		   
           foreach ($vendors as $vendor) {
               $data[] = $vendor->id;
           }
		}else if($this->type == Ledger::TYPE_WORKER_VENDOR){	   
		   $vendors = \app\models\WorkerVendor::find()->where($where)->orderBy('id')->all();
		   
           foreach ($vendors as $vendor) {
               $data[] = $vendor->id;
           }
		}else if($this->type == Ledger::TYPE_WORKER){	   
		   if(isset($this->vendor)) 
		   $vendors = \app\models\Worker::find()->where($where)->andWhere(['worker_vendor_id'=>$this->vendor])->orderBy('id')->all();
		   else
		   $vendors = \app\models\Worker::find()->where($where)->orderBy('id')->all();
		   
           foreach ($vendors as $vendor) {
               $data[] = $vendor->id;
           }
		}else if($this->type == Ledger::TYPE_SITE_DUES){	   
		   $vendors = \app\models\SiteDues::find()->where($where)->orderBy('id')->all();
		   
           foreach ($vendors as $vendor) {
               $data[] = $vendor->id;
           }
		}else if($this->type == Ledger::TYPE_COMPANY_DUES){	   
		   $vendors = \app\models\CompanyDues::find()->where($where)->orderBy('id')->all();
		   
           foreach ($vendors as $vendor) {
               $data[] = $vendor->id;
           }
		}else if($this->type == Ledger::TYPE_CONTRACT_COMPANY){	   
		   $vendors = \app\models\ContractCompany::find()->orderBy('id')->all();
		   
		   $data = [['id' => '', 'text' => '']];
           foreach ($vendors as $vendor) {
               $data[] = $vendor->id;
           }
		}
        
        return $data;
	}
	 
	public function hasUnverify( $account_type ){
        $employeeParticular = ['Personal Payment by Account','Personal Payment by Employee','Salary','Extra Salary'];
        $query = self::find();
        $query->andWhere(['!=','status',self::STATUS_DELETE]);
        $query->andWhere(['status'=>self::STATUS_UNVERIFIED]);
        $query->andWhere(['NOT IN','particular',['Personal Opening Balance','Expense Opening Balance','Opening Balance']]);
        $query->andWhere(['account' => $this->account,'type'=>self::TYPE_EMPLOYEE]);
        if($account_type == self::ACCOUNT_PERSONAL){
		    $query->andWhere(['IN','particular',$employeeParticular]);
		}else if($account_type == self::ACCOUNT_EXPENSE){
            $query->andWhere(['NOT IN','particular',$employeeParticular]);
		}
		//echo $query->createCommand()->getRawSql();die;
        return $query->exists();
	}
	
}
