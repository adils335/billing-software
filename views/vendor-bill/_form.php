<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2; 
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\VendorBill */
/* @var $form yii\widgets\ActiveForm */
$formatter = Yii::$app->formatter;
$lastBills = $model->lastBills;
if(!$model->isNewRecord){
	$lastBillObject = json_decode($lastBills);
	$company_id = $model->company_id;
	$lastBill = $lastBillObject->$company_id;
}
?>

<div class="vendor-bill-form">
    <div class="last-bill-summary col-sm-12 <?= !$model->isNewRecord?'':'hide';?>">
       <label class="col-sm-3">Document No:<span id="last-document-no"><?= $model->isNewRecord?'':$lastBill->bill_no;?></span></label>
       <label class="col-sm-3">Document Date:<span id="last-document-date"><?= $model->isNewRecord?'':$lastBill->bill_date;?></span></label>
	</div>
    <?php $form = ActiveForm::begin(['id'=>'vendor-bill']); ?>
    
    <?= $form->field($model, 'bill_no')->hiddenInput()->label(false); ?>
	
	<div class="row">
	    <div class="col-sm-4">
                     <?= $form->field($model, "company_id")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\Company::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                               'options' => ['placeholder' => 'Select ...'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
	     </div>
		 <div class="col-sm-4">
                     <?php $model->session = $model->session?$model->session:(new \app\models\Session)->currentSession;?> 
                     <?= $form->field($model, "session")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\Session::find()->orderBy(['session'=>SORT_DESC])->asArray()->all(), 'session', 'session'),
                               'options' => ['placeholder' => 'Select ...'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
	     </div>
	     <div class="col-md-4">
  
		   <?php if($model->bill_date){$model->bill_date = $formatter->asDate($model->bill_date,'php:d-m-Y');}?>
           <?=  $form->field($model, "bill_date")->widget(DatePicker::classname(), [
                                   'options' => ['placeholder' => 'Enter date ...'],
                                   'pluginOptions' => [
                                          'autoclose'=>true,
		                                  'format'=>'dd-mm-yyyy',
										  'startDate' => $model->isNewRecord?"01-01-2018":$lastBill->bill_date,
                                       ]
                                    ]); ?>

	   </div>	
	</div>

	<div class="row">
	   <div class="col-md-4">
	   
          <?= $form->field($model, "vendor_id")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\Vendor::find()->select(['id','CONCAT(name," ",code) as name'])->orderBy('id')->asArray()->all(), 'id', 'name'),
                               'options' => ['placeholder' => 'Select ...','class'=>'vendor-id'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
		  
	   </div>	  
	   <div class="col-md-4">
	   
          <?= $form->field($model, 'invoice_no')->textInput() ?>
		  
	   </div>	  
	   <div class="col-md-4">
	       
		   <?php if($model->invoice_date){$model->invoice_date = $formatter->asDate($model->invoice_date,'php:d-m-Y');}?>
  
           <?=  $form->field($model, "invoice_date")->widget(DatePicker::classname(), [
                                   'options' => ['placeholder' => 'Enter date ...'],
                                   'pluginOptions' => [
                                          'autoclose'=>true,
		                                  'format'=>'dd-mm-yyyy'
                                       ]
                                    ]); ?>

	   </div>	
	 </div>	


	<?= $this->render('form/_items',['model'=>$model,'form'=>$form,'billItem'=>$billItem]);?>
	
	<?= $this->render('form/_schedule',['model'=>$model,'form'=>$form]);?>


	<?= $this->render('form/_tax',['model'=>$model,'form'=>$form,'billTax'=>$billTax]);?>
    
	<div class="row">
	    <div class="col-sm-8"></div>
	    <div class="col-sm-2">
          <label>Payable Amount</label>
        </div>
	    <div class="col-sm-2">
          <?= $form->field($model, 'payable_amount')->textInput(['maxlength' => true])->label(false); ?>
        </div>
	</div>
	
	<?= $this->render('form/_deduction',['model'=>$model,'form'=>$form,'billDeduction'=>$billDeduction]);?>
	
	<div class="row">
	    <div class="col-sm-8"></div>
	    <div class="col-sm-2">
          <label>Pay For</label>
        </div>
	    <div class="col-sm-2">
          <?= $form->field($model, 'pay_amount')->textInput(['maxlength' => true])->label(false); ?>
        </div>
	</div>
	
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
    
	function billAmount(){

		var itemAmount = 0;
		var itemTotalAmount = 0;
		$('.item-quantity').each(function(index,element){
			itemAmount = Number($('.item-quantity').eq(index).val()) * Number($('.item-rate').eq(index).val());
			itemTotalAmount += itemAmount;
			$('.item-amount').eq(index).val(itemAmount);
		});
		
		$("#vendorbill-base_amount").val((itemTotalAmount).toFixed(2));
		
		var scheduleAmount = Number($("#vendorbill-schedule_amount").val());
		
		/*
		var scheduleAmount = Number($("#vendorbill-schedule_rate").val())*itemTotalAmount/100;

		if(Number($("#vendorbill-schedule_rate").val()) == 0){
           scheduleAmount = Number($("#vendorbill-schedule_amount").val());
		}
        */
        
		var taxableAmount = itemTotalAmount;
		
		var schedule = $("#vendorbill-schedule").val();
		if(schedule == 1)
			taxableAmount += scheduleAmount;
		else if(schedule == 2)
			taxableAmount -= scheduleAmount;
		
		$("#vendorbill-schedule_amount").val((scheduleAmount).toFixed(2));
		$("#vendorbill-taxable_amount").val((taxableAmount).toFixed(2));
		
		var taxAmount = 0;
		var taxTotalAmount = 0;
		$('.tax-rate').each(function(index,element){
			taxAmount = Number($('.tax-rate').eq(index).val()) * taxableAmount/100;
			taxTotalAmount += taxAmount;
			$('.tax-amount').eq(index).val((taxAmount).toFixed(2));
		});
		
		var payableAmount = taxableAmount + taxTotalAmount;
		$("#vendorbill-tax_amount").val((taxTotalAmount).toFixed(2));
		$("#vendorbill-payable_amount").val((payableAmount).toFixed(2));	
		
		
		//var deductionAmount = 0;
		//var deductionTotalAmount = 0;
		//$('.deduction-rate').each(function(index,element){
		//	deductionAmount = Number($('.deduction-rate').eq(index).val()) * taxableAmount/100;
		//	deductionTotalAmount += deductionAmount;
		//	$('.deduction-amount').eq(index).val((deductionAmount).toFixed(2));
		//});
		
		var deductionAmount = 0;
		var deductionTotalAmount = 0;
		$('.is_rate:checked').each(function(index,element){
		    index = $(".is_rate").index(this);
			deductionAmount = Number($('.deduction-rate').eq(index).val()) * taxableAmount/100;
			deductionTotalAmount += deductionAmount;
			$('.deduction-amount').eq(index).val(deductionAmount.toFixed(2));
		});

		$('.is_rate:not(:checked)').each(function(index,element){
		    index = $(".is_rate").index(this);
			deductionTotalAmount += Number($('.deduction-amount').eq(index).val());
		});
		
		var payAmount = payableAmount - deductionTotalAmount;
		$("#vendorbill-deduction_amount").val((deductionTotalAmount).toFixed(2));
		$("#vendorbill-pay_amount").val((payAmount).toFixed(2));
		
	}
	

</script>

<?php 
$formatJs = <<< JS
   var lastbills =  $lastBills ;
   $("#vendorbill-company_id").change(function(){
	  $(".last-bill-summary").removeClass("hide");
	  company_id = $(this).val();
	  lastbill = lastbills[company_id];console.log(lastbill);
	  if( !jQuery.isEmptyObject(lastbill) ){
		  $("#last-document-no").text(lastbill['bill_no'] );
		  $("#last-document-date").text(lastbill['bill_date'] );
		  var startDate = new Date(lastbill['bill_date'].replace( /(\d{2})-(\d{2})-(\d{4})/, "$2/$1/$3"));
		  $("#vendorbill-bill_date-kvdate").data("datepicker").setStartDate(startDate);
		  $("#vendorbill-bill_date-kvdate").kvDatepicker('clearDates').trigger('change');
	  }else{
		  $(".last-bill-summary").addClass("hide");
		  var startDate = new Date("01-01-2018".replace( /(\d{2})-(\d{2})-(\d{4})/, "$2/$1/$3"));
		  $("#vendorbill-bill_date-kvdate").data("datepicker").setStartDate(startDate);
	  }
   });
JS;
$this->registerJs($formatJs);
