<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Search\Work */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="work-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?//= $form->field($model, 'id') ?>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'name') ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'unit') ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'work_type') ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Reset',['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>
