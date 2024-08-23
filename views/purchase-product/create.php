<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\PurchaseProduct $model */

$this->title = 'Create Purchase Product';
$this->params['breadcrumbs'][] = ['label' => 'Purchase Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="purchase-product-create box box-primary"> 
    <div class="box-header with-border">

        <?= $this->render('_form', [
            'model' => $model,
            'items'=>$items,
        ]) ?>
    </div>

</div>
