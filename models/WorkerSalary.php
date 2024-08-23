<?php

namespace app\models;

use Yii;
use \app\models\base\WorkerSalary as BaseWorkerSalary;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "worker_salary".
 */
class WorkerSalary extends BaseWorkerSalary
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
