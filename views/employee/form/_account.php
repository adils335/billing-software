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
  <div class="modal fade" id="account-modal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
       
        <?php $form = ActiveForm::begin([
             'options' => ['enctype' => 'multipart/form-data','method'=>'post'],
              'action' => Yii::$app->urlManager->createUrl(['account/create'])]);
        ?>

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Account <strong class="pull-right"><?= $model->emp_name?></strong></h4>
        </div>
        <div class="modal-body">
            <div class="row">

                <?= $form->field($account, 'employee_id')->hiddenInput(['value' => $model->id])->label(false); ?>

                <div class="col-md-12">
  
                     <?= $form->field($account, 'name')->textInput(['maxlength' => true]); ?>

                </div>
               
                <div class="col-md-12">
  
                     <?= $form->field($account, 'bank_name')->textInput(['maxlength' => true]); ?>

                </div>
               
                <div class="col-md-12">
  
                     <?= $form->field($account, 'branch_name')->textInput(['maxlength' => true]); ?>

                </div>
               
                <div class="col-md-12">
  
                     <?= $form->field($account, 'account_no')->textInput(['maxlength' => true]); ?>

                </div>
               
                <div class="col-md-12">
  
                     <?= $form->field($account, 'ifsc_code')->textInput(['maxlength' => true]); ?>

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