<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Agreement;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\Agreement */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Quotations');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agreement-index box box-primary">
    <?php Pjax::begin(); ?>
    <div class="box-header with-border">
        <div class="pull-right">
            <?= Html::a(Yii::t('app', 'Create Quotation'), ['create-quotation'], ['class' => 'btn btn-success btn-flat btn-xs']) ?>
        </div>
    </div>
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'file_no',
            'agreement_no',
            'taxable_amount',
            'tax_amount',
            'payable_amount',
            [
                'label' => 'Status',
                'value' => function($model){
                    return $model->statusLabel;
                }
            ],
            ['class' => 'yii\grid\ActionColumn',
                'template'=> '{view}{update}{delete}',
    			'urlCreator' => function ($action, $model, $key, $index) {
    				   if($action == "update"){
                            return Url::to(['//quotation/create-quotation','id' => $model['id']]);
    				   }elseif($action == "delete"){
    				       return Url::to(['//quotation/delete','id' => $model['id']]);
    				   }elseif($action == "view"){
    				       return Url::to(['//quotation/view','id' => $model['id']]);
    				   }   
                   }	
    			],
        ],
    ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>
