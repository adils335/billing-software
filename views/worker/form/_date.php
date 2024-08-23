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
  <div class="modal fade" id="date-modal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
       
        <?php $form = ActiveForm::begin([
             'options' => ['method'=>'post'],
             'id'=>'date-form',
              'action' => Yii::$app->urlManager->createUrl(['//common/erpmeta'])]);
        ?>

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Joining and End Date <strong class="pull-right"><?= $model->name?></strong></h4>
        </div>
        <div class="modal-body">
            <div class="row">

                <?= $form->field($erpmeta, 'type')->hiddenInput(['value' => $erpmeta::TYPE_WORKER])->label(false); ?>
                <?= $form->field($erpmeta, 'type_id')->hiddenInput(['value' => $model->id])->label(false); ?>

                <div class="col-md-12">
                     <?= $form->field($erpmeta, 'meta_key')->label("Date Type")->radioList($erpmeta::joining_end_date()); ?>
                </div>
                <div class="col-md-12">
                <?=  $form->field($erpmeta, 'meta_value')->widget(DatePicker::classname(), [
                  'options' => ['placeholder' => 'Enter date ...'],
                  'pluginOptions' => [
                               'autoclose'=>true,
		                             'format'=>'dd-mm-yyyy'
                          ]
                    ])->label('Date'); ?>
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