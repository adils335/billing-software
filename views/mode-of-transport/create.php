<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ModeOfTransport $model */

$this->title = 'Create Mode Of Transport';
$this->params['breadcrumbs'][] = ['label' => 'Mode Of Transports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="mode-of-transport-create">
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
