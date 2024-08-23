<?php

namespace app\models;

use Yii;
use \app\models\base\ScheduleRateMaster as BaseScheduleRateMaster;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "schedule_rate_master".
 */
class ScheduleRateMaster extends BaseScheduleRateMaster
{

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

    public static function getSrmid(){
        do{
           $uniqid = time();
           $check = Self::find()->where(['srmid'=>$uniqid])->one();
        }while($check);
        return $uniqid;
    }

}
