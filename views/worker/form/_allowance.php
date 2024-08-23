<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use wbraganca\dynamicform\DynamicFormWidget;

/* @var $this yii\web\View */
/* @var $model app\models\Employee */
$allowanceModel = new \app\models\WorkerAllowance;
\yii\web\YiiAsset::register($this);
?>
  <!-- Modal -->
  <div class="modal fade" id="allowance-modal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
       
        <?php $form = ActiveForm::begin(['id'=>'allowance_form',
              'action' => Yii::$app->urlManager->createUrl(['worker/add-allowance','worker_id'=>$model->id])]);
        ?>

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Allowance <strong class="pull-right"><?= $model->name?></strong></h4>
        </div>
        <div class="modal-body">
            <div class="row">
                
                <?php foreach($allowances as $allowance):?>
                     <div class="col-sm-12">
                        <?= $form->field($allowanceModel,"[$allowance[allowance_id]][WorkerAllowance]value")->textInput(['value'=>$allowance['value']])->label($allowance['allowance']);?>
                        <?= $form->field($allowanceModel,"[$allowance[allowance_id]][WorkerAllowance]worker_id")->hiddenInput(['value'=>$allowance['worker_id']])->label(false);?>
                        <?= $form->field($allowanceModel,"[$allowance[allowance_id]][WorkerAllowance]allowance_id")->hiddenInput(['value'=>$allowance['allowance_id']])->label(false);?>
                        <?php if(!empty($allowance['id'])):?>
                        <?= $form->field($allowanceModel,"[$allowance[allowance_id]][WorkerAllowance]id")->hiddenInput(['value'=>$allowance['id']])->label(false);?>
                        <?php endif;?>
                    </div>
                <?php endforeach;?>
               <div class="modal-footer">
                 <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
                 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
               </div>
            </div>
            
        </div>

        <?php ActiveForm::end(); ?>

      </div>
      
    </div>
  </div>
  
	<?php 
	   
	   $this->registerJs("
	   
	     $('.dynamicform_wrapper').on('afterInsert', function (e, item) {
         });
	   
	   ");
	   
	?>