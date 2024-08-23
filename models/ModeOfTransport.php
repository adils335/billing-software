<?php

namespace app\models;

use \app\models\base\ModeOfTransport as BaseModeOfTransport;

use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "mode_of_transport".
 */
class ModeOfTransport extends BaseModeOfTransport
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
        
            if(isset(self::buildStatus()[$this->status])){
                return self::buildStatus()[$this->status];
            }
        
    }

}
