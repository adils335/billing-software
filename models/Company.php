<?php

namespace app\models;

use Yii;
use \app\models\base\Company as BaseCompany;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "company".
 */
class Company extends BaseCompany
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
                ['logo', 'required', 'on' => 'create'],
            ]
        );
    }
}
