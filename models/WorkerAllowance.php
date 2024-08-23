<?php

namespace app\models;

use Yii;
use \app\models\base\WorkerAllowance as BaseWorkerAllowance;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "worker_allowance".
 */
class WorkerAllowance extends BaseWorkerAllowance
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
