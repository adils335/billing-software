<?php

namespace app\models;

use Yii;
use \app\models\base\AgreementProduct as BaseAgreementProduct;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "agreement_product".
 */
class AgreementProduct extends BaseAgreementProduct
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
