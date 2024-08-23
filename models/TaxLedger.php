<?php

namespace app\models;

use Yii;
use \app\models\base\TaxLedger as BaseTaxLedger;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tax_ledger".
 */
class TaxLedger extends BaseTaxLedger
{

    const STATUS_NOT_DELETED = 0; 
    const STATUS_DELETED = 1; 

    const INOUT_CREDIT = 1; 
    const INOUT_DEBIT = 2; 

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
                self::STATUS_NOT_DELETED =>'Not Deleted',
                self::STATUS_DELETED =>'Deleted',
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
                self::INOUT_DEBIT =>'Debit',
        ];
    }

    public  function getInoutLabel(){
        
            if(isset(self::buildInout()[$this->inout])){
                return self::buildInout()[$this->inout];
            }
        
    }
    
}
