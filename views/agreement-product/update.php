<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\AgreementProduct $model */

//$this->title = 'Update Agreement Product: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Agreement Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="agreement-product-update box box-primary"> 
    <div class="box-header with-border">

    <?= $this->render('_form', [
        'model' => $model,
        'items' => $items,
    ]) ?>

    </div>
</div>
