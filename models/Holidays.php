<?php

namespace app\models;

use Yii;
use \app\models\base\Holidays as BaseHolidays;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "holidays".
 */
class Holidays extends BaseHolidays
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
}
