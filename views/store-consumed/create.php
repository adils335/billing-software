<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\StoreConsumed $model */

$this->title = 'Create Store Consumed';
$this->params['breadcrumbs'][] = ['label' => 'Store Consumeds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="store-consumed-create box box-primary"> 
    <div class="box-header with-border">

    <?= $this->render('_form', [
        'model' => $model,
        'items'=>$items,
    ]) ?>
    </div>

</div>
