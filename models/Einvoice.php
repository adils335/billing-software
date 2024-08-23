<?php

namespace app\models;

use Yii;
use \app\models\base\Einvoice as BaseEinvoice;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "einvoice".
 */
class Einvoice extends BaseEinvoice
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
