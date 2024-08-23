<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'UOM');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="uom-index">
<div class="uom-view box box-primary">
    <div class="box-header">

    <h1><?= Html::encode($this->title) ?><span class="pull-right"><?= Html::a(Yii::t('app', 'Create UOM'), ['create'], ['class' => 'btn btn-success']) ?></span></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',

            ['class' => 'yii\grid\ActionColumn','template'=>'{update} {delete}'],
        ],
    ]); ?>


</div>
</div>
</div>
