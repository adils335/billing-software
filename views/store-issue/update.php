<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\StoreIssue $model */

$this->title = 'Update Store Issue: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Store Issues', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="store-issue-update box box-primary"> 
    <div class="box-header with-border">

    <?= $this->render('_form', [
        'model' => $model,
        'items'=>$items,
    ]) ?>
    </div>

</div>
