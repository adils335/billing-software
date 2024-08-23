<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\StoreIndents $model */

$this->title = 'Update Store Indents: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Store Indents', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="store-indents-update box box-primary"> 
    <div class="box-header with-border">


    <?= $this->render('_form', [
        'model' => $model,
        'modelsStoreIndentsItems'=>$modelsStoreIndentsItems
    ]) ?>
    </div>

</div>
