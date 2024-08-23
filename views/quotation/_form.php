<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Assessment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="assessment-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'lead_id')->textInput() ?>

        <?= $form->field($model, 'ref_no')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'assemble_deassemble_done_by')->textInput() ?>

        <?= $form->field($model, 'assemble_table_qty')->textInput() ?>

        <?= $form->field($model, 'assemble_bed_qty')->textInput() ?>

        <?= $form->field($model, 'assemble_cupboard_qty')->textInput() ?>

        <?= $form->field($model, 'assemble_note')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'drill_done_by')->textInput() ?>

        <?= $form->field($model, 'drill_cupboard_qty')->textInput() ?>

        <?= $form->field($model, 'drill_lamp_qty')->textInput() ?>

        <?= $form->field($model, 'drill_note')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'packing_done_by')->textInput() ?>

        <?= $form->field($model, 'packing_glass')->textInput() ?>

        <?= $form->field($model, 'packing_all_item')->textInput() ?>

        <?= $form->field($model, 'packing_note')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'unpacking_done_by')->textInput() ?>

        <?= $form->field($model, 'unpacking_glass')->textInput() ?>

        <?= $form->field($model, 'unpacking_all_items')->textInput() ?>

        <?= $form->field($model, 'unpacking_note')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'heavy_duty_piano')->textInput() ?>

        <?= $form->field($model, 'heavy_duty_safe')->textInput() ?>

        <?= $form->field($model, 'heavy_duty_note')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'task_other_note')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'transport_volume')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'team')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'vehicle')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'moving_equipments')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'price_note')->textarea(['rows' => 6]) ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
