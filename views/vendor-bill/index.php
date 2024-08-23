<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Search\VendorBill */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Vendor Bills');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendor-bill-index">
   <div class="vendor-bill-index box box-primary"> 
		
		<div class="box-header with-border"> 

    <h1><?= Html::encode($this->title) ?>
    <span class="pull-right">
        <?= Html::a(Yii::t('app', 'Create Vendor Bill'), ['create'], ['class' => 'btn btn-success btn-sm']) ?>
    </span></h1>


    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="box-body">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

		    [
            'header' => 'Date',
            'content' => function($model) {
                return Yii::$app->formatter->asDate($model->bill_date,'php:d-m-Y');
            }           
            ],
			
		    [
            'header' => 'Document No',
            'content' => function($model) {
                return $model->session."/".sprintf("%02d",$model->bill_no);
            }           
            ],
			
		    [
            'header' => 'Vendor',
            'content' => function($model) {
                return $model->vendor->name . " " . $model->vendor->code;
            }           
            ],
			
            'invoice_no',
			
		    [
            'header' => 'Date',
            'content' => function($model) {
                return Yii::$app->formatter->asDate($model->invoice_date,'php:d-m-Y');
            }           
            ],
			
            //'base_amount',
            //'schedule',
            //'schedule_rate',
            //'schedule_amount',
            'taxable_amount',
            'tax_amount',
            [
            'header' => 'Payable Amount',
            'content' => function($model) {
                $payayble_amount = round($model->payable_amount);
                $payayble_amount = number_format($payayble_amount, 2);
                $payayble_amount = str_replace(',', '', $payayble_amount);
                return $payayble_amount;
            }           
            ],
            //'payable_amount',
            //'deduction_amount',
            //'pay_amount',
            //'company_id',
            //'session',
            //'created_at',
            //'created_by',
            //'updated_at',
            //'updated_by',

            
            ['class' => 'yii\grid\ActionColumn',
			 'template'=> '{view}{update} {delete}',
			 'buttons' => [
                'view' => function ($url, $model, $key) {
					   
					   return Html::a('<i class="fa fa-eye" aria-hidden="true"></i>',
     					   ['vendor bill/'.$model->company_id.'-'.$model->session.'-'.$model->bill_no.'.pdf'],
						   ['target'=>'_blank']);
                    },	
                ],	
			],
        ],
    ]); ?>


</div>
</div>
</div>
</div>
