<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\StoreConsumed $model */

$this->title = 'Update Store Consumed: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Store Consumeds', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="store-consumed-update box box-primary"> 
    <div class="box-header with-border">

    <?= $this->render('_form', [
        'model' => $model,
        'items'=>$items,
    ]) ?>
    </div>

</div>
