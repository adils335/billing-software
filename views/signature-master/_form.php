<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\editors\Summernote;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\SignatureMaster */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="signature-master-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'company_id')->widget(Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\models\Company::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                'options' => ['placeholder' => 'Select a Company ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
    ]); ?>
    
    <?= $form->field($model, 'type_id')->widget(Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\models\SignatureType::find()->orderBy('id')->asArray()->all(), 'id', 'type'),
                'options' => ['placeholder' => 'Select...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
    ]); ?>

    <?= $form->field($model, 'signature')->widget(Summernote::class, [
    'options' => ['placeholder' => 'Add Your signture','class'=>'']
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
