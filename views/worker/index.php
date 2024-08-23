<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Search\Worker */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Workers');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="worker-index">
    <div class="worker-index box box-primary"> 
		
		<div class="box-header with-border">

    <h1><?= Html::encode($this->title) ?>
    <span class="pull-right">
        <?= Html::a(Yii::t('app', 'Create Worker'), ['create'], ['class' => 'btn btn-success btn-sm']) ?>
    </span>
</h1>

    <div class="box-body">
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
       // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'code',
            'name',
            'father_name',
            'mobile',
            
            [
                'header'  => 'Vendor',
                'content' => function($model){
                    return $model->workerVendor->name;
                }
            ],
            
            [
                'header'  => 'Joining Date',
                'content' => function($model){
                    return Yii::$app->formatter->asDate($model->joining_date,'php:d-m-Y');
                }
            ],
            [
                'header'=>'Company',
                'content'=>function($model){
                  return $model->company->name;
                }
            ],
            //'pancard_no',
            //'aadhar_no',
            //'address',
            //'state_id',
            //'district_id',
            //'pincode',
            //'worker_vendor_id',
            //'salary',
            //'last_balance',
            //'inout_type',
            //'joining_date',
            //'status',
            //'company_id',
            //'session',
            //'created_at',
            //'created_by',
            //'updated_at',
            //'updated_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
</div>
</div>
</div>
