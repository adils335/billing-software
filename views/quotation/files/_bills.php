<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm; 
use yii\grid\GridView;
$formatter = \Yii::$app->formatter;
?>


<div class="row">
    <div class="col-md-12">
        <div class="agreement-bill-details box box-primary  collapsed-box">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-history margin-r-5"></i><?=Yii::t('app', 'Bill')?></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>   
            </div>   
			 <div class="box-body">  
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
                            'base_amount',
                            'tax_amount',
                            'payable_amount',

                                ['class' => 'yii\grid\ActionColumn',
                    			 'template'=> '{view}{update} {delete}',
                    			 'buttons' => [
                                    'view' => function ($url, $model, $key) {
                    					   
                    					   return Html::a('<i class="fa fa-eye" aria-hidden="true"></i>',
                         					   ['quotation-bill/'.$model->company_id.'-'.$model->session.'-'.$model->invoice_no.'.pdf'],
                    						   ['target'=>'_blank']);
                                        },	
                                    ],
                    			'urlCreator' => function ($action, $model, $key, $index) {
                    				   if($action == "update")
                                            return Url::to(['agreement-bill/update','id' => $model['id']]);
                    				   if($action == "delete")
                                            return Url::to(['agreement-bill/delete','id' => $model['id']]);	
                                   }	
                    			],
                            ],
                        ]); ?>
			</div>   
		</div>   
	</div>   
</div>   