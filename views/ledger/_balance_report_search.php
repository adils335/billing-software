<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use app\models\Ledger;

/* @var $this yii\web\View */
/* @var $model app\models\Search\Employee */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employee-search">

  <div class="row">
    <?php $form = ActiveForm::begin([
        'action' => ['balance-report'],
        'id'=>'form_balance_report_earch',
        'method' => 'get',
    ]); ?>
    <?= $form->field($model,'type')->hiddenInput()->label(false);?>
    <?= $form->field($model,'account_type')->hiddenInput()->label(false);?>
    <div class="col-md-3">
      <?= $form->field($model, 'company_id')->widget(Select2::classname(), [
    'data' => \yii\helpers\ArrayHelper::map(\app\models\Company::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
    'options' => ['placeholder' => 'Select ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],
    ])->label("Company"); ?>
    </div>
    <div class="col-md-2">
          <?= $form->field($model, 'status')->dropDownList(\app\models\Employee::buildStatus()) ?>
    </div>
    
         <?php $class=""; 
          if($model->type != Ledger::TYPE_EMPLOYEE){
             $class="hide"; 
          }
         ?>
         
         <div class="col-md-2 account-type <?= $class?>">
               <?= $form->field($model, 'account_type')->dropDownList(\app\models\Employee::buildAccountType()) ?>
         </div>
         
         <?php $class=""; 
          if($model->type != Ledger::TYPE_WORKER){
             $class="hide"; 
          }
         ?>
         <div class="col-sm-3 <?= $class?>">
   
            <?= $form->field($model, 'vendor')->label("Vendor")->widget(Select2::classname(), [
                   'data' => \yii\helpers\ArrayHelper::map(\app\models\WorkerVendor::find()->select(['id','CONCAT(code," ",name) as name'])->orderBy('id')->asArray()->all(), 'id', 'name'),
                   'options' => ['placeholder' => 'Select ...'],
                   'pluginOptions' => [
                         'allowClear' => true
                    ],
              ]); ?>

    </div>
    <div class="col-md-2">
     
             <?=  $form->field($model, 'fromDate')->widget(DatePicker::classname(), [
                     'options' => ['placeholder' => 'Enter date ...'],
                     'pluginOptions' => [
                     'autoclose'=>true,
                        'format'=>'dd-mm-yyyy'
                     ]
             ]); ?>
   
     </div>
     
     <div class="col-md-2">
     
             <?=  $form->field($model, 'toDate')->widget(DatePicker::classname(), [
                     'options' => ['placeholder' => 'Enter date ...'],
                     'pluginOptions' => [
                     'autoclose'=>true,
                        'format'=>'dd-mm-yyyy'
                     ]
             ]); ?>
   
     </div>
     
	
    <div class="col-md-12">
        <input type="hidden" name="ispdf" value="0" id="ispdf" >
    <div class="form-group">
        <?= Html::button(Yii::t('app', 'Search'), ['class' => 'btn btn-primary','id'=>'search']) ?>
        <?= Html::button(Yii::t('app', 'Print'), ['class' => 'btn btn-primary','id'=>'print']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>
</div>
    <?php ActiveForm::end(); ?>

</div>

</div>

<?php

$script = <<< JS
  
  $("#search").click(function(){
      $("#ispdf").val(0);
      $("#form_balance_report_earch").submit();
  });
  
  $("#print").click(function(){
      $("#ispdf").val(1);
      $("#form_balance_report_earch").submit();
  });
  
JS;
$this->registerJs($script);
?>
