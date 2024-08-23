<?php

namespace app\models;

use Yii;
use \app\models\base\BillingCompany as BaseBillingCompany;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "billing_company".
 */
class BillingCompany extends BaseBillingCompany
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
