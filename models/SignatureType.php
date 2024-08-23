<?php

namespace app\models;

use Yii;
use \app\models\base\SignatureType as BaseSignatureType;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "signature_type".
 */
class SignatureType extends BaseSignatureType
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
