<?php

namespace app\models;

use Yii;
use \app\models\base\WorkerSalaryDeduction as BaseWorkerSalaryDeduction;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "worker_salary_deduction".
 */
class WorkerSalaryDeduction extends BaseWorkerSalaryDeduction
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
    
    
    public static function loadData($type,$post,$worker_id=null){
       $response = [];
       if($worker_id){
          $deductions = \app\models\DeductionMaster::find()->where(['type'=>$type])->all();
          foreach($deductions as $deduction){
             $salaryDeduction = new WorkerSalaryDeduction;
             $salaryDeduction->deduction_id = $deduction->id;
             $salaryDeduction->rate = $deduction->rate;
             $response[] = $salaryDeduction;
          }
       }else if($post){
          foreach($post as $deduction){
            $salaryDeduction = new WorkerSalaryDeduction;
            $salaryDeduction->load($deduction);
            if($type == $salaryDeduction->type){
              $response[] = $salaryDeduction;
            }
          }
       }
       return $response;
    }

}
