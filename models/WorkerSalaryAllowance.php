<?php

namespace app\models;

use Yii;
use \app\models\base\WorkerSalaryAllowance as BaseWorkerSalaryAllowance;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "worker_salary_allowance".
 */
class WorkerSalaryAllowance extends BaseWorkerSalaryAllowance
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
    
     public static function loadData($post,$worker_id=null){

       $response = [];
       if($worker_id){
          $allowances = \app\models\WorkerAllowance::find()->where(['worker_id'=>$worker_id])->all();
          foreach($allowances as $allowance){
             $salaryAllowance = new WorkerSalaryAllowance;
             $salaryAllowance->allowance_id = $allowance->allowance_id;
             $salaryAllowance->amount = $allowance->value;
             $salaryAllowance->actual_amount = $allowance->value;
             $response[] = $salaryAllowance;
          }
       }else if($post){
          foreach($post as $allowance){
            $salaryAllowance = new WorkerSalaryAllowance;
            $salaryAllowance->load($allowance);
            $response[] = $salaryAllowance;
          }
       }
       return $response;
    }
}
