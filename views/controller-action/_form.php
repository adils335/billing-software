<?php

use yii\bootstrap\Html; 
use yii\Helpers\Url; 
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2; 
use wbraganca\dynamicform\DynamicFormWidget;

/* @var $this yii\web\View */
/* @var $model app\models\ControllerAction */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="controller-action-form">

    <?php $index = 0;?>

    <div class="row">
    <?php $form = ActiveForm::begin([
       'id'=>'controller-action'
    ]); ?>
    
    <?php foreach($controllers as $data):
        ?>

        <div class="col-md-12">
            <?= $form->field($model, "[$index]controller")->hiddenInput(['value'=>$data])->label(false) ?>
            <?= $form->field($model, "[$index]action")->widget(Select2::classname(), [
                     'data' => $actionArray[$data],
                     'options' => ['placeholder' => 'Select ...','value' => $model->getActions($data)],
                     'pluginOptions' => [
                              'allowClear' => true,
                              'multiple' => true
                      ],
            ])->label($data); ?>
          
        </div>
        <?php $index++;?>
    <?php endforeach;?>
    
    <div class="col-sm-12">
       <div class="form-group">
           <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
       </div>
    </div>
    <?php ActiveForm::end(); ?>

    </div>
    </div>