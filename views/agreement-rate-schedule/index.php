<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Agreement Rate Schedules');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agreement-rate-schedule-index">
<div class="agreement-rate box box-primary">
    <div class="box-header">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Agreement Rate Schedule'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'agreement_id',
            'item:ntext',
            'hsn_no',
            'unit',
            //'quantity',
            //'rate',
            //'company_id',
            //'session',
            //'created_at',
            //'created_by',
            //'updated_at',
            //'updated_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
</div>
</div>
