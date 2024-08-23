<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Search\DeductionMasterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Deduction Masters');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deduction-master-index">
   <div class="deduction-master-index box box-primary"> 
        
        <div class="box-header with-border"> 

    <h1><?= Html::encode($this->title) ?>
        
    <span class="pull-right">
        <?= Html::a(Yii::t('app', 'Create Deduction Master'), ['create'], ['class' => 'btn btn-success btn-sm']) ?>
    </span>

    </h1>

    <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',
            [
                'header' => 'Type',
                'content' => function($model){
                    return $model->typeLabel;
                }
            ],
            [
                'header' => 'Deduction Type',
                'content' => function($model){
                    return $model->deductionTypeLabel;
                }
            ],
            'rate',
            //'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
</div>
</div>
</div>
