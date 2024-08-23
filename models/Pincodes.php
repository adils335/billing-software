<?php

namespace app\models;

use Yii;
use \app\models\base\Pincodes as BasePincodes;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "pincodes".
 */
class Pincodes extends BasePincodes
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
