<?php

namespace app\models;

use Yii;
use \app\models\base\AgreementTax as BaseAgreementTax;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "agreement_tax".
 */
class AgreementTax extends BaseAgreementTax
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
