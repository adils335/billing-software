<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Search\State */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="state-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

   <div class="row">
      <div class="col-md-4">
         <?= $form->field($model, 'state') ?>
      </div>
	  
      <div class="col-md-4">
         <?= $form->field($model, 'state_tin') ?>

      </div>
	  
      <div class="col-md-4">
         <?= $form->field($model, 'state_code') ?>
      
      </div>
	
    </div>
	
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
