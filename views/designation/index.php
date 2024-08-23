<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Designations');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="designation-index">
   <div class="designation-index box box-primary"> 
		
		<div class="box-header with-border"> 
    <h1><?= Html::encode($this->title) ?>
    <span class="pull-right">
        <?= Html::a(Yii::t('app', 'Create Designation'), ['create'], ['class' => 'btn btn-success']) ?>
    </span></h1>



    <div class="box-body">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'designation',

            ['class' => 'yii\grid\ActionColumn',
			 'template' => '{update} {delete}'
			],
        ],
    ]); ?>


</div>
</div>
</div>
</div>
