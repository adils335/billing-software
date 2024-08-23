<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\StoreProducts $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Store Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="store-products-view box box-primary"> 
    <div class="box-header with-border">

    <span class="pull-right">
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </span>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            [
                'label'=>'Uom',
                'value'=> $model->uom->name,
            ],
            [
                'label'=>'Company',
                'value'=> $model->company->name,
            ],
            [
                'label'=>'Status',
                'value'=> $model->statusLabel,
            ],
        ],
    ]) ?>

</div>
</div>
