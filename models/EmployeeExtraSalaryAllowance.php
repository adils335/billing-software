<?php

namespace app\models;

use Yii;
use \app\models\base\EmployeeExtraSalaryAllowance as BaseEmployeeExtraSalaryAllowance;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "employee_extra_salary_allowance".
 */
class EmployeeExtraSalaryAllowance extends BaseEmployeeExtraSalaryAllowance
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
    
    public static function loadData($post,$employee_id=null){

       $response = [];
       if($employee_id){
          $allowances = \app\models\EmployeeAllowance::find()->where(['employee_id'=>$employee_id])->all();
          foreach($allowances as $allowance){
             $salaryAllowance = new EmployeeExtraSalaryAllowance;
             $salaryAllowance->amount = $allowance->value;
             $salaryAllowance->allowance_id = $allowance->allowance_id;
             $response[] = $salaryAllowance;
          }
       }else if($post){
          foreach($post as $allowance){
            $salaryAllowance = new EmployeeExtraSalaryAllowance;
            $salaryAllowance->load($allowance);
            $response[] = $salaryAllowance;
          }
       }
       return $response;
    }
}
