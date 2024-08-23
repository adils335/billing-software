<?php

namespace app\models;

use Yii;
use \app\models\base\StoreConsumedItems as BaseStoreConsumedItems;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "store_consumed_items".
 */
class StoreConsumedItems extends BaseStoreConsumedItems
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
