<?php

namespace app\models;

use Yii;
use \app\models\base\AgreementBillBack as BaseAgreementBillBack;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "agreement_bill_back".
 */
class AgreementBillBack extends BaseAgreementBillBack
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
