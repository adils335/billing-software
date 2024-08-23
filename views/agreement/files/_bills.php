<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm; 
use yii\grid\GridView;
$formatter = \Yii::$app->formatter;
?>


<div class="row">
    <div class="col-md-12">
        <div class="agreement-bill-details box box-primary">
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
            'template'=> '{view}&nbsp;&nbsp;{pdf}&nbsp;&nbsp;{update}&nbsp;&nbsp;{sync_irn}&nbsp;&nbsp;{cancel_irn}&nbsp;&nbsp;{delete}',
			 
			 'buttons' => [
                'view' => function ($url, $model, $key) { 
					   return Html::a('<i class="fa fa-eye" aria-hidden="true"></i>',
     					   ['agreement-bill/view','id'=>$model->id]);
                },
                'pdf' => function ($url, $model, $key) {
				   
                    return Html::a('<i class="fa fa-file-pdf-o" aria-hidden="true"></i>',
                         ['agreement bill/'.$model->company_id.'-'.$model->session.'-'.$model->invoice_no.'.pdf'],
                        ['target'=>'_blank']);
                },
                'sync_irn' => function ($url, $model, $key) { 
                    return Html::a('<i class="fa fa-sync" aria-hidden="true"></i>',
                    ['#'],['class'=>'show-sidebar-popup','data-url'=>Url::to(['//einvoice/verify-irn','id'=>$model->id]),"swidth"=>"70",'title'=>'Sync to einvoice portal']);
                },
                'cancel_irn' => function ($url, $model, $key) { 
                    if( !empty( $model->irn_no ) && empty( $model->cancel_date ) ){
                        return Html::a('<i class="fa fa-times" aria-hidden="true"></i>',
                        ['#'],['bill_id'=>$model->id ,'class'=>'show-sidebar-popup','data-url'=>Url::to(['//einvoice/cancel-reason-irn','id'=>$model->id]),'title'=>'cancel irn no']);
                    }
                },
                'delete' => function ($url, $model, $key) {
                        $actionUrl = Url::to('../agreement-bill/change-status');
                        $html = "<select class='bill-action' id='" . $model->id . "' url='$actionUrl' refresh='0'>";
                        $statuses = $model::buildStatus();
                            foreach( $statuses as $key => $status ){
                                $selected = $key == $model->status ? "selected":"";
                                $html .= "<option value='$key' $selected>$status</option>";
                            }
                        $html .= "</select>";
                      return  $html;
                 }
                
                ],
                'visibleButtons' => [
                    'sync' => function ($model, $key, $index) {
                        return $model->has_sync == 1;
                    },
                ],
    			'urlCreator' => function ($action, $model, $key, $index) {
    				   if($action == "update")
                            return Url::to(['agreement-bill/update','id' => $model['id']]);
    				  // if($action == "delete")
                        //    return Url::to(['agreement-bill/delete','id' => $model['id']]);	
                   }	
    			],
        ],
    ]); ?>
			</div>   
		</div>   
	</div>   
</div>  