<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\PurchaseProduct $model */

$this->title = 'Update Purchase Product: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Purchase Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="purchase-product-update box box-primary"> 
    <div class="box-header with-border">

    <?= $this->render('_form', [
        'model' => $model,
        'items'=>$items,
    ]) ?>
    </div>

</div>
