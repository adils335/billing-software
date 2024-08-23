<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Search\AgreementBill */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Gst Report');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="agreement-bill-details box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-history margin-r-5"></i><?=Yii::t('app', 'Gst Report')?></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>   
            </div>   
			 <div class="box-body">  
        <?php  echo $this->render('../_gst_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
       
            [
               'attribute' => 'invoice_no',
               'label' => Yii::t('app', 'Invoice'),
               'contentOptions' => ['nowrap' => 'nowrap'],
               'content' => function($model){
                            
                            return $model->session."/".sprintf("%02d",$model->invoice_no);
                            
                    },
               'visible'=>true,
            ],
     
            [
               'attribute' => 'invoice_date',
               'label' => Yii::t('app', 'Invoice Date'),
               'contentOptions' => ['nowrap' => 'nowrap'],
               'content' => function($model){
                            
                            return Yii::$app->formatter->asDate($model->invoice_date,'php:d-m-Y');
                            
                    },
               'visible'=>true,
            ],
            'taxable_amount',
            'tax_amount',

            ['class' => 'yii\grid\ActionColumn',
			 'template'=> '{view}',
			 'buttons' => [
                'view' => function ($url, $model, $key) {
					   
					   return Html::a('<i class="fa fa-eye" aria-hidden="true"></i>',
     					   ['agreement bill/'.$model->company_id.'-'.$model->session.'-'.$model->invoice_no.'.pdf'],
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
