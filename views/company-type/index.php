<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Company Types');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-type-index">
<div class="company-type-index box box-primary"> 
		
		<div class="box-header with-border"> 
    <h1><?= Html::encode($this->title) ?>
    <span class="pull-right">
        <?= Html::a(Yii::t('app', 'Create Company Type'), ['create'], ['class' => 'btn btn-success']) ?>
    </span>

</h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'type',

            ['class' => 'yii\grid\ActionColumn',
			'template'=>'{update} {delete}'],
        ],
    ]); ?>


</div>
</div>
</div>
