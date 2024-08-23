<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Search\Worker */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Salary');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="worker-index">
   <div class="worker-index box box-primary"> 
		
		<div class="box-header with-border"> 

    <?php  echo $this->render('generate-salary', ['model' => $searchModel]); ?>
    <?php  echo $this->render('_salary_search', ['model' => $searchModel]); ?>

    <div class="box-body">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'rowOptions'=>function ($model, $key, $index, $grid){
             return ['class'=>'worker'];
          },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
            'header' => 'Vendor',
            'content' => function($model) {
                return $model->workerVendor->code." ".$model->workerVendor->name;
            }           
            ],
            [
            'header' => 'Name',
            'content' => function($model) {
                return $model->worker->code." ".$model->worker->name;
            }           
            ],

            [
            'header' => 'Month',
            'content' => function($model) {
                return Yii::$app->formatter->asDate($model->month,'php:M-Y');
            }           
            ],
            'base_salary',
            'salary',
            'allowance',
            'salary_with_allowance',
            'worker_deduction',
            'payable_salary',

            ['class' => 'yii\grid\ActionColumn',
             'template'=> '{view}{update} {delete}',
             'buttons' => [
                'view' => function ($url, $model, $key) {
                       
                       return Html::a('<i class="fa fa-eye" aria-hidden="true"></i>',
                           ['worker/salary-slip/'.$model->id],
                           ['target'=>'_blank']);
                    },  
                ],
            'urlCreator' => function ($action, $model, $key, $index) {
                   if($action == "update")
                        return Url::to(['worker/salary-form','id' => $model['id']]);
                   if($action == "delete")
                        return Url::to(['worker/delete-salary','id' => $model['id']]); 
               }    
            ],
        ],
    ]); ?>


</div>
</div>
</div>
</div>