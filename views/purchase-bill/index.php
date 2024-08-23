<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Search\PurchaseBill */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Purchase Bills');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchase-bill-index box box-primary">

	<div class="box-header with-border"> 
    <h1><?= Html::encode($this->title) ?>
    <span class="pull-right">
        <?= Html::a(Yii::t('app', 'Create Purchase Bill'), ['create'], ['class' => 'btn btn-success']) ?>
    </span>
    </h1>


    <?=$this->render('_search', ['model' => $searchModel]); ?>

    <div class="box-body">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'gstin',
            'invoice_no',
            [
                'header' => 'Date',
                'content' => function($model){
                    return Yii::$app->formatter->asDate($model->date,'php:d-m-Y');
                },
            ],
            [
                'header' => 'Taxable Amount',
                'content' => function($model){
                    return $model->amount;
                },
            ],
            [
                'header' => 'Total Tax',
                'content' => function($model){
                    return $model->tax;
                },
            ],
            [
                'header' => 'Amount',
                'content' => function($model){
                    return $model->total;
                },
            ],
            //'amount',
            //'tax',
            //'total',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    </div>
  </div>
</div>
