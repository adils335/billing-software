<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Search\WorkerVendor */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Worker Vendors');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="worker-vendor-index">
    <div class="sites-index box box-primary"> 
		
		<div class="box-header with-border">

    <h1><?= Html::encode($this->title) ?>
    <span class="pull-right">
        <?= Html::a(Yii::t('app', 'Create Worker Vendor'), ['create'], ['class' => 'btn btn-success btn-sm']) ?>
    </span>
    </h1>

    <div class="box-body">
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'code',
            'name',
            'father_name',
            [
                'header'=>'Address',
                'content'=>function($model){
                    $address = $model->address;
                    if($model->district_id){
                        $address .= " ".$model->district->district;
                    }
                    if($model->state_id){
                        $address .= " ".$model->state->state;
                    }
                  return $model->address;
                },
                'contentOptions'=>['style'=>'width: 20%;']
            ],
            'mobile',
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
            //'commission_type',
            //'commission',
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
