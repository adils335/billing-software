<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Holidays */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="holidays-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?=  $form->field($model, 'date')->widget(DatePicker::classname(), [
                             'options' => ['placeholder' => 'Enter date ...'],
                             'pluginOptions' => [
                                          'autoclose'=>true,
                                          'orientation'=>'bottom',
		                                  'format'=>'yyyy-mm-dd'
                                     ]
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
