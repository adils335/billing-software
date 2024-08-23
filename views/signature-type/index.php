<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Signature Types');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="signature-type-index">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h1>
                <?= Html::encode($this->title) ?>
                <span class="pull-right">
                    <?= Html::a(Yii::t('app', 'New'), ['create'], ['class' => 'btn btn-success btn-sm']) ?>
                </span>
            </h1>
            <div class="box-body">
                <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'type',
                            ['class' => 'yii\grid\ActionColumn','template'=>'{update}{delete}'],
                        ],
               ]); ?>
            </div>
        </div>
    </div>
</div>
