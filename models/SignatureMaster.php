<?php

namespace app\models;

use Yii;
use \app\models\base\SignatureMaster as BaseSignatureMaster;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "signature_master".
 */
class SignatureMaster extends BaseSignatureMaster
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
