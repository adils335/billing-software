<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2; 

/* @var $this yii\web\View */
/* @var $model app\models\Search\SiteDues */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="site-dues-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="row">

           <div class="col-md-2"> 
    
                <?= $form->field($model, 'code') ?>
    
           </div>
    
           <div class="col-md-2"> 
               
                <?= $form->field($model, 'name') ?>
    
           </div>
    
           <div class="col-md-2"> 
               
               <?= $form->field($model, 'mobile') ?>
    
           </div>
    
           <div class="col-md-3">
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
                dropDownList(\app\models\SiteDues::buildStatus(), ['prompt'=>Yii::t('app', 'Select ...'),'class'=>'form-control from-head']) ?>
    
           </div>
          </div>

          <div class="row">
               <div class="col-md-12"> 
               <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
               <?= Html::a('Reset', ['index'], ['class' => 'btn btn-default']) ?>
              </div>
    
          </div>

    <?php ActiveForm::end(); ?>

</div>
