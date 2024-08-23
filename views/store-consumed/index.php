<?php

use app\models\StoreConsumed;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\Search\StoreConsumed $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Store Consumeds';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="store-consumed-index box box-primary"> 
    <div class="box-header with-border">

    <span class="pull-right">
        <?= Html::a('Create Store Consumed', ['create'], ['class' => 'btn btn-success']) ?>
    </span>
    <br>
    <br>

    <?=$this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'invoice_no',
            'bill_no',
            [
                'header'=>'Compnay Name',
                'content'=>function($model){
                    return $model->company->name;
                }
            ],
            [
                'header'=>'Billing Company Name',
                'content'=>function($model){
                    return $model->billingCompany->name;
                }
            ],

            [
                'header' => 'Status',
                'content' => function($model) {
                    return $model->StatusLabel;
                }           
            ],
            [
                'class' => ActionColumn::className(),
                'template'=> '{view}&nbsp;&nbsp{update}&nbsp;&nbsp;{delete}&nbsp;&nbsp;{pdf}',
                'buttons' => [
                    'pdf' => function ($url, $model, $key) {  
                        return Html::a('<i class="fa fa-file-pdf-o" aria-hidden="true"></i>',
                               ['store-consumed-file','id'=>$model->id],
                            ['target'=>'_blank']);
                        },	
                    ],
                'urlCreator' => function ($action, StoreConsumed $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>
    </div>
</div>
