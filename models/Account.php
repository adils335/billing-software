<?php

namespace app\models;

use Yii;
use \app\models\base\Account as BaseAccount;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "account".
 */
class Account extends BaseAccount
{
    const STATUS_ENABLE = 1;
    const STATUS_DISABLE = 2;
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

    public static function buildStatus(){
        return [
            self::STATUS_ENABLE => 'Enable',
            self::STATUS_DISABLE => 'Disable',
            self::STATUS_DELETE => 'Delete',
        ];
    }

    public function getStatusLabel(){
       $array = self::buildStatus();
       if( isset( $array[$this->status] ) ){
           return $array[$this->status];
       }
    }
    
}
