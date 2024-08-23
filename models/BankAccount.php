<?php

namespace app\models;

use Yii;
use \app\models\base\BankAccount as BaseBankAccount;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "bank_account".
 */
class BankAccount extends BaseBankAccount
{

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
	
	public static function buildBalanceType(){
			return [
				self::TYPE_CREDIT    => 'Credit',
				self::TYPE_DEBIT	=> 'Debit',
		];
	}
	public  function getBalanceTypeLabel(){
		
			if(isset(self::buildSchedule()[$this->balance_type])){
				return self::buildSchedule()[$this->balance_type];
			}
		
	}
	
}
