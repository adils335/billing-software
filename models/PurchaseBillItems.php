<?php

namespace app\models;

use Yii;
use \app\models\base\PurchaseBillItems as BasePurchaseBillItems;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "purchase_bill_items".
 */
class PurchaseBillItems extends BasePurchaseBillItems
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
