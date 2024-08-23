<?php

namespace app\models;

use Yii;
use \app\models\base\Designation as BaseDesignation;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "designation".
 */
class Designation extends BaseDesignation
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
