<?php

namespace app\models;

use Yii;
use \app\models\base\Worker as BaseWorker;
use yii\helpers\ArrayHelper;
use app\models\Erpmeta;

/**
 * This is the model class for table "worker".
 */
class Worker extends BaseWorker
{

	const TYPE_CREDIT = 1;
	const TYPE_DEBIT = 2;
	
	const STATUS_ACTIVE = 1;
	const STATUS_DEACTIVE = 2;
	const STATUS_DELETE = 3;
	
	const DEDUCTION_NO = 0;
	const DEDUCTION_YES = 1;
	
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
    
	public static function buildBalanceType(){
			return [
				self::TYPE_CREDIT   => 'Cr.',
				self::TYPE_DEBIT	=> 'Dr.',
		];
	}
	public  function getBalanceTypeLabel(){
		
			if(isset(self::buildBalanceType()[$this->inout_type])){
				return self::buildBalanceType()[$this->inout_type];
			}
		
	}
	
	public static function buildStatus(){
			return [
				self::STATUS_ACTIVE   => 'Active',
				self::STATUS_DEACTIVE	=> 'Deactive',
				self::STATUS_DELETE	=> 'Delete',
		];
	}
	public  function getStatusLabel(){
		
			if(isset(self::buildStatus()[$this->status])){
				return self::buildStatus()[$this->status];
			}
		
	}
	
	public static function buildDeduction(){
			return [
				self::DEDUCTION_NO   => 'No',
				self::DEDUCTION_YES	=> 'Yes',
		];
	}
	public  function getDeductionLabel(){
		
			if(isset(self::buildDeduction()[$this->is_deduction])){
				return self::buildDeduction()[$this->is_deduction];
			}
		
	}
	
	public  function getCode(){
		
	    $shortcode = "4";
	    
	    $prefix = $this->company_id.$shortcode;
	    
		$code = (int) $prefix."01";
		$model = Self::find()->select(['MAX(CAST(SUBSTRING(code,3) AS SIGNED)) as code'])->where(['company_id'=>$this->company_id])->one();
		if($model)
			$code = (int) $prefix.sprintf("%02d",$model->code+1);
		
		return $code;
		
		
	}
	
	public function getWorkerAllowances(){
	    
	    $response = [];
	    $allowances = \app\models\AllowanceMaster::find()->all();
	    foreach($allowances as $allowance){
	        $isExist = \app\models\WorkerAllowance::find()->where(['worker_id'=>$this->id,'allowance_id'=>$allowance->id])->one();
	        $value = 0;
	        $id = '';
	        if(!empty($isExist)){
	            $value = $isExist->value;
	            $id = $isExist->id;
	        }
	        $response[] = ['id'=>$id,'worker_id'=>$this->id,'allowance_id'=>$allowance->id,'allowance'=>$allowance->name,'value'=>$value];
	    }
	    return $response;
	}

	public function getErpmetaDate(){
		return Erpmeta::find()
			   ->where(['type'=>Erpmeta::TYPE_WORKER,'type_id'=>$this->id])
			   ->andWhere(['meta_key'=>['joining_date','end_date']])
			   ->all();
    }
   
    public function getLatestDate(){
    	return [
    		'joining_date'=>$this->joining_date??NULL,
    		'end_date'=>$this->end_date??NULL
    	];
    }

}
