<?php

namespace app\models;

use Yii;
use \app\models\base\Employee as BaseEmployee;
use yii\helpers\ArrayHelper;
use app\models\Erpmeta;

/**
 * This is the model class for table "employee".
 */
class Employee extends BaseEmployee
{

    const STATUS_DEACTIVE = 1;
	const STATUS_ACTIVE = 2;
	const STATUS_DELETE = 3;
	
    const TRANSACTION_CREDIT = 1;
	const TRANSACTION_DEBIT = 2;

	const ACCOUNT_PERSONAL = 1;
	const ACCOUNT_EXPENSE = 2;
	
	const DEDUCTION_NO = 1;
    const DEDUCTION_YES = 2;

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
    
    public function getErpmetaDate(){
         return Erpmeta::find()
		        ->where(['type'=>Erpmeta::TYPE_EMPLOYEE,'type_id'=>$this->id])
		        ->andWhere(['meta_key'=>['joining_date','end_date']])
				->all();
	}

	public static function buildAction($controller){
        
        $array = [];
		$getAction = \app\models\ControllerAction::find()->where(['controller'=>$controller])->one();
		if(empty($getAction)){
            return $array;
		}
        $actions = json_decode($getAction->action);
        foreach ($actions as $key => $action) {
        	$array[$action] = $action;
        }
        return $array;

	}

	public static function buildStatus(){
			return [
				self::STATUS_DEACTIVE =>'Deactive',
				self::STATUS_ACTIVE	=>'Active',
				self::STATUS_DELETE	=>'Delete',
		];
	}

	public  function getStatusLabel(){
		
			if(isset(self::buildStatus()[$this->status])){
				return self::buildStatus()[$this->status];
			}
		
	}

	public static function buildDeduction(){
			return [
				self::DEDUCTION_NO =>'No',
				self::DEDUCTION_YES	=>'Yes',
		];
	}

	public  function getDeductionLabel(){
		
			if(isset(self::buildDeduction()[$this->status])){
				return self::buildDeduction()[$this->status];
			}
		
	}

	public static function buildAccountType(){
			return [
				self::ACCOUNT_PERSONAL =>'Personal',
				self::ACCOUNT_EXPENSE	=>'Expense',
		];
	}
	public static function buildTransactionType(){
			return [
				self::TRANSACTION_CREDIT =>'Credit',
				self::TRANSACTION_DEBIT	=>'Debit',
		];
	}
	
	public static function buildBalanceType(){
			return [
				self::TRANSACTION_CREDIT =>'Cr.',
				self::TRANSACTION_DEBIT	=>'Dr.',
		];
	}
	public  function getPersonalTypeLabel(){
		
			if(isset(self::buildBalanceType()[$this->personal_type])){
				return self::buildBalanceType()[$this->personal_type];
			}
		
	}
	
	public  function getExpenseTypeLabel(){
		
			if(isset(self::buildBalanceType()[$this->expense_type])){
				return self::buildBalanceType()[$this->expense_type];
			}
		
	}
	
	public function employeeCode(){
	    
	    $code = "1";
	    
	    $prefix = ($this->emp_company).$code;
	    
		$empCode = (int) $prefix."1";
		$model = Self::find()->select(['MAX(CAST(SUBSTRING(emp_code,3) AS SIGNED)) as emp_code'])->where(['emp_company'=>$this->emp_company])->one();
		if($model)
			$empCode = (int) $prefix.sprintf('%02d',$model->emp_code+1);
		
		return $empCode;
		
	}
    
    public function getPermission(){

       $contrllers = \app\models\ControllerAction::getAllControllers();
       $permission = [];

       foreach ($contrllers as $key => $value) {
       	    $action = Null;
       	    $model = \app\models\Permission::find()->where(['user_id'=>$this->user_id,'controller'=>$value])->one();
            if($model){
                if($model->action)
            	$action = json_decode($model->action);
            }
            $permission[] = ['controller'=>$value,'action'=>$action];

       }
       return $permission;

    }

	public function signup(){
		
		$user = new User();
        $user->email = $this->email;
        $user->username = explode('@', $this->email)[0];
        if($user->create()){
           $this->user_id = $user->id;	
           return true;
        }else{
           echo "<pre>";print_r($user->getErrors());die();	
           return false;
        }
        
	}
	
	public function getEmployeeAllowances(){
	    
	    $response = [];
	    $allowances = \app\models\AllowanceMaster::find()->all();
	    foreach($allowances as $allowance){
	        $isExist = \app\models\EmployeeAllowance::find()->where(['employee_id'=>$this->id,'allowance_id'=>$allowance->id])->one();
	        $value = 0;
	        $id = '';
	        if(!empty($isExist)){
	            $value = $isExist->value;
	            $id = $isExist->id;
	        }
	        $response[] = ['id'=>$id,'employee_id'=>$this->id,'allowance_id'=>$allowance->id,'allowance'=>$allowance->name,'value'=>$value];
	    }
	    return $response;
	}

	public function getLatestDate(){
		return [
			'joining_date'=>$this->joining_date??NULL,
			'end_date'=>$this->end_date??NULL
		];
	}
	
}
