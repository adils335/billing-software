<?php

namespace app\models;

use Yii;
use \app\models\base\DeductionMaster as BaseDeductionMaster;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "deduction_master".
 */
class DeductionMaster extends BaseDeductionMaster
{

    const STATUS_ACTIVE = 1;
    const STATUS_DEACTIVE = 2;

    const TYPE_EMPLOYEE = 1;
    const TYPE_EMPLOYER = 2;
    
    const DEDUCTION_TYPE_EPF = 1;
    const DEDUCTION_TYPE_ESI = 2;

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
                self::STATUS_ACTIVE    => 'Active',
                self::STATUS_DEACTIVE    => 'Deactive',
        ];
    }
    
    public  function getStatusLabel(){
        
            if(isset(self::buildStatus()[$this->status])){
                return self::buildStatus()[$this->status];
            }
        
    }

    public static function buildType(){
            return [
                self::TYPE_EMPLOYEE    => 'Employee',
                self::TYPE_EMPLOYER    => 'Employer',
        ];
    }
    
    public  function getTypeLabel(){
        
            if(isset(self::buildType()[$this->type])){
                return self::buildType()[$this->type];
            }
        
    }
    
    public static function buildDeductionType(){
            return [
                self::DEDUCTION_TYPE_EPF    => 'EPF',
                self::DEDUCTION_TYPE_ESI    => 'ESI',
        ];
    }
    
    public  function getDeductionTypeLabel(){
        
            if(isset(self::buildDeductionType()[$this->deduction_type])){
                return self::buildDeductionType()[$this->deduction_type];
            }
        
    }

}
