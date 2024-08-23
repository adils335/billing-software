<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Search\Company */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="company-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    
	<div class="row">
	   <div class="col-md-4">
          <?= $form->field($model, 'name') ?>
       </div>
       
	  <div class="col-md-4">
      <?= $form->field($model, 'type')->label("Type")->widget(Select2::classname(), [
    'data' => \yii\helpers\ArrayHelper::map(\app\models\CompanyType::find()->orderBy('id')->asArray()->all(), 'id', 'type'),
    'options' => ['placeholder' => 'Select a Type ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],
]); ?>
    </div> 
       
    <?php // echo $form->field($model, 'district') ?>

    <?php // echo $form->field($model, 'pincode') ?>

    <?php // echo $form->field($model, 'person') ?>

    <?php // echo $form->field($model, 'number') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'pancard_no') ?>

    <?php // echo $form->field($model, 'gst_no') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>
   <div class="col-md-12">
       <div class="form-group">
           <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
           <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
       </div>
   </div>
    <?php ActiveForm::end(); ?>

</div>
