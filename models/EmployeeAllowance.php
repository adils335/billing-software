<?php

namespace app\models;

use Yii;
use \app\models\base\EmployeeAllowance as BaseEmployeeAllowance;
use yii\helpers\ArrayHelper;
use \app\models\EmployeeAllowanceHistory;
use yii\base\Event;

/**
 * This is the model class for table "employee_allowance".
 */
class EmployeeAllowance extends BaseEmployeeAllowance
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

Event::on(EmployeeAllowance::className(), EmployeeAllowance::EVENT_AFTER_INSERT, function ($event){
            $model = $event->sender;
            $history = new EmployeeAllowanceHistory;
            $data['EmployeeAllowanceHistory'] = ['employee_id'=>$model->employee_id,'allowance_id'=>$model->allowance_id,'value'=>$model->value];
            $history->load($data);
            $history->save();
});

Event::on(EmployeeAllowance::className(), EmployeeAllowance::EVENT_AFTER_UPDATE, function ($event) {
            $model = $event->sender;
            $history = new EmployeeAllowanceHistory;
            $data['EmployeeAllowanceHistory'] = ['employee_id'=>$model->employee_id,'allowance_id'=>$model->allowance_id,'value'=>$model->value];
            $history->load($data);
            $history->save();
});
