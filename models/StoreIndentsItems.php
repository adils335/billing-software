<?php

namespace app\models;

use Yii;
use \app\models\base\StoreIndentsItems as BaseStoreIndentsItems;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "store_indents_items".
 */
class StoreIndentsItems extends BaseStoreIndentsItems
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
