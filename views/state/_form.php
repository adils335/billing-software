<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\State */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="state-form">
  <div class="row">
    <?php $form = ActiveForm::begin(); ?>
     
	<div class="col-md-4"> 
	
        <?= $form->field($model, 'state')->textInput(['maxlength' => true]) ?>
   
    </div>
	<div class="col-md-4"> 
	
       <?= $form->field($model, 'state_tin')->textInput() ?>

    </div>
	<div class="col-md-4"> 
	
       <?= $form->field($model, 'state_code')->textInput(['maxlength' => true]) ?>

    </div>
	
	<div class="col-md-12"> 
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    </div>
	
    <?php ActiveForm::end(); ?>

</div>
</div>
