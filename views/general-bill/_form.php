<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap5\ActiveForm;
use kartik\select2\Select2; 
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\date\DatePicker;
use app\models\Session;
use app\models\Common;
use kartik\editors\Summernote;

/* @var $this yii\web\View */
/* @var $model app\models\AgreementBill */
/* @var $form yii\widgets\ActiveForm */
$formatter = Yii::$app->formatter;
if(!empty($model->invoice_date)){
    $model->invoice_date = $formatter->asDate($model->invoice_date,'php:d-m-Y');
}

$startDate = "";
?>

<div class="general-bill-form">
<div class="general-bill box box-primary">
    <div class="box-header">

     <?php $form = ActiveForm::begin([
	        'id' => 'agreement-bill',
	 ]); ?>
     <?php 
        $lastBill = $model->lastBill();
        ?>
            <div class="row last-bill-detail">
          <?php  if(!empty($lastBill)):?>
                <div class="col-sm-12">
                    <b>Last Invoice No:<?= $lastBill->session."/".sprintf('%02d',$lastBill->invoice_no)?></b>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php $startDate = $formatter->asDate($lastBill->invoice_date,'php:d-m-Y');?>
                    <b>Last Invoice Date:<?= $startDate?></b>
                </div>
                
        <?php endif;?> 
            </div>
    <?= $form->field($agreement, 'agreement_id')->hiddenInput(['value'=>$agreement->id])->label(false); ?>
    <?= $form->field($agreement, 'type')->hiddenInput(['value'=>$agreement::TYPE_GENERAL])->label(false); ?>

    <?= $form->field($model, 'invoice_no')->hiddenInput()->label(false); ?>
	
    <div class="row">

	    <div class="col-sm-2">
		    <?= $form->field($agreement, "session")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\Session::find()->orderBy(['session'=>SORT_DESC])->asArray()->all(), 'session', 'session'),
                               'options' => ['placeholder' => 'Select ...'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
        </div>

	    <div class="col-sm-2">
		    <?= $form->field($agreement, "company_id")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\Company::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                               'options' => ['placeholder' => 'Select ...','onchange'=>'getStamp($(this).val())'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
        </div>

	    <div class="col-sm-3">
            <?= $form->field($agreement, "state_id")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\State::find()->orderBy('id')->asArray()->all(), 'id', 'state'),
                               'options' => ['placeholder' => 'Select ...','onchange'=>'getCompanyGst()'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
        </div>
        
	    <div class="col-sm-2">
            <?= $form->field($agreement, "district_id")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\District::find()->where(['state_id'=>$agreement->state_id])->orderBy('id')->asArray()->all(), 'id', 'district'),
                               'options' => ['placeholder' => 'Select ...'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
        </div>
    
		<div class="col-sm-3">
            <?= $form->field($agreement, 'gst_no')->textInput(); ?>
        </div>

	</div>
	
    <div class="row">
		
	    <div class="col-sm-3">
		    <?=  $form->field($model, "invoice_date")->widget(DatePicker::classname(), [
                                   'options' => ['placeholder' => 'Enter date ...'],
                                   'pluginOptions' => [
                                          'autoclose'=>true,
		                                  'format'=>'dd-mm-yyyy',
		                                  //'startDate' => $startDate,
		                                  'orientation' => 'bottom'
                                       ]
                                    ]); ?>
        </div>

	    <div class="col-sm-3">
            <?= $form->field($agreement, 'agreement_no')->textarea(['maxlength' => true])->label("Name"); ?>
        </div>
    
		<div class="col-sm-3">
            <?= $form->field($model, 'work_name')->textarea(['maxlength' => true]) ?>
        </div>
        
	    <div class="col-sm-3">
		    <?= $form->field($model, 'stamp')->radioList(\app\models\Common::signature($agreement->company_id)); ?>
        </div>

	</div>
	
			<div class="row">
			   
				<div class="col-md-3">
					    <?= $form->field($agreement, 'contract_company_id')->label("Bill to Company")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\ContractCompany::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                               'options' => ['placeholder' => 'Select a Company ...'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
				</div>
				
				<div class="col-md-3">
					    <?= $form->field($agreement, 'contract_company_state')->label("Bill to State")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\State::find()->orderBy('id')->asArray()->all(), 'id', 'state'),
                               'options' => ['placeholder' => 'Select a State ...',
                               'onchange' => "getDistrictAndGst('agreement-contract_company_state', 'agreement-contract_company_district','agreement-contract_company_gst', 'agreement-contract_company_id','BillingCompany')"],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
				</div>
				
				<div class="col-md-3">
					    <?= $form->field($agreement, 'contract_company_district')->label("Bill to District")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\District::find()->where(['state_id'=>$agreement->contract_company_state])->orderBy('id')->asArray()->all(), 'id', 'district'),
                               'options' => ['placeholder' => 'Select a District ...'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
				</div>
				
				<div class="col-md-3">
					    <?= $form->field($agreement, "contract_company_gst")->label("Bill to gst"); ?>
				</div>
				
			</div>
	
			<div class="row">
			
				<div class="col-md-3">
					    <?= $form->field($model, 'billing_company_state')->label("Billing Company State")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\Common::shipToCompanyState(), 'id', 'state'),
                               'options' => ['placeholder' => 'Select a State ...',
                                             'onchange' => "getDistrict('agreementbill-billing_company_state', 'agreementbill-billing_company_district')"],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ])->label("Ship to State"); ?>
				</div>
				
				<div class="col-md-3">
					    <?= $form->field($model, 'billing_company_district')->label("Billing Company District")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\District::find()->where(['state_id'=>$model->billing_company_state])->orderBy('id')->asArray()->all(), 'id', 'district'),
                               'options' => ['placeholder' => 'Select a District ...',
                                             'onchange' => "getCompany('agreementbill-billing_company_district', 'agreementbill-billing_company_id')"],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ])->label("Ship to District"); ?>
				</div>
				
				<div class="col-md-3">
					    <?= $form->field($model, 'billing_company_id')->label("Billing Company")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\BillingCompany::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                               'options' => ['placeholder' => 'Select a Company ...',
                                             'onchange' => "getGst('agreementbill-billing_company_id','agreementbill-billing_company_state','agreementbill-billing_company_gst')"],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ])->label("Ship to Company"); ?>
				</div>
				
				<div class="col-md-3">
					    <?= $form->field($model, "billing_company_gst")->label("Ship to Gst");; ?>
				</div>
				
			</div>
			
	<div class="row">
	    <div class="col-sm-12">
	        <?= $form->field($model, 'extra_note')->widget(Summernote::class, [
                     'options' => ['placeholder' => 'Add Note', 'minHeight'=>'5']
            ]); ?>
	    </div>
	</div>
			
	<?= $this->render('form/_items',['model'=>$model,'agreement'=>$agreement,'form'=>$form,'billItem'=>$billItem]);?>
	
	<?//= $this->render('form/_schedule',['model'=>$model,'agreement'=>$agreement,'form'=>$form]);?>
	
	<?= $this->render('form/_tax',['model'=>$model,'agreement'=>$agreement,'form'=>$form,'billTax'=>$billTax]);?>
	
	<div class="row">
	    <div class="col-sm-8"></div>
	    <div class="col-sm-2">
          <label>OTF Advance Paid</label>
        </div>
	    <div class="col-sm-2">
          <?= $form->field($model, 'advance_paid')->textInput(['maxlength' => true,'onkeyup'=>'billAmount()'])->label(false); ?>
        </div>
	</div>
	
	<div class="row">
	    <div class="col-sm-8"></div>
	    <div class="col-sm-2">
          <label>Payable Amount</label>
        </div>
	    <div class="col-sm-2">
          <?= $form->field($model, 'payable_amount')->textInput(['maxlength' => true])->label(false); ?>
        </div>
	</div>
	
	<?= $this->render('form/_deduction',['model'=>$model,'agreement'=>$agreement,'form'=>$form,'billDeduction'=>$billDeduction]);?>
	
	<div class="row">
	    <div class="col-sm-8"></div>
	    <div class="col-sm-2">
          <label>Pay For</label>
        </div>
	    <div class="col-sm-2">
          <?= $form->field($model, 'pay_amount')->textInput(['maxlength' => true])->label(false); ?>
        </div>
	</div>
	
	<div class="row">
	    <div class="col-sm-12">
	        <?= $form->field($model, 'payment_note')->widget(Summernote::class, [
                     'options' => ['placeholder' => 'Add Note', 'minHeight'=>'5']
            ]); ?>
	    </div>
	</div>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

       </div>
    <?php ActiveForm::end(); ?>

</div>
</div>
</div>

<?php
  $this->registerJs("
    $('#agreement-bill').yiiActiveForm('remove', 'billdeduction-tax_id');
    $('#agreement-bill').yiiActiveForm('remove', 'billdeduction-amount');
	sortItem();
    $(document).on('click','.add-item-2',function(){
		$( '.deduction-total' ).before( $( '.clone:first' ).clone() );
	    billAmount();
	});

    $(document).on('click','.remove-item-2',function(){
		if($('.clone').length > 1)
		$(this).closest('.clone').remove();
	    billAmount();
	});
	
    $('.dynamicform_wrapper').on('afterInsert', function (e, item) {
       $('.agreement-id').val($('#agreement-agreement_id').val());
       $(e.target).find('.container-items').find('.item:last').find('input,textarea').val('');
       $(e.target).find('.container-items').find('.item:last').find('.hidden-item').remove();
       $(e.target).find('.container-items').find('.item:last').find('select').val('').trigger('change');
       sortItem();
    });
    
    $('.dynamicform_wrapper_1').on('afterInsert', function (e, item) {
       $('.agreement-id').val($('#agreement-agreement_id').val());
       $(e.target).find('.container-items-1').find('.item-1:last').find('input').val('');
       $(e.target).find('.container-items-1').find('.item-1:last').find('select').val('').trigger('change');
    });
    
    $('.dynamicform_wrapper').on('afterDelete', function (e, item) {
       sortItem();
    });
         
	$('.container-items').sortable({
             items: '.item',
             cursor: 'pointer',
             axis: 'y',
             dropOnEmpty: false,
             start: function (e, ui) {
                 ui.item.addClass('selected');
             },
             stop: function (e, ui) {
                 ui.item.removeClass('selected');
                 $(this).find('.item').each(function (index) {
                     if (index > 0) {
                         $(this).find('.sequence').val(index+1);
                     }
                 });
             }
    });
    
    
    function sortItem(){
       $('.sequence').each(function(index){
          $(this).val(index+1);
       });
    }
	
  ");
?>

<script type="text/javascript">
    
	function billAmount(){

		var itemAmount = 0;
		var itemTotalAmount = 0;
		$('.item-quantity').each(function(index,element){
			itemAmount = Number($('.item-quantity').eq(index).val()) * Number($('.item-rate').eq(index).val());
			itemTotalAmount += itemAmount;
			$('.item-amount').eq(index).val( (itemAmount).toFixed(2) );
		});
		itemTotalAmount = ( itemTotalAmount ).toFixed(2);
		$("#agreementbill-base_amount").val(itemTotalAmount);
		
		var taxableAmount = itemTotalAmount;
		
		$("#agreementbill-taxable_amount").val(taxableAmount);

		var advanceAmount = $("#agreementbill-advance_paid").val();

		var taxAmount = 0;
		var taxTotalAmount = 0;
		$('.tax-rate').each(function(index,element){
			taxAmount = Number($('.tax-rate').eq(index).val()) * taxableAmount/100;
			taxTotalAmount += taxAmount;
			$('.tax-amount').eq(index).val( (taxAmount ).toFixed(2) );
		});
		
		var payableAmount = Number(taxableAmount) + Number(taxTotalAmount) - Number(advanceAmount);
		$("#agreementbill-tax_amount").val( (taxTotalAmount).toFixed(2) );
		$("#agreementbill-payable_amount").val( payableAmount.toFixed(2) );	
		
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
		$("#agreementbill-deduction_amount").val( (deductionTotalAmount).toFixed(2) );
		$("#agreementbill-pay_amount").val( (payAmount).toFixed(2) );
		
	}
	
</script>
<?php 

$districtStateWiseUrl1 = Url::to(['district/district-state-wise']);
$districtStateWiseUrl = Url::to(['common/ship-to-district']);
$companyUrl = Url::to(['common/ship-to-company']);
$companyGstUrl = Url::to(['district/gst']);
$gstUrl1 = Url::to(['district/gst']);
$gstUrl = Url::to(['common/ship-to-gst']);
$stampUrl = Url::to(['common/ajax-stamp']);

?>

<script>
    function getDistrictAndGst(stateId, districtId, gstId, companyId, model){
      
	  var state = $("#"+stateId).val();
	  var district = $("#"+districtId);
	  
	  if(districtId)
	  $.ajax({
		  url:'<?= $districtStateWiseUrl1?>',
		  type:'post',
		  data:{state:state},
		  dataType:'JSON',
		  success:function(res){
			  district.find("option").remove();
			  district.append("<option value=''>Select District</option>");
			  for(var key in res){
                district.append("<option value='"+key+"'>"+res[key]+"</option>");
              }
		  }
	  });
	  
	  getContractGst(model, stateId, gstId, companyId);
	  
  }
  
  
  function getContractGst(model, stateId, gstId, companyId){
      
	  var state = $("#"+stateId).val();
	  var gst = $("#"+gstId);
	  var company = $("#"+companyId).val();
	
	  $.ajax({
		  url:'<?=$gstUrl1?>',
		  type:'post',
		  data:{state:state,model:model,company:company},
		  dataType:'JSON',
		  success:function(res){
			  
			  gst.val(res);
			  
		  }
	  });
	  
  }
  function getDistrict(stateId, districtId){
      
	  var state = $("#"+stateId).val();
	  var district = $("#"+districtId);
	  
	  $.ajax({
		  url:'<?= $districtStateWiseUrl?>',
		  data:{state:state},
		  dataType:'JSON',
		  success:function(res){
			  district.find("option").remove();
			  district.append("<option value=''>Select District</option>");
			  $.each(res,function(index,item){
			      district.append("<option value='"+index+"'>"+item+"</option>");
			  });
		  }
	  });
	  
  }
  
  function getCompany(districtId,companyId,model){
      
	  var district = $("#"+districtId).val();
	  var company = $("#"+companyId);
	  
	  $.ajax({
		  url:'<?= $companyUrl?>',
		  data:{district:district,model:model},
		  dataType:'JSON',
		  success:function(res){
			  company.find("option").remove();
			  company.append("<option value=''>Select Company</option>");
			  $.each(res,function(index,item){
			      company.append("<option value='"+index+"'>"+item+"</option>");
			  });
		  }
	  });
	  
  }
  
  function getGst(companyId,stateId,gstId){
      
	  var gst = $("#"+gstId);
	  var company = $("#"+companyId).val();
	  var state = $("#"+stateId).val();
	  $.ajax({
		  url:'<?=$gstUrl?>',
		  data:{company:company,state:state},
		  dataType:'JSON',
		  success:function(res){
			  gst.val(res);
		  }
	  });
	  
  }

  function getCompanyGst(model = "Company"){
      
	  var state = $("#agreement-state_id").val();
	  if(state == ""){
	      $("#error-modal .message").html("Please select Company State");
          $("#error-modal").modal("show");
          return false;
	  }
	  var company = $("#agreement-company_id").val();
	  if(company == ""){
	      $("#error-modal .message").html("Please select Company");
          $("#error-modal").modal("show");
          return false;
	  }
	  var gst = $("#agreement-gst_no");
	  getDistrict('agreement-state_id','agreement-district_id')
	
	  $.ajax({
		  url:'<?=$companyGstUrl?>',
		  type:'post',
		  data:{state:state,model:model,company:company},
		  dataType:'JSON',
		  success:function(res){
			  
			  gst.val(res);
			  
		  }
	  });
	  
  }
  
  function getStamp(company){
      var stamp = $("#agreementbill-stamp");
      var html = "";
      $.ajax({
		  url:'<?=$stampUrl?>',
		  data:{company:company},
		  dataType:'JSON',
		  success:function(res){
		      $.each(res.signature,function(index,item){
		        html += '<div class="radio"><label><input type="radio" name="AgreementBill[stamp]" value="'+index+'"> '+item+'</label></div>';
		      });
		      $(".last-bill-detail").html(res.lastBillDetail);
		      stamp.html(html);
		  }
	  });
  }

</script>
