<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Contract Companies');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contract-company-index">
<div class="contract-company-index box box-primary"> 
		
		<div class="box-header with-border">

    <h1><?= Html::encode($this->title) ?>
    <span class="pull-right">
        <?= Html::a(Yii::t('app', 'Create Contract Company'), ['create'], ['class' => 'btn btn-success']) ?>
    </span></h1>


    <div class="box-body">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            [
                'label'=>'Company',
                'value'=>function($model){
                    return $model->company->name;
                }
            ],
            ['class' => 'yii\grid\ActionColumn',
			'template'=>'{view} {update} {delete} {gst}',
			'buttons' => [
                'gst' => function($url, $model, $key) {     // render your custom button
                    return Html::a("<span class='fa fa-plus'>GST</span>",['company-gst','id'=>$model->id],['class'=>'']);
                }
            ]
			],
        ],
    ]); ?>


</div>
</div>
</div>
</div>
