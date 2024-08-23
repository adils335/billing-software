<?php

namespace app\models;

use Yii;
use \app\models\base\BillBackMaster as BaseBillBackMaster;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "bill_back_master".
 */
class BillBackMaster extends BaseBillBackMaster
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
