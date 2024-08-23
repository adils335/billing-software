<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\Assessment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="assessment-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'lead_id') ?>

    <?= $form->field($model, 'ref_no') ?>

    <?= $form->field($model, 'assemble_deassemble_done_by') ?>

    <?= $form->field($model, 'assemble_table_qty') ?>

    <?php // echo $form->field($model, 'assemble_bed_qty') ?>

    <?php // echo $form->field($model, 'assemble_cupboard_qty') ?>

    <?php // echo $form->field($model, 'assemble_note') ?>

    <?php // echo $form->field($model, 'drill_done_by') ?>

    <?php // echo $form->field($model, 'drill_cupboard_qty') ?>

    <?php // echo $form->field($model, 'drill_lamp_qty') ?>

    <?php // echo $form->field($model, 'drill_note') ?>

    <?php // echo $form->field($model, 'packing_done_by') ?>

    <?php // echo $form->field($model, 'packing_glass') ?>

    <?php // echo $form->field($model, 'packing_all_item') ?>

    <?php // echo $form->field($model, 'packing_note') ?>

    <?php // echo $form->field($model, 'unpacking_done_by') ?>

    <?php // echo $form->field($model, 'unpacking_glass') ?>

    <?php // echo $form->field($model, 'unpacking_all_items') ?>

    <?php // echo $form->field($model, 'unpacking_note') ?>

    <?php // echo $form->field($model, 'heavy_duty_piano') ?>

    <?php // echo $form->field($model, 'heavy_duty_safe') ?>

    <?php // echo $form->field($model, 'heavy_duty_note') ?>

    <?php // echo $form->field($model, 'task_other_note') ?>

    <?php // echo $form->field($model, 'transport_volume') ?>

    <?php // echo $form->field($model, 'team') ?>

    <?php // echo $form->field($model, 'vehicle') ?>

    <?php // echo $form->field($model, 'moving_equipments') ?>

    <?php // echo $form->field($model, 'price_note') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
