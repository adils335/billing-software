<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ModeOfTransport $model */

$this->title = 'Update Mode Of Transport: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Mode Of Transports', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mode-of-transport-update">
    <div class="box box-primary">
        <div class="box-header with-border">
            <div class="box-body">
                 <?= $this->render('_form', [
                    'model' => $model,
                 ]) ?>
            </div>
        </div>
    </div>
</div>
