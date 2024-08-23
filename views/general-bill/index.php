<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use kartik\popover\PopoverX;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Search\AgreementBill */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'General Bills');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="general-bill-index">
<div class="general-bill-index box box-primary"> 
		
		<div class="box-header with-border"> 

    <h1><?= Html::encode($this->title) ?>
    <span class="pull-right">
        <?= Html::a(Yii::t('app', 'Create General Bill'), ['create'], ['class' => 'btn btn-success btn-sm']) ?>
    </span>
</h1>

    <?=$this->render('_search', ['model' => $searchModel]); ?>

    <div class="box-body">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'invoice_no',
            'invoice_date',
            [
                'header'=>'Company',
                'content' => function($model){
                    return $model->company->name;
                }
            ],
            [
                'header'=>'Billing Company',
                'content' => function($model){
                    return $model->agreement->contractCompany->name;
                }
            ],
            'base_amount',
            'taxable_amount',
            'tax_amount',
            'payable_amount',

            ['class' => 'yii\grid\ActionColumn',
			 'template'=> '{view}&nbsp;&nbsp;{pdf}&nbsp;&nbsp;{update}&nbsp;&nbsp;{sync_irn}&nbsp;&nbsp;{cancel_irn}&nbsp;&nbsp;{delete}',
			 
			 'buttons' => [
                'view' => function ($url, $model, $key) { 
					   return Html::a('<i class="fa fa-eye" aria-hidden="true"></i>',
     					   ['general-bill/view','id'=>$model->id]);
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
                        $content = '<p class="text-justify">' .
    'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.' . 
    '</p>';
                        return Html::a('<i class="fa fa-times" aria-hidden="true"></i>',
                        ['#'],['bill_id'=>$model->id ,'class'=>'show-sidebar-popup','data-url'=>Url::to(['//einvoice/cancel-reason-irn','id'=>$model->id]),'title'=>'cancel irn no']);
                    }
                },
                'delete' => function ($url, $model, $key) {
                        $actionUrl = Url::to('../general-bill/change-status');
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
                        return Url::to(['general-bill/update','id' => $model['agreement_id']]);
                   }	
    			],
        ],
    ]); ?>


</div>
</div>
</div>
</div>
<?php 
$script = <<<JS

JS;
$this->registerJs($script);
