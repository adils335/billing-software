<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SignatureType */

$this->title = Yii::t('app', 'Update Signature Type: {name}', [
    'name' => $model->type,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Signature Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="signature-type-update">
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
