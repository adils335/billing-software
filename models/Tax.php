<?php

namespace app\models;

use Yii;
use \app\models\base\Tax as BaseTax;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tax".
 */
class Tax extends BaseTax
{
    const TAX_PAYABLE = 1;
	const TAX_DEDUCTION = 2;
    const TAX_PENALITY = 3;
	
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
	public static function buildTaxType(){
			return [
				self::TAX_PAYABLE =>'Payable Tax',
				self::TAX_DEDUCTION	=>'Deduction Tax',
                self::TAX_PENALITY =>'Penality',
		];
	}
	public  function getTaxTypeLabel(){
		
			if(isset(self::buildTaxType()[$this->tax_type])){
				return self::buildTaxType()[$this->tax_type];
			}
		
	}
}
