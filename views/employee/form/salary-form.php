<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Employee */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('app', 'Employee Salary');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Employee'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Salary'), 'url' => ['salary-record']];
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
          <?= $form->field($model, 'base_salary')->textInput(['class'=>'form-control editSalary']); ?>
        </div>

        <div class="col-md-1">
          <?= $form->field($model, 'per_day_salary')->textInput()->label("Per Day"); ?>
        </div>

        <div class="col-md-2">
          <?= $form->field($model, 'actual_working_days')->textInput(['class'=>'form-control editSalary']); ?>
        </div>
        
        <div class="col-md-2">
          <?= $form->field($model, 'working_days')->textInput(['class'=>'form-control editSalary']); ?>
        </div>

        <div class="col-md-1">
          <?= $form->field($model, 'holidays')->textInput(['class'=>'form-control editSalary']); ?>
        </div>
        
        <div class="col-md-1">
          <?= $form->field($model, 'leave')->textInput(['class'=>'form-control editSalary']); ?>
          <?= $form->field($model, 'balanced_leave')->hiddenInput(['class'=>'form-control'])->label(false); ?>
        </div>

        <div class="col-md-2">
          <?= $form->field($model, 'extra_work_days')->textInput(['class'=>'form-control editSalary']); ?>
        </div>

        <div class="col-md-2">
          <?= $form->field($model, 'extra_salary')->textInput(['class'=>'form-control']); ?>
        </div>
        
        <div class="col-md-2">
          <?= $form->field($model, 'salary')->textInput(['class'=>'form-control editSalary']); ?>
        </div>

    </div>

    <?php if($model->session):
        echo $this->render('_additional_salary',['employeeAllowance'=>$employeeAllowance,'employeeDeduction'=>$employeeDeduction,'employerDeduction'=>$employerDeduction]);
    endif;?> 	
    
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
$url = Url::to(['additional-salary']);
$salaryUrl = Url::to(['get-base-salary']);
$script = <<< JS
  if('$isUpdate'){
     salary();
  }
  $("#employeesalary-employee_id,#employeesalary-month").change(function(){
      var employee = $("#employeesalary-employee_id").val();
      var month = $("#employeesalary-month").val();
      if(month == ""){
          $("#error-modal .message").html("Please select month");
          $("#error-modal").modal("show");
          return false;
      }
      if(employee != ""){
         additionalSalary(employee);
         getSalary(employee,month);
      }
  });
  
  function additionalSalary(employee){

      $.ajax({
         url:'$url',
         data:{employee_id:employee},
         success:function(res){
            $(".as").remove();
            $(".basic-div").after(res);
         }
      });

  }

  function getSalary(employee,month){

      $.ajax({
         url:'$salaryUrl',
         data:{employee_id:employee,month:month},
         success:function(res){
            $("#employeesalary-base_salary").val(res.base_salary);
            $("#employeesalary-per_day_salary").val(res.per_day_salary);
            $("#employeesalary-actual_working_days").val(res.actual_working_days);
            $("#employeesalary-working_days").val(res.working_days);
            $("#employeesalary-holidays").val(res.holidays);
            $("#employeesalary-leave").val(res.leave);
            $("#employeesalary-balanced_leave").val(res.balanced_leave);
            $("#employeesalary-salary").val(res.salary);
            $("#employeesalary-extra_work_days").val(res.extra_work_days);
            setTimeout(function(){ 
              salary();
            }, 1200);
         }
      });

  }

  function daysInMonth(date){
     var split = date.split("-"); 
     return new Date(split[2], split[1], 0).getDate();
  }

  function salary(){
     var month = $("#employeesalary-month").val();
     var per_day_salary = Number($("#employeesalary-per_day_salary").val());
     var actual_working_days = Number($("#employeesalary-actual_working_days").val());
     var working_days = Number($("#employeesalary-working_days").val());
     var holidays = Number($("#employeesalary-holidays").val());
     var leave = Number($("#employeesalary-leave").val());
     var extra_work_days = Number($("#employeesalary-extra_work_days").val());
     var monthDays = daysInMonth("01-"+month);
  	 var base_salary = Number($("#employeesalary-base_salary").val());
  	 var salary = Number($("#employeesalary-salary").val());
  	 var total_allowance = 0;
  	 $(".allowance-actual-amount").each(function(index){
  	     var per_day = ($(this).val()/actual_working_days).toFixed(3);
  	     $(".allowance-per-day").eq(index).val(per_day);
  	     $(".allowance-amount").eq(index).val((per_day*(working_days+holidays-leave)).toFixed(2));
         total_allowance += Number(((working_days+holidays-leave)*per_day).toFixed(2));
  	 });
     $(".allowance").val((total_allowance).toFixed(2));
     $(".salary_with_allowance").val((total_allowance + salary).toFixed(2));
     var extra_salary = parseInt(Math.round(extra_work_days*per_day_salary + total_allowance*extra_work_days/actual_working_days));
     $("#employeesalary-extra_salary").val(Number(extra_salary));
     var employee_deduction = 0, total_employee_deduction = 0;
     $(".employee-deduction-rate").each(function(index){
          employee_deduction = salary*Number($(this).val())/100;
          total_employee_deduction += employee_deduction;
          $(".employee-deduction-amount").eq(index).val((employee_deduction).toFixed(2));
     });
     $(".employee-deduction").val((total_employee_deduction).toFixed(2));
     $(".payable-salary").val(Math.round(total_allowance + salary-total_employee_deduction));
     var employer_deduction = 0, total_employer_deduction = 0;
     $(".employer-deduction-rate").each(function(index){
          employer_deduction = salary*Number($(this).val())/100;
          total_employer_deduction += employer_deduction;
          $(".employer-deduction-amount").eq(index).val((employer_deduction).toFixed(2));
     });
     $(".employer-deduction").val((total_employer_deduction).toFixed(2));
     $(".net-salary").val(Math.round(total_allowance + salary + total_employer_deduction));
     return 1;
  }

  function editSalary(){
    var month = $("#employeesalary-month").val();
    var base_salary = Number($("#employeesalary-base_salary").val());
    var actual_working_days = Number($("#employeesalary-actual_working_days").val());
    var working_days = Number($("#employeesalary-working_days").val());
    var holidays = Number($("#employeesalary-holidays").val());
    var leave = Number($("#employeesalary-leave").val());
    var total_allowance = Number($(".allowance").val());
    var monthDays = daysInMonth("01-"+month);
    var per_day_salary = (base_salary/actual_working_days).toFixed(2);
    $("#employeesalary-per_day_salary").val(per_day_salary);
    var extra_work_days = Number($("#employeesalary-extra_work_days").val());
    var extra_salary = parseInt(Math.round(extra_work_days*per_day_salary + total_allowance*extra_work_days/actual_working_days));
    $("#employeesalary-extra_salary").val(extra_salary);
    var current_salary = (working_days+holidays-leave)*per_day_salary;
    current_salary = (current_salary).toFixed(0);
    $("#employeesalary-salary").val(current_salary);
    salary();
  }

  $(".editSalary").keyup(function(){
     editSalary();
  });

JS;
$this->registerJs($script);