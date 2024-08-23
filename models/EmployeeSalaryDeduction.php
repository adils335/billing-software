<?php

namespace app\models;

use Yii;
use \app\models\base\EmployeeSalaryDeduction as BaseEmployeeSalaryDeduction;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "employee_salary_deduction".
 */
class EmployeeSalaryDeduction extends BaseEmployeeSalaryDeduction
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

    public static function loadData($deduction_type,$type,$post,$employee_id=null){
       $response = [];
       if($employee_id){
          $deductions = \app\models\DeductionMaster::find()->where(['type'=>$type,'deduction_type'=>$deduction_type])->all();
          foreach($deductions as $deduction){
             $salaryDeduction = new EmployeeSalaryDeduction;
             $salaryDeduction->deduction_id = $deduction->id;
             $salaryDeduction->rate = $deduction->rate;
             $response[] = $salaryDeduction;
          }
       }else if($post){
          foreach($post as $deduction){
            $salaryDeduction = new EmployeeSalaryDeduction;
            $salaryDeduction->load($deduction);
            if($type == $salaryDeduction->type){
              $response[] = $salaryDeduction;
            }
          }
       }
       return $response;
    }

}
