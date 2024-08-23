<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Search\ScheduleRateMaster */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="schedule-rate-master-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>


    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'type') ?>
        </div>
    </div>
    <?//= $form->field($model, 'id') ?>

    <?//= $form->field($model, 'srmid') ?>

    <?//= $form->field($model, 'type') ?>

    <?//= $form->field($model, 'item') ?>

    <?//= $form->field($model, 'hsn_no') ?>

    <?php // echo $form->field($model, 'unit') ?>

    <?php // echo $form->field($model, 'company_id') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Reset',['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
