<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Search\TaxLedger */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tax-ledger-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'page_id') ?>

    <?= $form->field($model, 'page') ?>

    <?= $form->field($model, 'date') ?>

    <?= $form->field($model, 'tax_id') ?>

    <?php // echo $form->field($model, 'tax_type') ?>

    <?php // echo $form->field($model, 'rate') ?>

    <?php // echo $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'inout') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'company_id') ?>

    <?php // echo $form->field($model, 'session') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
