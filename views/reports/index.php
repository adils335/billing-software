<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Search\AgreementBill */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Gst Report');
$this->params['breadcrumbs'][] = $this->title;

$gridColumns = [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'label' =>'Contract Company',
        'value' => function( $model ){
           return $model->contractCompany->name;
        } 
    ],
    'agreement_no',
    'invoice_date',
    [
        'class' => 'yii\grid\ActionColumn',
        'template' => '{view}{update}{delete}',
        'buttons' => [
             'delete' => function ($url, $model, $key) {
                   $html = "<select class='bill-action' data-id='" . $model->id . "'>";
                   $statuses = $model::buildStatus();
                       foreach( $statuses as $key => $status )
                           $html .= "<option value='$key'>$status</option>";
                   $html .= "</select>";
                 return  $html;
             }
        ]
    ],
];
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
                <?php $exportWidget = ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $gridColumns,
                    'clearBuffers' => true, //optional
                ]);?>
                <?php  echo $this->render('_search', ['model' => $searchModel,'exportWidget'=>$exportWidget]);?>
                
                <?php if( !empty( $pdf_file ) ):?>
                    <a href="<?= $pdf_file?>" target="_blank" > View Report</a>
                <?php endif;?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $gridColumns,
                ]); ?>
			</div>   
		</div>   
	</div>   
</div>   
