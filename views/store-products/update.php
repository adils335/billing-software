<?php

use yii\helpers\Html;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\StoreProducts $model */

$this->title = 'Update Store Products: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Store Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="store-products-update box box-primary"> 
    <div class="box-header with-border">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">

        <div class="col-md-3">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'uom_id')->widget(Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\models\Uom::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                'options' => ['placeholder' => 'Select a Uom ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
                ]); 
            ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'company_id')->widget(Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\models\Company::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                'options' => ['placeholder' => 'Select a Company ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
                ]); 
            ?>
        </div>
    </div>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    <?php ActiveForm::end(); ?>   
    </div>
</div>
