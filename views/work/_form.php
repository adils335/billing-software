<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Work */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="work-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-md-4">
	
       <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    
	</div>
	
    <div class="col-md-4">
	
        <?= $form->field($model, 'unit')->widget(Select2::classname(), [
    'data' => \yii\helpers\ArrayHelper::map(\app\models\Uom::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
    'options' => ['placeholder' => 'Select a ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],
]); ?>
	</div>
	
    <div class="col-md-4">
	
        <?= $form->field($model, 'work_type')->widget(Select2::classname(), [
    'data' => \yii\helpers\ArrayHelper::map(\app\models\WorkType::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
    'options' => ['placeholder' => 'Select a ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],
]); ?>

	</div>
	
    <div class="col-md-12">
	
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

	</div>
	
    <?php ActiveForm::end(); ?>

</div>
