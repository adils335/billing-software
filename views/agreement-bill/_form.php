<?php

use app\models\AgreementBill;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use kartik\select2\Select2; 
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\date\DatePicker;
use yii\helpers\Url;
use kartik\editors\Summernote;

$formatter = Yii::$app->formatter;
if(!empty($model->invoice_date)){
    $model->invoice_date = $formatter->asDate($model->invoice_date,'php:d-m-Y');
}
$startDate = "";
/* @var $this yii\web\View */
/* @var $model app\models\AgreementBill */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="agreement-bill-form">
<div class="agreement-bill box box-primary">
    <div class="box-header">
               
    <?php $form = ActiveForm::begin([
	       'id' => 'agreement-bill',
	]); ?>
    <?php 
        $lastBill = $model->lastBill($agreement->company_id);
        if(!empty($lastBill)):?>
            <div class="row">
                <div class="col-sm-12">
                    <b>Last Invoice No:<?= $lastBill->session."/".sprintf('%02d',$lastBill->invoice_no)?></b>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php $startDate = $formatter->asDate($lastBill->invoice_date,'php:d-m-Y');?>
                    <b>Last Invoice Date:<?= $startDate?></b>
                </div>
            </div>
        <?php endif;?> 
    <?= $form->field($model, 'agreement_id')->hiddenInput()->label(false); ?>

    <?= $form->field($model, 'invoice_no')->hiddenInput()->label(false); ?>
    <div class="row">
	
		
	    <div class="col-sm-3">
		    <?=  $form->field($model, "invoice_date")->widget(DatePicker::classname(), [
                                   'options' => ['placeholder' => 'Enter date ...'],
                                   'pluginOptions' => [
                                          'autoclose'=>true,
		                                  'format'=>'dd-mm-yyyy',
		                                  //'startDate' => $startDate,
                                       ]
                                    ]); ?>
        </div>

	    <!--<div class="col-sm-6">
            <?//= $form->field($model, 'order_no')->textarea(['maxlength' => true]) ?>
        </div>-->
    
	    <div class="col-sm-3">
		    <?= $form->field($model, 'stamp')->radioList(\app\models\Common::signature($agreement->company_id)); ?>
        </div>
	    <div class="col-sm-3">
		    <?= $form->field($model, 'tax_on_items')->checkbox([]); ?>
        </div>
	</div>
	
	<!--<div class="row">
	    <div class="col-sm-4">
            <?//= $form->field($model, 'work_name')->textarea(['maxlength' => true]) ?>
        </div>

	    <div class="col-sm-4">
             <?//= $form->field($model, 'estimate_no')->textarea(['maxlength' => true]) ?>
        </div>

	    <div class="col-sm-4">
             <?//= $form->field($model, 'section_name')->textarea(['maxlength' => true]) ?>
        </div>

	</div>-->
	
	<!--<div class="row">
	    <div class="col-sm-3">
		     <?//=  $form->field($model, "start_date")->widget(DatePicker::classname(), [
               /*'options' => ['placeholder' => 'Enter date ...'],
               'pluginOptions' => [
                      'autoclose'=>true,
                      'format'=>'dd-mm-yyyy'
                   ]
                ]);*/ 
                ?>
        </div>

	    <div class="col-sm-3">
		     <?//=  $form->field($model, "complete_date")->widget(DatePicker::classname(), [
               /*'options' => ['placeholder' => 'Enter date ...'],
               'pluginOptions' => [
                      'autoclose'=>true,
                      'format'=>'dd-mm-yyyy'
                   ]
                ]); */ 
                ?>
        </div>
		
	</div>-->
	
	<div class="row">
	    <div class="col-sm-3">
            <?= $form->field($model, "mode_of_transport")->widget(Select2::classname(), [
               'data' => \yii\helpers\ArrayHelper::map(\app\models\ModeOfTransport::find()->orderBy(['name'=>SORT_ASC])->asArray()->all(), 'id', 'name'),
               'options' => ['placeholder' => 'Select ...'],
               'pluginOptions' => [
                            'allowClear' => true
                    ],
            ]); ?>
        </div>

	    <div class="col-sm-3">
             <?= $form->field($model, 'transporter')->textInput(['maxlength' => true]) ?>
        </div>

	    <div class="col-sm-3">
             <?= $form->field($model, 'gr_no')->textInput(['maxlength' => true]) ?>
        </div>
        
        <div class="col-sm-3">
            <?= $form->field($model, 'vehicle_no')->textInput(['maxlength' => true]) ?>
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
                              ]); ?>
				</div>
				
				<div class="col-md-3">
					    <?= $form->field($model, 'billing_company_district')->label("Billing Company District")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\District::find()->where(['state_id'=>$model->billing_company_state])->orderBy('id')->asArray()->all(), 'id', 'district'),
                               'options' => ['placeholder' => 'Select a District ...',
                                             'onchange' => "getCompany('agreementbill-billing_company_district', 'agreementbill-billing_company_id')"],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
				</div>
				
				<div class="col-md-3">
					    <?= $form->field($model, 'billing_company_id')->label("Billing Company")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\BillingCompany::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                               'options' => ['placeholder' => 'Select a Company ...',
                                             'onchange' => "getGst('agreementbill-billing_company_id','agreementbill-billing_company_state','agreementbill-billing_company_gst')"],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
				</div>
				
				<div class="col-md-3">
					    <?= $form->field($model, "billing_company_gst"); ?>
				</div>
			</div>
			
	
	<?= $this->render('form/_items',['model'=>$model,'agreement'=>$agreement,'form'=>$form,'billItems'=>$billItems]);?>
	
	<?= $this->render('form/_tax',['model'=>$model,'agreement'=>$agreement,'form'=>$form,'billTaxes'=>$billTaxes]);?>

	<div class="row">
	    <div class="col-sm-8"></div>
	    <div class="col-sm-2">
          <label>Payable Amount</label>
        </div>
	    <div class="col-sm-2">
          <?= $form->field($model, 'payable_amount')->textInput(['maxlength' => true])->label(false); ?>
        </div>
	</div>
	
	<div class="row" style="display:none">
	    <div class="col-sm-6">
                     <?= $form->field($model, "company_id")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\Company::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                               'options' => ['placeholder' => 'Select ...','value' => $agreement->company_id],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
	     </div>
	     <?php
	        $session = empty($model->session)?\app\models\Session::getCurrentSession():$model->session;
	     ?>
		 <div class="col-sm-6">
                     <?= $form->field($model, "session")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\Session::find()->orderBy('id')->asArray()->all(), 'session', 'session'),
                               'options' => ['placeholder' => 'Select ...','value' => $session],
                               'pluginOptions' => [
                                        'allowClear' => true,
                                    ],
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
  
$lastBillUrl = Url::to(['agreement-bill/last-bill-amount']);
  $this->registerJs(<<< EOT_JS_CODE
    $("#agreementbill-tax_on_items").click(function(event){
        if( $(this).is(":checked") ){
            $(".item-tax-row").removeClass("hide");
            $("#payable-tax-row").addClass("hide")
        }else{
            $(".item-tax-row").addClass("hide");
            $("#payable-tax-row").removeClass("hide")
        }
    });
    $('#has-credit-note').click(function(){
        if( $(this).is(':checked') ){
            $('.credit-note-invoice-div').removeClass('hide');
        }else{
            $('.credit-note-invoice-div').addClass('hide');
        }
    });

    $('#has-percentage').click(function(){
        if( $(this).is(':checked') ){
            $('.related-invoice-div').removeClass('hide');
        }else{
            $('.related-invoice-div').addClass('hide');
        }
    });
    
    $(document).on('click','.add-item',function(){
        $( '.deduction-total' ).before( $( '.clone:first' ).clone() );
        billAmount();
    });

    $(document).on('click','.remove-item',function(){
        if($('.clone').length > 1)
        $(this).closest('.clone').remove();
        billAmount();
    });
    
    $(document).on('click','.add-penality',function(){
        $( '.penality-total' ).before( $( '.penality-clone:first' ).clone() );
        billAmount();
    });

    $(document).on('click','.remove-penality',function(){
        if($('.penality-clone').length > 1)
        $(this).closest('.penality-clone').remove();
        billAmount();
    });
    
    $('#agreementbill-related_invoice').change(function(){
        var last_bill_amount = $('#last-bill-amount');
        $.ajax({
          type:'post',    
          url:"$lastBillUrl",
          data:{invoice_no:$('#agreementbill-related_invoice').val()},
          dataType:'JSON',
          success:function(res){
              last_bill_amount.html(res);
          }
        });
      });
      
  EOT_JS_CODE);
?>

<script type="text/javascript">
    
	function billAmount(){
        var tax_on_items = $("#agreementbill-tax_on_items").is(":checked");
		var itemTotalAmount = 0;
		var total_tax_amount = 0;
		$('.item-quantity').each(function(index,element){
		    var item_rate = Number($('.item-rate').eq(index).val());
			var itemAmount = Number($('.item-quantity').eq(index).val()) * item_rate;
			$('.item-base-amount').eq(index).val(itemAmount.toFixed(2));
			if( tax_on_items ){
			    var item_tax_rate = Number($('.item-tax-rate').eq(index).val());
			    var item_tax_amount = (itemAmount*item_tax_rate)/100;
			    total_tax_amount += item_tax_amount;
			    $('.item-tax-amount').eq(index).val(item_tax_amount.toFixed(2))
			    itemAmount = itemAmount + item_tax_amount;
			}
			itemTotalAmount += itemAmount;
			$('.item-amount').eq(index).val(itemAmount.toFixed(2));
		});
		$("#agreementbill-base_amount").val(itemTotalAmount.toFixed(2));
		if( !tax_on_items ){
		    $('.tax-rate').each(function(index,element){
		    	var taxAmount = Number($('.tax-rate').eq(index).val()) * itemTotalAmount/100;
		    	total_tax_amount += taxAmount;
		    	$('.tax-amount').eq(index).val(taxAmount.toFixed(2));
		    });
		}
		$("#agreementbill-tax_amount").val(total_tax_amount.toFixed(2));
		$("#agreementbill-after_tax_total").val((itemTotalAmount + total_tax_amount).toFixed(2));	
		var payableAmount = itemTotalAmount + total_tax_amount;
		$("#agreementbill-payable_amount").val(payableAmount.toFixed(2));	
	}
	
</script>

<?php 

$districtStateWiseUrl = Url::to(['common/ship-to-district']);
$companyUrl = Url::to(['common/ship-to-company']);
$gstUrl = Url::to(['common/ship-to-gst']);

?>

<script>
      
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
  
  function getCompany(districtId,companyId){
      
	  var district = $("#"+districtId).val();
	  var company = $("#"+companyId);
	  
	  $.ajax({
		  url:'<?= $companyUrl?>',
		  data:{district:district},
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
  

</script>