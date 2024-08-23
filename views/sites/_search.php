<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Search\SitesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sites-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="col-sm-2">
                <?= $form->field($model, 'name') ?>
            </div>
            <div class="col-sm-3">
                <?= $form->field($model, 'state_id')->widget(Select2::classname(), [
                        'data' => \yii\helpers\ArrayHelper::map(\app\models\State::find()->orderBy('id')->asArray()->all(), 'id', 'state'),
                        'options' => ['placeholder' => 'Select ...'],
                        'pluginOptions' => [
                                        'allowClear' => true
                            ],
                ]); ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'status')->widget(Select2::classname(), [
                               'data' =>\app\models\Sites::buildStatus(),
                               'options' => ['placeholder' => 'Select ...'],
                               'pluginOptions' => [
                                            'allowClear' => true,
                                    ],
               ]); ?>
            </div>
            <div class="col-sm-2">
                <div class="form-group"><br>
                    <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
                    <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
                </div>
            </div>
        </div>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
