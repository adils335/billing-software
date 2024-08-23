<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\District */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="district-form">

  <div class="row">
    <?php $form = ActiveForm::begin(); ?>
   
   <div class="col-md-4">
        <?= $form->field($model, 'state_id')->label("State")->widget(Select2::classname(), [
    'data' => \yii\helpers\ArrayHelper::map(\app\models\State::find()->orderBy('id')->asArray()->all(), 'id', 'state'),
    'options' => ['placeholder' => 'Select a state ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],
]); ?>
    </div>
   
   <div class="col-md-4">
        <?= $form->field($model, 'district')->textInput(['maxlength' => true]) ?>
   </div>
   
   
   <div class="col-md-12">
     <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
     </div>
   </div>
   
    <?php ActiveForm::end(); ?>

</div>

