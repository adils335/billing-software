<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\ContractCompany */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="contract-company-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'company_id')->widget(Select2::classname(), [
               'data' => \app\models\Common::getCompanies(),
               'options' => ['placeholder' => 'Select a Company ...'],
               'pluginOptions' => [
                   'allowClear' => true
               ],
            ]); ?>
        </div>
        <div class="col-sm-9">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
