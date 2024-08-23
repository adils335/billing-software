<?php

use app\models\StoreProducts;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\Search\StoreProducts $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Store Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="store-products-index box box-primary"> 
    <div class="box-header with-border"> 
    <span class="pull-right">
        <?= Html::a('Create Store Products', ['create'], ['class' => 'btn btn-success']) ?>
    </span>
    <br>
    <br>
    
    <?=$this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            [
                'header'=>'Uom',
                'content'=>function($model){
                    return $model->uom->name;
                }
            ],
            [
                'header'=>'Company Name',
                'content'=>function($model){
                    return $model->company->name;
                }  
            ],
            [
                'header'=>'Status',
                'content'=>function($model){
                    return $model->StatusLabel;
                }  
            ],
            //'created_at',
            //'created_by',
            //'updated_at',
            //'updated_by',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, StoreProducts $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
</div>
