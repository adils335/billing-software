<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\export\ExportMenu;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Search\Employee */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Salary');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php 
    $gridColumns = [
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
            'salary',
            'allowance',
            'salary_with_allowance',
            'employee_deduction',
            'payable_salary',

            ['class' => 'yii\grid\ActionColumn',
             'template'=> '{view}{update} {delete}',
             'buttons' => [
                'view' => function ($url, $model, $key) {
                       
                       return Html::a('<i class="fa fa-eye" aria-hidden="true"></i>',
                           ['employee-salary/'.$model->employee->emp_code."/".$model->month.".pdf"],
                           ['target'=>'_blank']);
                    },  
                ],
            'urlCreator' => function ($action, $model, $key, $index) {
                   if($action == "update")
                        return Url::to(['employee/salary-form','id' => $model['id']]);
                   if($action == "delete")
                        return Url::to(['employee/delete-salary','id' => $model['id']]); 
               }    
            ]

    ]

?>
<div class="employee-index">
   <div class="employee-index box box-primary"> 
		
		<div class="box-header with-border"> 

    <?php  echo $this->render('generate-salary', ['model' => $searchModel]); ?>
    <?php  echo $this->render('_salary_search', ['model' => $searchModel]); ?>

    <div class="box-body">

    <? echo ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => $gridColumns,
            'dropdownOptions' => [
                'label' => 'Export',
                'class' => 'btn btn-outline-secondary btn-default'
            ]
        ]) . "<hr>\n".
        GridView::widget([
            'dataProvider' => $dataProvider,
            'rowOptions'=>function ($model, $key, $index, $grid){
                return ['class'=>'employee'];
             },
            'columns' => $gridColumns,
        ]);
    
    
    ?>


</div>
</div>
</div>
</div>