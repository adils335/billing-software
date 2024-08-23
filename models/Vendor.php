<?php

namespace app\models;

use Yii;
use \app\models\base\Vendor as BaseVendor;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "vendor".
 */
class Vendor extends BaseVendor
{

    const STATUS_DEACTIVE = 1;
	const STATUS_ACTIVE = 2;
	const STATUS_DELETE = 3;

	const TYPE_CREDIT = 1;
	const TYPE_DEBIT = 2;
	
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
	
	public static function buildBalanceType(){
			return [
				self::TYPE_CREDIT    => 'Cr.',
				self::TYPE_DEBIT	=> 'Dr.',
		];
	}
	public  function getBalanceTypeLabel(){
		
			if(isset(self::buildBalanceType()[$this->balance_type])){
				return self::buildBalanceType()[$this->balance_type];
			}
		
	}
	
	public  function getVendorCode(){
		
	    $code = "2";
	    
	    $prefix = ($this->company_id).$code;
	    
		$vendorCode = (int) $prefix."01";
		$model = Self::find()->select(['MAX(CAST(SUBSTRING(code,3) AS SIGNED)) as code'])->where(['company_id'=>$this->company_id])->one();
		if($model)
			$vendorCode = (int) $prefix.sprintf("%02d",$model->code+1);
		
		return $vendorCode;
		
		
	}
	
}
