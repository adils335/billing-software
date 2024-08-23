<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\AgreementProduct $model */

$this->title = 'Create Agreement Product';
$this->params['breadcrumbs'][] = ['label' => 'Agreement Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="agreement-product-create box box-primary"> 
    <div class="box-header with-border">

    <?= $this->render('_form', [
        'model' => $model,
        'items' => $items,
    ]) ?>

    </div>
</div>
