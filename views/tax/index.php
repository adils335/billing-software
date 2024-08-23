<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Taxes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tax-index">
<div class="tax-index box box-primary"> 
		
		<div class="box-header with-border">
    <h1><?= Html::encode($this->title) ?>
    <span class="pull-right">
        <?= Html::a(Yii::t('app', 'Create Tax'), ['create'], ['class' => 'btn btn-success']) ?>
    </span>
</h1>


    <div class="box-body">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
		    [
            'header' => 'tax_type',
            'content' => function($model) {
                return $model->taxTypeLabel;
            }           
            ],
			

            ['class' => 'yii\grid\ActionColumn',
			'template'=>'{update} {delete}'],
        ],
    ]); ?>


</div>
</div>
</div>
</div>
