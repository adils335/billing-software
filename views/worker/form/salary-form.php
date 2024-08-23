<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Worker */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('app', 'Worker Salary');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Worker'), 'url' => ['index']];
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

	    <div class="col-md-2">
	
             <?=  $form->field($model, 'month')->widget(DatePicker::classname(), [
                  'options' => ['placeholder' => 'Enter date ...','autocomplete'=>"off"],
                  'pluginOptions' => [
                  'autoclose'=>true,
		                'format'=>'mm-yyyy',
		                'minViewMode'=>'months',
                  ]
             ]); ?>

	    </div>
    
        <div class="col-md-3">
          <?= $form->field($model, 'worker_vendor_id')->widget(Select2::classname(), [
              'data' => \yii\helpers\ArrayHelper::map(\app\models\WorkerVendor::find()->select(['id','CONCAT(name," ",code) as name'])->orderBy('id')->asArray()->all(), 'id', 'name'),
              'options' => ['placeholder' => 'Select ...'],
              'pluginOptions' => [
                   'allowClear' => true
               ],
           ]); ?>
        </div>
        
	    <div class="col-md-2">
	
             <?= $form->field($model, 'worker_id')->widget(Select2::classname(), [
              'data' => \yii\helpers\ArrayHelper::map(\app\models\Worker::find()->select(['id','CONCAT(name," ",code) as name'])->where(['worker_vendor_id'=>$model->worker_vendor_id])->orderBy('id')->asArray()->all(), 'id', 'name'),
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

        <div class="col-md-1">
          <?= $form->field($model, 'leave')->textInput(['class'=>'form-control editSalary']); ?>
        </div>

        <div class="col-md-2">
          <?= $form->field($model, 'working_days')->textInput(['class'=>'form-control editSalary']); ?>
        </div>

        <div class="col-md-2">
          <?= $form->field($model, 'extra_work_days')->textInput(['class'=>'form-control editSalary']); ?>
        </div>

        <div class="col-md-2">
          <?= $form->field($model, 'extra_salary')->textInput(['class'=>'form-control editSalary']); ?>
        </div>
        
        <div class="col-md-2">
          <?= $form->field($model, 'salary')->textInput(['class'=>'form-control editSalary']); ?>
        </div>

    </div>

    <?php if($model->session):
        echo $this->render('_additional_salary',['workerAllowance'=>$workerAllowance,'workerDeduction'=>$workerDeduction,'employerDeduction'=>$employerDeduction]);
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
 $select2Options = json_encode([
    'data'=>'',         
    'multiple' => false,
    'theme' => 'krajee',
    'placeholder' => 'Select',
    'language' => 'en-US',
    'width' => '100%',
     ]);
$isUpdate = $model->session?true:false;
$url = Url::to(['additional-salary']);
$salaryUrl = Url::to(['get-base-salary']);
$toVendorUrl = Url::to(['ledger/ajax-account-by-vendor']);

$script = <<< JS
  if('$isUpdate'){
     salary();
  }
  $(document).on("change","#workersalary-worker_vendor_id",function(){
           
        var vendor = $(this).val();
        var worker = $("#workersalary-worker_id");
        $.ajax({
          url:'$toVendorUrl',
          data:{vendor:vendor},
          success:function(data){
              select2Options = $select2Options;
              worker.find("option").remove();
           select2Options.data = data.data;
           worker.select2(select2Options);
          }
        });
        
  });
  $("#workersalary-worker_id,#workersalary-month").change(function(){
      var worker = $("#workersalary-worker_id").val();
      var month = $("#workersalary-month").val();
      if(month == ""){
          $("#error-modal .message").html("Please select month");
          $("#error-modal").modal("show");
          return false;
      }
      if(worker != ""){
         additionalSalary(worker);
         getSalary(worker,month);
      }
  });
  
  function additionalSalary(worker){

      $.ajax({
         url:'$url',
         data:{worker_id:worker},
         success:function(res){
            $(".as").remove();
            $(".basic-div").after(res);
         }
      });

  }

  function getSalary(worker,month){

      $.ajax({
         url:'$salaryUrl',
         data:{worker_id:worker,month:month},
         success:function(res){
            $("#workersalary-base_salary").val(res.base_salary);
            $("#workersalary-per_day_salary").val(res.per_day_salary);
            $("#workersalary-working_days").val(res.working_days);
            $("#workersalary-leave").val(res.leave);
            $("#workersalary-salary").val(res.salary);
            $("#workersalary-extra_salary").val(res.extra_salary);
            $("#workersalary-extra_work_days").val(res.extra_work_days);
            setTimeout(function(){ 
              salary();
            }, 1000);
         }
      });

  }

  function daysInMonth(date){
     var split = date.split("-"); 
     return new Date(split[2], split[1], 0).getDate();
  }

  function salary(){
     var month = $("#workersalary-month").val();
     var working_days = $("#workersalary-working_days").val();
     var monthDays = daysInMonth("01-"+month);
  	 var base_salary = Number($("#workersalary-base_salary").val());
  	 var salary = Number($("#workersalary-salary").val());
  	 var total_allowance = 0;
  	 $(".allowance-actual-amount").each(function(index){
  	     var per_day = ($(this).val()/monthDays).toFixed(2);
  	     $(".allowance-per-day").eq(index).val(per_day);
  	     $(".allowance-amount").eq(index).val((per_day*working_days).toFixed(0));
         total_allowance += Number((working_days*per_day).toFixed(2));
  	 });
     $(".allowance").val((total_allowance).toFixed(0));
     $(".salary_with_allowance").val((total_allowance + salary).toFixed(0));
     var worker_deduction = 0, total_worker_deduction = 0;
     $(".worker-deduction-rate").each(function(index){
          worker_deduction = base_salary*Number($(this).val())/100;
          var per_day = (worker_deduction/monthDays).toFixed(2);
  	      $(".worker-deduction-per-day").eq(index).val(per_day);
          total_worker_deduction += working_days*per_day;
          $(".worker-deduction-amount").eq(index).val((working_days*per_day).toFixed(0));
     });
     $(".worker-deduction").val((total_worker_deduction).toFixed(0));
     $(".payable-salary").val((total_allowance + salary-total_worker_deduction).toFixed(0));
     var employer_deduction = 0, total_employer_deduction = 0;
     $(".employer-deduction-rate").each(function(index){
          employer_deduction = base_salary*Number($(this).val())/100;
          var per_day = (employer_deduction/monthDays).toFixed(2);;
  	      $(".employer-deduction-per-day").eq(index).val(per_day);
          total_employer_deduction += working_days*per_day;
          $(".employer-deduction-amount").eq(index).val((working_days*per_day).toFixed(0));
     });
     $(".employer-deduction").val((total_employer_deduction).toFixed(0));
     $(".net-salary").val((total_allowance + salary + total_employer_deduction).toFixed(0));
     return 1;
  }

  function editSalary(){
    var month = $("#workersalary-month").val();
    var base_salary = Number($("#workersalary-base_salary").val());
    var monthDays = daysInMonth("01-"+month);
    var per_day_salary = (base_salary/monthDays).toFixed(2);
    $("#workersalary-per_day_salary").val(per_day_salary);
    var leave = Number($("#workersalary-leave").val());
    $("#workersalary-working_days").val(monthDays-leave);
    var extra_work_days = Number($("#workersalary-extra_work_days").val());
    $("#workersalary-extra_salary").val((extra_work_days*per_day_salary).toFixed(0));
    var current_salary = leave>0?base_salary-leave*per_day_salary:base_salary;
    current_salary = (current_salary + extra_work_days*per_day_salary).toFixed(0);
    $("#workersalary-salary").val(current_salary);
    salary();
  }

  $(".editSalary").keyup(function(){
     editSalary();
  });

JS;
$this->registerJs($script);