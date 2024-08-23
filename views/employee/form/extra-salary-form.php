<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Employee */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('app', 'Employee Extra Salary');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Employee'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Extra Salary'), 'url' => ['extra-salary']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="salary-form">
   <div class="salary-form box box-primary"> 
		
		<div class="box-header with-border"> 

    <?php $form = ActiveForm::begin(); ?>
    
    <div class="row">
        <?php
        $session = $model->session;
        if(!$session){
            $session = \app\models\Session::getCurrentSession();
        }
        ?>
	    <div class="col-md-2">
	
           <?= $form->field($model, 'session')->widget(Select2::classname(), [
               'data' => \yii\helpers\ArrayHelper::map(\app\models\Session::find()->orderBy('id')->asArray()->all(), 'session', 'session'),
               'options' => ['placeholder' => 'Select ...','value'=>$session],
               'pluginOptions' => [
                       'allowClear' => true
               ],
           ]); ?>

	    </div>
    
	    <div class="col-md-3">
	
           <?= $form->field($model, 'company_id')->label("Company")->widget(Select2::classname(), [
               'data' => \yii\helpers\ArrayHelper::map(\app\models\Company::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
               'options' => ['placeholder' => 'Select ...'],
               'pluginOptions' => [
                       'allowClear' => true
               ],
           ]); ?>

	    </div>

	    <div class="col-md-3">
	
             <?=  $form->field($model, 'month')->widget(DatePicker::classname(), [
                  'options' => ['placeholder' => 'Enter birth date ...','autocomplete'=>"off"],
                  'pluginOptions' => [
                  'autoclose'=>true,
		                'format'=>'mm-yyyy',
		                'minViewMode'=>'months',
                  ]
             ]); ?>

	    </div>
    
        <div class="col-md-3">
          <?= $form->field($model, 'employee_id')->label("Employee")->widget(Select2::classname(), [
              'data' => \yii\helpers\ArrayHelper::map(\app\models\Employee::find()->select(['id','CONCAT(emp_name," ",emp_code) as name'])->orderBy('id')->asArray()->all(), 'id', 'name'),
              'options' => ['placeholder' => 'Select ...'],
              'pluginOptions' => [
                   'allowClear' => true
               ],
           ]); ?>
        </div>

    </div>

    <div class="row basic-div">
    	
        <div class="col-md-2">
          <?= $form->field($model, 'days')->textInput(['class'=>'form-control editSalary']); ?>
        </div>

        <div class="col-md-2">
          <?= $form->field($model, 'salary')->textInput(['class'=>'form-control editSalary']); ?>
        </div>

    </div>
    
    <div id="allowance-main-div">
        <?php if($model->session):
            echo $this->render('_extra_salary_allowance',['employeeAllowance'=>$employeeAllowance]);
        endif;?> 
    </div>	
    
	<div class="row">
	    
	   <div class="col-md-12">
	
          <div class="form-group">
              <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
          </div>

	   </div>

    </div>
    
    <?php ActiveForm::end(); ?>
  
</div>
</div>
</div>

<?php 
$isUpdate = $model->session?true:false;
$salaryUrl = Url::to(['get-extra-salary']);
$script = <<< JS
  if('$isUpdate'){
    setTimeout(function(){ 
              $(".editSalary").trigger("keyup");
    }, 1200);
  }
  $("#employeeextrasalary-employee_id,#employeeextrasalary-month").change(function(){
      var employee = $("#employeeextrasalary-employee_id").val();
      var month = $("#employeeextrasalary-month").val();
      if(month == ""){
          $("#error-modal .message").html("Please select month");
          $("#error-modal").modal("show");
          return false;
      }
      if(employee != ""){
         getExtraSalary(employee,month);
      }
  });

  function getExtraSalary(employee,month){

      $.ajax({
         url:'$salaryUrl',
         data:{employee_id:employee,month:month},
         success:function(res){
            $("#employeeextrasalary-days").val(res.data.days);
            $("#employeeextrasalary-salary").val(res.data.salary);
            $("#allowance-main-div").html(res.data.allowance);
            setTimeout(function(){ 
              $(".editSalary").trigger("keyup");
            }, 1200);
         }
      });

  }

  function daysInMonth(date){
     var split = date.split("-"); 
     return new Date(split[2], split[1], 0).getDate();
  }


  $(document).on("keyup",".editSalary",function(){
    var salary = Number($("#employeeextrasalary-salary").val());
    var total_allowance = 0;
    $(".allowance-amount").each(function(index){
         total_allowance += Number($(this).val())
    });
    $("input.allowance").val( (total_allowance).toFixed(2) );
    $(".salary_with_allowance").val( (salary+total_allowance).toFixed(2) );
  });

JS;
$this->registerJs($script);