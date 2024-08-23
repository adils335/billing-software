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
$taxesArray = [];
foreach( $taxes as $billTax ){
    $taxesArray[] = array(
        'label' => $billTax['name'],
        'value' => function( $model ) use ($billTax){
            return $model->getTaxAmountById($billTax['tax_id']);
        }
    );
}
$taxesArray[] = [
    'label'=>'Invoice Value',
    'value' => function( $model ){
        return $model->payable_amount;
    }
];
$gridColumns = [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'label'=>'Contract Company',
        'value' => function( $model ){
            return $model->agreement->contractCompany->name;
        }
    ],
    [
        'label'=>'Agreement No',
        'value' => function( $model ){
            return $model->agreement->agreement_no;
        }
    ],
    [
        'label'=>'Place of Supply',
        'value' => function( $model ){
            return $model->billingCompanyState->state;
        }
    ],
    [
        'label'=>'GSTIN of Recipient',
        'value' => function( $model ){
            return $model->billing_company_gst;
        }
    ],
    [
        'label'=>'Invoice Number',
        'value' => function( $model ){
            return $model->invoiceNo;
        }
    ],
    [
        'label'=>'Invoice Date',
        'value' => function( $model ){
            return \yii::$app->formatter->asDate($model->invoice_date,'php:d-m-Y');
        }
    ],
];
$gridColumns = array_merge($gridColumns, $taxesArray);
//echo "<pre>";print_r( $gridColumns );die();
?>

<div class="row">
    <div class="col-md-12">
        <div class="agreement-bill-details box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-history margin-r-5"></i><?=Yii::t('app', 'Gst Paid')?></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>   
            </div>   
			 <div class="box-body">  
                <?php  echo $this->render('_gst_search', ['model' => $searchModel]); 
                // Renders a export dropdown menu
                echo ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $gridColumns,
                    'clearBuffers' => true, //optional
                ]);
                ?>
                
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    //'filterModel' => $searchModel,
                    'columns' => $gridColumns,
                ]); ?>
			</div>   
		</div>   
	</div>   
</div>   
