<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2; 
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Search\Vendor */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vendor-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    
    <div class="row">

          <div class="col-md-3">
             <?php
                if(empty($model->session)){
                    $model->session = \app\models\Session::getCurrentSession();
                }
            ?>
	 
            <?= $form->field($model, 'session')->widget(Select2::classname(), [
                    'data' => \yii\helpers\ArrayHelper::map(\app\models\Session::find()->orderBy(['session'=>SORT_DESC])->asArray()->all(), 'session', 'session'),
                    'options' => ['placeholder' => 'Select ...'],
                    'pluginOptions' => [
                                    'allowClear' => true
                        ],
            ]);?>

		 </div>

           <div class="col-md-3"> 
    
                <?= $form->field($model, 'code') ?>
    
           </div>
          <div class="col-md-3"> 
               
               <?= $form->field($model, 'name') ?>
   
               </div>
    
          <div class="col-md-3"> 
               
               <?= $form->field($model, 'mobile') ?>
    
           </div>
    
          </div>

          <div class="row">
               
    
           <div class="col-sm-3">
                <?= $form->field($model, "company_id")->widget(Select2::classname(), [
                     'data' => \yii\helpers\ArrayHelper::map(\app\models\Company::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                     'options' => ['placeholder' => 'Select ...'],
                     'pluginOptions' => [
                                  'allowClear' => true
                          ],
                ]); ?>
           </div>

           <div class="col-md-3"> 
               
              <?= $form->field($model, "status")->
                dropDownList(\app\models\Vendor::buildStatus(), ['prompt'=>Yii::t('app', 'Select ...'),'class'=>'form-control from-head']) ?>
    
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
