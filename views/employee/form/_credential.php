<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Employee */

\yii\web\YiiAsset::register($this);
?>
  <!-- Modal -->
  <div class="modal fade" id="credential-modal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
       
        <?php $form = ActiveForm::begin([
             'options' => ['enctype' => 'multipart/form-data','method'=>'post','autocomplete'=>'off'],
             'action' => Yii::$app->urlManager->createUrl(['employee/credential'])]);
        ?>

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Credential <strong class="pull-right"><?= $model->emp_name?></strong></h4>
        </div>
        <div class="modal-body">
            <div class="row">

                     <?= $form->field($credential, 'id')->hiddenInput()->label(false); ?>
                     <?= $form->field($model, 'id')->hiddenInput()->label(false); ?>

                <div class="col-md-12">
  
                      <?= $form->field($credential, 'role')->label("Role")->widget(Select2::classname(), [
                          'data' => \yii\helpers\ArrayHelper::map(\app\models\Roles::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                          'options' => ['placeholder' => 'Select a Role ...'],
                          'pluginOptions' => [
                              'allowClear' => true
                           ],
                       ]); ?>

                </div>
                
                <div class="col-md-12">
                      <?php $credential->access_company = !empty($credential->access_company)?json_decode($credential->access_company,true):[];?>
                      <?= $form->field($credential, 'access_company')->widget(Select2::classname(), [
                          'data' => \yii\helpers\ArrayHelper::map(\app\models\Company::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                          'options' => ['placeholder' => 'Select a Company ...'],
                          'pluginOptions' => [
                              'allowClear' => true,
                              'multiple'=>true,
                           ],
                       ]); ?>

                </div>

                <div class="col-md-12">
  
                     <?= $form->field($credential, 'username')->textInput(['maxlength' => true,'autocomplete'=>'nope']); ?>

                </div>
               
                <div class="col-md-12">
  
                     <?= $form->field($credential, 'password_hash')->label("Password")->passwordInput(['maxlength' => true,'value'=>'','autocomplete'=>'new-password']); ?>

                </div>

            </div>
            
        </div>
        <div class="modal-footer">
          <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>

        <?php ActiveForm::end(); ?>

      </div>
      
    </div>
  </div>