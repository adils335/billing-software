<?php

use app\models\ModeOfTransport;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\Search\ModeOfTransport $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Mode Of Transports';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mode-of-transport-index">
    <div class="box box-primary">
        <div class="box-header with-border">

            <h1>
                <?= Html::encode($this->title) ?>
                <span class="pull-right">
                    <?= Html::a(Yii::t('app', 'New'), ['create'], ['class' => 'btn btn-success btn-sm']) ?>
                </span>
            </h1>

            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    
            <div class="box-body">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'name',
                        [
                            'header' => 'Status',
                            'content' => function($model) {
                                return $model->statusLabel;
                            }           
                        ],
                        ['class' => 'yii\grid\ActionColumn','template'=>'{view}{update}{delete}'],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
