<?php

namespace app\models;

use Yii;
use \app\models\base\CompanyDues as BaseCompanyDues;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "company_dues".
 */
class CompanyDues extends BaseCompanyDues
{

	const TYPE_CREDIT = 1;
	const TYPE_DEBIT = 2;
	
	const STATUS_ACTIVE = 1;
	const STATUS_DEACTIVE = 2;
	const STATUS_DELETE = 3;
	
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
	
	public  function getCode(){
		
	    $shortcode = "6";
	    
	    $prefix = $this->company_id."".$shortcode;
	    
		$code = (int) $prefix."01";
		$model = Self::find()->select(['MAX(CAST(SUBSTRING(code,3) AS SIGNED)) as code'])->where(['company_id'=>$this->company_id])->one();
		if($model)
			$code = (int) $prefix.sprintf("%02d",$model->code+1);
		
		return $code;
		
		
	}

}
