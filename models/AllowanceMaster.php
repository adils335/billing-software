<?php

namespace app\models;

use Yii;
use \app\models\base\AllowanceMaster as BaseAllowanceMaster;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "allowance_master".
 */
class AllowanceMaster extends BaseAllowanceMaster
{
    
    const STATUS_ACTIVE = 1;
    const STATUS_DEACTIVE = 2;

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
        
            if(isset(self::buildSchedule()[$this->status])){
                return self::buildSchedule()[$this->status];
            }
        
    }
}
