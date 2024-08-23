<?php

namespace app\models;

use Yii;
use \app\models\base\WorkType as BaseWorkType;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "work_type".
 */
class WorkType extends BaseWorkType
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
