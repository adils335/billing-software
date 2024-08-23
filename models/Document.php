<?php

namespace app\models;

use Yii;
use \app\models\base\Document as BaseDocument;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "document".
 */
class Document extends BaseDocument
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
