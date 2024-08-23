<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\ModeOfTransport $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Mode Of Transports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="mode-of-transport-view">
    <div class="box box-primary">
        <div class="box-header with-border">
            <div class="box-body">
                <p>
                    <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </p>
    
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'name',
                        [
                            'label'=>'Status',
                            'value'=> $model->statusLabel,
                        ],
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>
