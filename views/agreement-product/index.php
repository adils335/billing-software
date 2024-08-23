<?php

use app\models\AgreementProduct;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\Search\AgreementProduct $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Agreement Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agreement-product-index box box-primary"> 
    <div class="box-header with-border">

    <span class="pull-right">
        <?= Html::a('Create Agreement Product', ['create'], ['class' => 'btn btn-success']) ?>
    </span>

    <br>
    <br>

    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'header'=>'State',
                'content'=>function($model){
                    return $model->state->state;
                }
            ],
            [
                'header'=>'District',
                'content'=>function($model){
                    return $model->district->district;
                }
            ],
            [
                'header'=>'Billing Company Name',
                'content'=>function($model){
                    return $model->billingCompany->name;
                }
            ],

            [
                'header'=>'Agreement Number',
                'content'=>function($model){
                    return $model->agreement->agreement_no;
                }
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, AgreementProduct $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    </div>
</div>
