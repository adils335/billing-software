<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DeductionMaster */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="deduction-master-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rate')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deduction_type')->dropDownList(\app\models\DeductionMaster::buildDeductionType(), ['prompt'=>Yii::t('app', 'Select ...')]) ?>
    
    <?= $form->field($model, 'type')->dropDownList(\app\models\DeductionMaster::buildType(), ['prompt'=>Yii::t('app', 'Select ...')]) ?>
    
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
