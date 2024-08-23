<?php

use app\models\PurchaseProduct;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\Search\PurchaseProduct $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Purchase Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchase-product-index box box-primary"> 
    <div class="box-header with-border">

    <span class="pull-right">
        <?= Html::a('Create Purchase Product', ['create'], ['class' => 'btn btn-success']) ?>
    </span>

    <br>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'header'=>'Compnay Name',
                'content'=>function($model){
                    return $model->company->name;
                }
            ],
            'session',
            'invoice_no',
            [
                'header' => 'Invoice Date',
                'content' => function($model) {
                    return Yii::$app->formatter->asDate($model->invoice_date,'php:d-m-Y');
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
                'urlCreator' => function ($action, PurchaseProduct $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>
    </div>
</div>
