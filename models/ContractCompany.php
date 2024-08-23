<?php

namespace app\models;

use Yii;
use \app\models\base\ContractCompany as BaseContractCompany;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "contract_company".
 */
class ContractCompany extends BaseContractCompany
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
