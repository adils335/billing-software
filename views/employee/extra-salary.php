<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Search\Employee */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Extra Salary');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-index">
   <div class="employee-index box box-primary"> 
		
		<div class="box-header with-border"> 

    <?php echo $this->render('_extra_salary_search', ['model' => $searchModel]); ?>

    <div class="box-body">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'rowOptions'=>function ($model, $key, $index, $grid){
             return ['class'=>'employee'];
          },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
            'header' => 'Name',
            'content' => function($model) {
                return $model->employee->emp_name;
            }           
            ],

            [
            'header' => 'Code',
            'content' => function($model) {
                return $model->employee->emp_code;
            }           
            ],

            [
            'header' => 'Month',
            'content' => function($model) {
                return Yii::$app->formatter->asDate($model->month,'php:M-Y');
            }           
            ],
            'days',
            'salary',
            'allowance',
            'salary_with_allowance',

            ['class' => 'yii\grid\ActionColumn',
             'template'=> '{update} {delete}',
             'buttons' => [
                'view' => function ($url, $model, $key) {
                       
                       return Html::a('<i class="fa fa-eye" aria-hidden="true"></i>',
                           ['extra-salary/'.$model->employee->emp_code."/".$model->month.".pdf"],
                           ['target'=>'_blank']);
                    },  
                ],
            'urlCreator' => function ($action, $model, $key, $index) {
                   if($action == "update")
                        return Url::to(['employee/extra-salary-form','id' => $model['id']]);
                   if($action == "delete")
                        return Url::to(['employee/delete-extra-salary','id' => $model['id']]); 
               }    
            ],
        ],
    ]); ?>


</div>
</div>
</div>
</div>