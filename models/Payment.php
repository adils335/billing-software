<?php

namespace app\models;

use Yii;
use \app\models\base\Payment as BasePayment;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "payment".
 */
class Payment extends BasePayment
{
    const STATUS_UNVERIFIED = 1;
	const STATUS_VERIFIED = 2;
	const STATUS_DELETE = 3;
		
	const FROM_ACCOUNT = 1;
	const FROM_EMPLOYEE = 2;
	const FROM_SITE_DUES = 3;
	const FROM_COMPANY_DUES = 4;
	const FROM_WORKER_VENDOR = 5;
	const FROM_CONTRACT_COMPANY = 6;
	const FROM_VENDOR = 7;
	
	const HEAD_SITE_EXPENSE = 1;
	const HEAD_ACCOUNT = 2;
	const HEAD_EMPLOYEE_PERSONAL = 3;
	const HEAD_EMPLOYEE_EXPENSE = 4;
	const HEAD_VENDOR_PAYMENT = 5;
	const HEAD_WORKER_VENDOR_PAYMENT = 6;
	const HEAD_WORKER_PAYMENT = 7;
	const HEAD_SITE_DUES = 8;
	const HEAD_COMPANY_DUES = 9;
	
	public $debit;
	public $credit;
	
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
                # custom validation rules
            ]
        );
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
	
	public static function buildFromHead(){
	   
        if(Yii::$app->user->identity->isSelf()){
            return [
				self::FROM_EMPLOYEE	=>'Employee',
		    ];
        }else{
            return [
				self::FROM_ACCOUNT =>'Account',
				self::FROM_EMPLOYEE	=>'Employee',
				self::FROM_SITE_DUES	=>'Site Dues Member',
				self::FROM_COMPANY_DUES	=>'Company Dues Member',
				self::FROM_WORKER_VENDOR	=>'Worker Vendor',
				self::FROM_CONTRACT_COMPANY	=>'Contract Company',
				self::FROM_VENDOR	=>'Vendor',
		    ];
        }
			
	}
	public  function getFromHeadLabel(){
		
			if(isset(self::buildFromHead()[$this->from_head])){
				return self::buildFromHead()[$this->from_head];
			}
		
	}
	
	public  function getFromAccount(){
		
		if($this->from_head == Self::FROM_ACCOUNT){
			$fromAccount = \app\models\BankAccount::findOne($this->from_account);
			return "<label class='text-primary'>".$fromAccount->bank_name."-".$fromAccount->account_no."</label>";
		}elseif($this->from_head == Self::FROM_EMPLOYEE){
			$fromAccount = \app\models\Employee::findOne($this->from_account);
			return "<label class='text-secondary'>".$fromAccount->emp_name."-".$fromAccount->emp_code."</label>";
		}elseif($this->from_head == Self::FROM_SITE_DUES){
			$fromAccount = \app\models\SiteDues::findOne($this->from_account);
			return "<label class='text-success'>".$fromAccount->name."-".$fromAccount->code."</label>";
		}elseif($this->from_head == Self::FROM_COMPANY_DUES){
			$fromAccount = \app\models\CompanyDues::findOne($this->from_account);
			return "<label class='text-danger'>".$fromAccount->name."-".$fromAccount->code."</label>";
		}elseif($this->from_head == Self::FROM_WORKER_VENDOR){
			$fromAccount = \app\models\WorkerVendor::findOne($this->from_account);
			return "<label class='text-warning'>".$fromAccount->name."-".$fromAccount->code."</label>";
		}elseif($this->from_head == Self::FROM_CONTRACT_COMPANY){
			$fromAccount = \app\models\ContractCompany::findOne($this->from_account);
			return "<label class='text-info'>".$fromAccount->name."</label>";
		}elseif($this->from_head == Self::FROM_VENDOR){
			$fromAccount = \app\models\Vendor::findOne($this->from_account);
			return "<label class='text-dark'>".$fromAccount->name."-".$fromAccount->code."</label>";
		}
	}
	
	public  function getToAccount(){
		if($this->to_head == Self::HEAD_ACCOUNT){
			$toAccount = \app\models\BankAccount::findOne($this->to_account);
			return "<label class='text-primary'>".$toAccount->bank_name."-".$toAccount->account_no."</label>";
		}elseif($this->to_head == Self::HEAD_EMPLOYEE_EXPENSE || $this->to_head == Self::HEAD_EMPLOYEE_PERSONAL){
			$toAccount = \app\models\Employee::findOne($this->to_account);
			return "<label class='text-secondary'>".$toAccount->emp_code."-".$toAccount->emp_name."</label>";
		}elseif($this->to_head == Self::HEAD_VENDOR_PAYMENT){
			$toAccount = \app\models\Vendor::findOne($this->to_account);
			return "<label class='text-dark'>".$toAccount->code."-".$toAccount->name."</label>";
		}elseif($this->to_head == Self::HEAD_WORKER_VENDOR_PAYMENT){
			$toAccount = \app\models\WorkerVendor::findOne($this->to_account);
			return "<label class='text-warning'>".$toAccount->code."-".$toAccount->name."</label>";
		}elseif($this->to_head == Self::HEAD_WORKER_PAYMENT){
			$toAccount = \app\models\Worker::findOne($this->to_account);
			return "<label class='text-info'>".$toAccount->code."-".$toAccount->name." ( ".$toAccount->workerVendor->code." ".$toAccount->workerVendor->name." )"."</label>";
		}elseif($this->to_head == Self::HEAD_SITE_DUES){
			$toAccount = \app\models\SiteDues::findOne($this->to_account);
			return "<label class='text-success'>".$toAccount->code."-".$toAccount->name."</label>";
		}elseif($this->to_head == Self::HEAD_COMPANY_DUES){
			$toAccount = \app\models\CompanyDues::findOne($this->to_account);
			return "<label class='text-danger'>".$toAccount->code."-".$toAccount->name."</label>";	
		}elseif($this->to_head == Self::HEAD_SITE_EXPENSE){
			return "Site Expense";
		}
		
	}
	
	public static function buildPaymentHead(){
			return [
				self::HEAD_SITE_EXPENSE	=>'Site Expense',
				self::HEAD_ACCOUNT =>'Account',
				self::HEAD_EMPLOYEE_PERSONAL	=>'Employee Personal',
				self::HEAD_EMPLOYEE_EXPENSE	=>'Employee Expense',
				self::HEAD_VENDOR_PAYMENT	=>'Vendor Payment',
				self::HEAD_WORKER_VENDOR_PAYMENT	=>'Worker Vendor Payment',
				self::HEAD_WORKER_PAYMENT	=>'Worker Payment',
				self::HEAD_SITE_DUES	=>'Site Dues',
				self::HEAD_COMPANY_DUES	=>'Company Dues',
		];
	}
	public  function getPaymentHeadLabel(){
		
			if(isset(self::buildPaymentHead()[$this->to_head])){
				return self::buildPaymentHead()[$this->to_head];
			}
		
	}
	
	public static function creditParticular(){
			return [
				self::FROM_ACCOUNT =>'Payment by Account',
				self::FROM_EMPLOYEE	=>'Payment by Employee',
				self::FROM_SITE_DUES	=>'Payment by Site Member',
				self::FROM_COMPANY_DUES	=>'Payment by Dues Member',
				self::FROM_WORKER_VENDOR	=>'Payment by Worker Vendor',
				self::FROM_CONTRACT_COMPANY	=>'Payment by Contract Company',
				self::FROM_VENDOR	=>'Payment by Vendor',
		];
	}
	public  function creditParticularLabel(){
		
			if(isset(self::creditParticular()[$this->from_head])){
				return self::creditParticular()[$this->from_head];
			}
		
	}
	
	public static function debitParticular(){
			return [
				self::HEAD_SITE_EXPENSE	=>'Payment for Site Expense',
				self::HEAD_ACCOUNT =>'Payment to Account',
				self::HEAD_EMPLOYEE_PERSONAL	=>'Payment to Employee Personal',
				self::HEAD_EMPLOYEE_EXPENSE	=>'Payment to Employee Expense',
				self::HEAD_VENDOR_PAYMENT	=>'Payment to Vendor Payment',
				self::HEAD_WORKER_VENDOR_PAYMENT	=>'Worker Vendor Payment',
				self::HEAD_WORKER_PAYMENT	=>'Worker Payment',
				self::HEAD_SITE_DUES	=>'Site Dues Payment',
				self::HEAD_COMPANY_DUES	=>'Company Dues Payment',
		];
	}
	public  function debitParticularLabel(){
		
			if(isset(self::debitParticular()[$this->to_head])){
				return self::debitParticular()[$this->to_head];
			}
		
	}
	
	public function fromAccount(){
		
		$data[''] = "";
		   
		if($this->from_head == Payment::FROM_ACCOUNT){
		   $accounts = \app\models\BankAccount::find()->orderBy('id')->all();
		   
           foreach ($accounts as $account) {
               $data[$account->id] = $account->bank_name." ".$account->account_no;
           }
		}elseif($this->from_head == Payment::FROM_EMPLOYEE){	   
		   $employees = \app\models\Employee::find()->andWhere(['!=','status',\app\models\Employee::STATUS_DELETE])->orderBy('id')->all();
		   
           foreach ($employees as $employee) {
               $data[$employee->id] = $employee->emp_code." ".$employee->emp_name;
           }
		}elseif($this->from_head == Payment::FROM_SITE_DUES){	   
		   $employees = \app\models\SiteDues::find()->andWhere(['!=','status',\app\models\SiteDues::STATUS_DELETE])->orderBy('id')->all();
		   
           foreach ($employees as $employee) {
               $data[$employee->id] = $employee->code." ".$employee->name;
           }
		}elseif($this->from_head == Payment::FROM_COMPANY_DUES){	   
		   $employees = \app\models\CompanyDues::find()->andWhere(['!=','status',\app\models\CompanyDues::STATUS_DELETE])->orderBy('id')->all();
		   
           foreach ($employees as $employee) {
               $data[$employee->id] = $employee->code." ".$employee->name;
           }
		}elseif($this->from_head == Payment::FROM_WORKER_VENDOR){	   
		   $employees = \app\models\WorkerVendor::find()->andWhere(['!=','status',\app\models\WorkerVendor::STATUS_DELETE])->orderBy('id')->all();
		   
           foreach ($employees as $employee) {
               $data[$employee->id] = $employee->code." ".$employee->name;
           }
		}elseif($this->from_head == Payment::FROM_CONTRACT_COMPANY){	   
		   $employees = \app\models\ContractCompany::find()->orderBy('id')->all();
		   
           foreach ($employees as $employee) {
               $data[$employee->id] = $employee->name;
           }
		}elseif($this->from_head == Payment::FROM_VENDOR){	   
		   $employees = \app\models\Vendor::find()->andWhere(['!=','status',\app\models\Vendor::STATUS_DELETE])->orderBy('id')->all();
		   
           foreach ($employees as $employee) {
               $data[$employee->id] = $employee->name;
           }
		}
        
        return $data;
	}
	
	public function toAccount(){
		
		$data[''] = "";
		if($this->to_head == Payment::HEAD_SITE_EXPENSE){
		   
		   $data[1] = "Site Expense";
           
		}elseif($this->to_head == Payment::HEAD_ACCOUNT){	   
		   $accounts = \app\models\BankAccount::find()->orderBy('id')->all();
		   
           foreach ($accounts as $account) {
               $data[$account->id] = $account->bank_name." ".$account->account_no;
           }
		}else if($this->to_head == Payment::HEAD_EMPLOYEE_PERSONAL || $this->to_head == Payment::HEAD_EMPLOYEE_EXPENSE){	   
		   $employees = \app\models\Employee::find()->andWhere(['!=','status',\app\models\Employee::STATUS_DELETE])->orderBy('id')->all();
		   
           foreach ($employees as $employee) {
               $data[$employee->id] = $employee->emp_code." ".$employee->emp_name;
           }
		}else if($this->to_head == Payment::HEAD_VENDOR_PAYMENT){	   
		   $vendors = \app\models\Vendor::find()->andWhere(['!=','status',\app\models\Vendor::STATUS_DELETE])->orderBy('id')->all();
		   
           foreach ($vendors as $vendor) {
               $data[$vendor->id] = $vendor->code." ".$vendor->name;
           }
		}else if($this->to_head == Payment::HEAD_WORKER_VENDOR_PAYMENT){	   
		   $vendors = \app\models\WorkerVendor::find()->andWhere(['!=','status',\app\models\WorkerVendor::STATUS_DELETE])->orderBy('id')->all();
		   
           foreach ($vendors as $vendor) {
               $data[$vendor->id] = $vendor->code." ".$vendor->name;
           }
		}else if($this->to_head == Payment::HEAD_WORKER_PAYMENT){	   

		   if($this->worker_vendor)	
		      $vendors = \app\models\Worker::find()->where(['worker_vendor_id'=>$this->worker_vendor])->andWhere(['!=','status',\app\models\Worker::STATUS_DELETE])->orderBy('id')->all();
		   else  $vendors = \app\models\Worker::find()->orderBy('id')->all();
		   

           foreach ($vendors as $vendor) {
               $data[$vendor->id] = $vendor->code." ".$vendor->name;
           }
		}else if($this->to_head == Payment::HEAD_SITE_DUES){	   
		   $vendors = \app\models\SiteDues::find()->andWhere(['!=','status',\app\models\SiteDues::STATUS_DELETE])->orderBy('id')->all();
		   
           foreach ($vendors as $vendor) {
               $data[$vendor->id] = $vendor->code." ".$vendor->name;
           }
		}else if($this->to_head == Payment::HEAD_COMPANY_DUES){	   
		   $vendors = \app\models\CompanyDues::find()->andWhere(['!=','status',\app\models\CompanyDues::STATUS_DELETE])->orderBy('id')->all();
		   
           foreach ($vendors as $vendor) {
               $data[$vendor->id] = $vendor->code." ".$vendor->name;
           }
		}
        
        return $data;
	}
	
	public function refNo($ref_no = Null){
		
		if($ref_no){
			return $ref_no;
		}else{
		    $invoice = Self::find()->select(['MAX(id) as id'])->where(['company_id'=>$this->company_id,'session'=>$this->session])->one();
		    if(!empty($invoice))
			    return "Ref#".time()."1";
		    else return "Ref#".time()."".($invoice->ref_no + 1);
		}
	}
	
	public function saveLedger($model){
		
		$flag = true;
		
		//Credit Ledger
		
		   $creditLedger = new \app\models\Ledger;
		
		if($model->to_head != Self::HEAD_SITE_EXPENSE){
		
           
		   $type = ""; $creditLebel = '';
           if($model->to_head == $model::HEAD_ACCOUNT){
               $type = $creditLedger::TYPE_ACCOUNT;	
               $creditLebel = $model->creditParticularLabel();
           }elseif($model->to_head == $model::HEAD_EMPLOYEE_EXPENSE || $model->to_head == $model::HEAD_EMPLOYEE_PERSONAL){
               $type = $creditLedger::TYPE_EMPLOYEE;	
               if($model->to_head == $model::HEAD_EMPLOYEE_PERSONAL){
                   $creditLebel = "Personal ".$model->creditParticularLabel();
               }else{
                   $creditLebel = $model->creditParticularLabel();
               }
           }elseif($model->to_head == $model::HEAD_VENDOR_PAYMENT){
               $type = $creditLedger::TYPE_VENDOR;	
               $creditLebel = $model->creditParticularLabel();
           }elseif($model->to_head == $model::HEAD_WORKER_VENDOR_PAYMENT){
               $type = $creditLedger::TYPE_WORKER_VENDOR;
               $creditLebel = $model->creditParticularLabel();
           }elseif($model->to_head == $model::HEAD_WORKER_PAYMENT){
               $type = $creditLedger::TYPE_WORKER;	
               $creditLebel = $model->creditParticularLabel();
           }elseif($model->to_head == $model::HEAD_SITE_DUES){
               $type = $creditLedger::TYPE_SITE_DUES;	
               $creditLebel = $model->creditParticularLabel();
           }elseif($model->to_head == $model::HEAD_COMPANY_DUES){
               $type = $creditLedger::TYPE_COMPANY_DUES;
               $creditLebel = $model->creditParticularLabel();
           }
		   
		   if(! $creditLedger->saveLedger($model->date, $model->to_account,$model->id,$creditLebel, $model->amount, 0,
            		   $creditLedger::INOUT_CREDIT, $type,$model->company_id, $model->session,$creditLedger::FROM_PAYMENT_PAGE))
			   $flag = false;
		
		}else{
			$deleteLedger = \app\models\Ledger::find()->where(['transaction_id'=>$model->id,'inout'=>$creditLedger::INOUT_CREDIT,'entry_from'=>$creditLedger::FROM_PAYMENT_PAGE])->one();
			if($deleteLedger){
               $deleteLedger->status = $creditLedger::STATUS_DELETE;
               $deleteLedger->save();
			}
		}
		
		//Debit Ledger
		
		   $debitLedger = new \app\models\Ledger;
		
		   $type = "";
           if($model->from_head == $model::FROM_ACCOUNT)
               $type = $debitLedger::TYPE_ACCOUNT;		
           elseif($model->from_head == $model::FROM_EMPLOYEE)
               $type = $debitLedger::TYPE_EMPLOYEE;	   	
           elseif($model->from_head == $model::FROM_SITE_DUES)
               $type = $debitLedger::TYPE_SITE_DUES;	   	
           elseif($model->from_head == $model::FROM_COMPANY_DUES)
               $type = $debitLedger::TYPE_COMPANY_DUES;	   	
           elseif($model->from_head == $model::FROM_CONTRACT_COMPANY)
               $type = $debitLedger::TYPE_CONTRACT_COMPANY;	   
           elseif($model->from_head == $model::FROM_VENDOR)
               $type = $debitLedger::TYPE_VENDOR;	 	   
           elseif($model->from_head == $model::FROM_WORKER_VENDOR)
               $type = $debitLedger::TYPE_WORKER_VENDOR;	   
		   
		   if(! $debitLedger->saveLedger($model->date, $model->from_account,$model->id,$model->debitParticularLabel(), 0, $model->net_amount,
            		   $debitLedger::INOUT_DEBIT, $type,$model->company_id, $model->session,$creditLedger::FROM_PAYMENT_PAGE))
			   $flag = false;
		
		   return $flag;
		   
	}
	
	public function getPaymentByRefNo($ref_no){
	    
	    return  Self::find()->where(['ref_no'=>$ref_no])->andWhere(['!=','status',Payment::STATUS_DELETE])->orderBy(['id'=>SORT_ASC])->all();
	    
	}
	
	public function getStates(){
	    
        $states = \app\models\ContractCompanyGst::find()->where(['company_id' => $this->contract_company_id])->all();
        $response = [];
        foreach($states as $state){
            $response[$state->state_id] = $state->state->state;
        }
        
        return $response;
	    
	}
	
	public function getDistricts(){
	    
        $districts = \app\models\ContractCompanyGst::find()->select('districts')->where(['company_id' => $this->contract_company_id,'state_id'=>$this->state_id])->one();
        $response = [];
        if( !empty($districts) ){
            $districts = json_decode($districts->districts,true);
            $districts = \app\models\District::find()->select(['id','district'])->where(['id'=>$districts])->orderBy('id')->all();
            foreach($districts as $district){
                $response[$district->id] = $district->district;
            }
        }
        
        
        return $response;
        
	}
	
	public function getSites(){
	    
        $sites = \app\models\Sites::find()->where(['company_id' => $this->contract_company_id,'state_id'=>$this->state_id, 'district_id'=>$this->district_id,'status'=>\app\models\Sites::ACTIVE_STATUS])->all();
        $response = [];
        if( !empty($sites) ){
            foreach($sites as $site){
                $response[$site->id] = $site->name;
            }
        }
        
        return $response;
	}
	
	public function hasHistory(){
	    if( Yii::$app->user->identity->isSuperAdmin() ){
			return false;
		}
		return \app\models\History::find()->where(['model_name'=>'app\\models\\Payment','other_id'=>$this->ref_no,'action_status'=>\app\models\History::ACTION_STATUS_UPDATE])->exists();
	}

	public function getHistory(){
	    if( Yii::$app->user->identity->isSuperAdmin() ){
			return false;
		}
        return \app\models\History::find()->where(['model_name'=>'app\\models\\Payment','model_id'=>$this->id,'action_status'=>\app\models\History::ACTION_STATUS_UPDATE])->orderBy(['id'=>SORT_DESC])->all();
	}
	
	public function getRecentHistory(){
		if( Yii::$app->user->identity->isSuperAdmin() ){
			return false;
		}
        return \app\models\History::find()->where(['model_name'=>'app\\models\\Payment','model_id'=>$this->id,'action_status'=>\app\models\History::ACTION_STATUS_UPDATE])->orderBy(['id'=>SORT_DESC])->one();
	}

	public function oldHistory( $data ){
       foreach( $data as $key => $array ){
         $this->$key = $array['old'];
	   }
	}
	
	public function newHistory( $data ){
		foreach( $data as $key => $array ){
		  $this->$key = $array['new'];
		}
	 }
	
}
