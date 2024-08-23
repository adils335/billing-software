<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Search\VendorWorkRate */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vendor-work-rate-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'vendor_id')->widget(Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\models\Vendor::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                'options' => ['placeholder' => 'Select ...','class'=>'vendor'],
                'pluginOptions' => [
                            'allowClear' => true
                    ],
                ]); 
            ?>
        </div>
    </div>
    <?//= $form->field($model, 'id') ?>

    <?//= $form->field($model, 'vendor_id') ?>

    <?//= $form->field($model, 'work_type') ?>

    <?//= $form->field($model, 'work_name') ?>

    <?//= $form->field($model, 'rate') ?>

    <?php // echo $form->field($model, 'company_id') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <div class="row">

        <div class="col-md-12">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Reset',['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
