<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SignatureMaster */

$this->title = Yii::t('app', 'Create Signature Master');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Signature Masters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="signature-master-create">
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
