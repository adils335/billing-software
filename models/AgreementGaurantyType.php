<?php

namespace app\models;

use Yii;
use \app\models\base\AgreementGaurantyType as BaseAgreementGaurantyType;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "agreement_gauranty_type".
 */
class AgreementGaurantyType extends BaseAgreementGaurantyType
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
