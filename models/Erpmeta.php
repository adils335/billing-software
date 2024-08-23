<?php

namespace app\models;

use Yii;
use \app\models\base\Erpmeta as BaseErpmeta;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "erpmeta".
 */
class Erpmeta extends BaseErpmeta
{
    const TYPE_EMPLOYEE = 1;
    const TYPE_WORKER = 2;

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

    public static function buildTypes(){
        return [
            self::TYPE_EMPLOYEE=>'Employee',
            self::TYPE_WORKER=>'Worker'
        ];
    }

    public function getTypeLabel(){
        $types = self::buildTypes();
        if( isset( $types[$this->type] ) ){
            return $types[$this->type];
        }
    }

    public static function joining_end_date(){
        return [
            'joining_date'=>'Joining Date',
            'end_date'=>'End Date'
        ];
    }

    public static function getLatestDate( $type, $type_id ){
        $formatter = \Yii::$app->formatter;
        $response = ['joining_date'=>null,'end_date'=>null];
        $joining_date = self::find()->where(['type'=>$type,'type_id'=>$type_id,'meta_key'=>'joining_date'])->one(); 
        if( !empty( $joining_date ) ){
            $response['joining_date'] = $formatter->asDate($joining_date->meta_value,'php:Y-m-d');
        }
        $end_date = self::find()->where(['type'=>$type,'type_id'=>$type_id,'meta_key'=>'end_date'])->one();
        if( !empty( $end_date ) ){
            $response['end_date'] = $formatter->asDate($end_date->meta_value,'php:Y-m-d');
        }
        return $response;  
    }
}
