<?php

namespace app\models;

use Yii;
use \app\models\base\EinvoiceAuth as BaseEinvoiceAuth;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "einvoice_auth".
 */
class EinvoiceAuth extends BaseEinvoiceAuth
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
