<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\StoreIndents $model */

$this->title = 'Create Store Indents';
$this->params['breadcrumbs'][] = ['label' => 'Store Indents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="store-indents-create box box-primary"> 
    <div class="box-header with-border">

    <?= $this->render('_form', [
        'model' => $model,
        'modelsStoreIndentsItems'=>$modelsStoreIndentsItems
    ]) ?>

</div>
</div>
