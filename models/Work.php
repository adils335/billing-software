<?php

namespace app\models;

use Yii;
use \app\models\base\Work as BaseWork;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "work".
 */
class Work extends BaseWork
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
