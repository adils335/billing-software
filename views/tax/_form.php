<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Tax */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tax-form">
 
  <div class="row">
    <?php $form = ActiveForm::begin(); ?>
   
   <div class="col-md-4">
       <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
   </div>
   
   <div class="col-md-4">
      <?= $form->field($model, 'tax_type')->dropDownList(\app\models\Tax::buildTaxType(), ['prompt'=>Yii::t('app', 'Select ...')]) ?>
    </div>
	
   <div class="col-md-12">
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>
 </div>
    <?php ActiveForm::end(); ?>

</div>

</div>