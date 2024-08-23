<?php

namespace app\models;

use Yii;
use \app\models\base\EmployeeAllowanceHistory as BaseEmployeeAllowanceHistory;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "employee_allowance_history".
 */
class EmployeeAllowanceHistory extends BaseEmployeeAllowanceHistory
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
