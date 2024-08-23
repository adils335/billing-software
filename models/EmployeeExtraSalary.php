<?php

namespace app\models;

use Yii;
use \app\models\base\EmployeeExtraSalary as BaseEmployeeExtraSalary;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "employee_extra_salary".
 */
class EmployeeExtraSalary extends BaseEmployeeExtraSalary
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
