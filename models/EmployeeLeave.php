<?php

namespace app\models;

use Yii;
use \app\models\base\EmployeeLeave as BaseEmployeeLeave;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "employee_leave".
 */
class EmployeeLeave extends BaseEmployeeLeave
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
                # custom rules
            ]
        );
    }
    
    public static function month_wise_leave($data){
        $dates = explode(",",$data);
        $leaves = [];
        foreach($dates as $date){
            $leaves[date("Y-m-01",strtotime($date))][] = $date;
        }
        return $leaves;
    }
    
    public static function resetLeave($id){
        $current = date("Y-m-01");
        $secondLast = date("Y-m-01",strtotime($current." -2 Month"));
        $firstLast = date("Y-m-01",strtotime($current." -1 Month"));
        $firstNext = date("Y-m-01",strtotime($current." +1 Month"));
        $secondNext = date("Y-m-01",strtotime($current." +1 Month"));
        $month = [$secondLast,$firstLast,$current,$firstNext,$secondNext];
        Self::UpdateAll(['leave'=>Null],['employee_id'=>$id,'month'=>$month]);
    }
    
    public static function filter_leave($id,$start_month,$end_month){
        $start_month = empty($start_month)?date("Y-m-01"):$start_month;
        $end_month = empty($end_month)?date("Y-m-01"):$end_month;
        $model = Self::find()->where(['BETWEEN','month',$start_month,$end_month])->orderBy(['month'=>SORT_DESC,'employee_id'=>SORT_DESC]);
        if($id){
            $model = $model->andWhere(['employee_id'=>$id]);
        }
        return $model;
    }
    
    public static function isLeave($date) {
         return in_array(date("l",strtotime($date)), ["Sunday"]);
    }
    
}
