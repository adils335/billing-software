<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2; 
use kartik\depdrop\DepDrop;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\date\DatePicker;
use app\models\Payment;
use app\models\Ledger;

/* @var $this yii\web\View */
/* @var $model app\models\Payment */
/* @var $form yii\widgets\ActiveForm */
$formatter = Yii::$app->formatter;

$stateUrl = Url::to(['common/state']);
$districtStateWiseUrl = Url::to(['common/district']);
$siteUrl = Url::to(['common/site']);
$contractCompanyUrl = Url::to(['common/contract-company']);
?>
<script>
    function inputChange(inputClass,ele){console.log($("."+inputClass).index(ele));
        if($("."+inputClass).index(ele) > 0){
            return false;
        }
        if(inputClass != "date")
        $("."+inputClass).not(":first").val($("."+inputClass+":first").val()).trigger("change");
        else $("input."+inputClass).each(function(index){
                if(index > 0){
                    $(this).parent().kvDatepicker("setDate",$("input."+inputClass+":first").val());
                }
             });
    }
    
  function getContractCompany(index,companyId){
     var contract_company = $(".contract-company").eq(index); 
     $(".to-head").eq(index).val("");
     $.ajax({
		  url:'<?= $contractCompanyUrl?>',
		  type:'get',
		  data:{company_id:companyId},
		  dataType:'JSON',
		  success:function(res){
			  contract_company.find("option").remove();
			  contract_company.append("<option value=''>Select State</option>");
			  for(var key in res){
                contract_company.append("<option value='"+key+"'>"+res[key]+"</option>");
              }
		  }
	  }); 
      
  }
  
  function getState(index,companyId,model){
     var state = $(".state").eq(index); 
     $.ajax({
		  url:'<?= $stateUrl?>',
		  type:'get',
		  data:{company_id:companyId,model:model},
		  dataType:'JSON',
		  success:function(res){
			  state.find("option").remove();
			  state.append("<option value=''>Select State</option>");
			  for(var key in res){
                state.append("<option value='"+key+"'>"+res[key]+"</option>");
              }
		  }
	  }); 
      
  }
      
  function getDistrict(index,state, model){
      
	  var companyId = $(".contract-company").eq(index).val();
	  var district = $(".district").eq(index);
	  
	  $.ajax({
		  url:'<?= $districtStateWiseUrl?>',
		  type:'get',
		  data:{company_id:companyId,state:state,model:model},
		  dataType:'JSON',
		  success:function(res){
			  district.find("option").remove();
			  district.append("<option value=''>Select District</option>");
			  for(var key in res){
                district.append("<option value='"+key+"'>"+res[key]+"</option>");
              }
		  }
	  });
	  
  }
     
  function getSites(index,district, model){
      
	  var companyId = $(".contract-company").eq(index).val();
	  var state = $(".state").eq(index).val();
	  var site = $(".sites").eq(index);
	  
	  $.ajax({
		  url:'<?= $siteUrl?>',
		  type:'get',
		  data:{company_id:companyId,state:state,district:district,model:model},
		  dataType:'JSON',
		  success:function(res){
			  site.find("option").remove();
			  site.append("<option value=''>Select Site</option>");
			  for(var key in res){
                site.append("<option value='"+key+"'>"+res[key]+"</option>");
              }
		  }
	  });
	  
  }

</script>
<div class="payment-form">

    <?php $form = ActiveForm::begin(['id'=>'paymentForm']); ?>
    
	
    <div class="panel panel-default">
        <div class="panel-body">
             <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper', 
                'widgetBody' => '.container-items', 
                'widgetItem' => '.item', 
                'limit' => 15, 
                'min' => 1, 
                'insertButton' => '.add-item', 
                'deleteButton' => '.remove-item', 
                'model' => $model[0],
                'formId' => 'paymentForm',
                'formFields' => [
                    'date',
                    'ref_no',
                    'contract_sompany_id',
                    'state_id',
                    'district_id',
                    'site_id',
                    'payment_from',
                    'from_account',
                    'payment_head',
                    'particular',
                    'payment_to',
                    'worker_vendor',
                    'to_account',
                    'amount',
                    'tds_rate',
                    'tds_amount',
                    'net_amount',
                    'company_id',
                    'to_company',
                    'session',
                ],
            ]); ?>

            <div class="container-items"><!-- widgetContainer -->
            <?php foreach ($model as $i => $payment): ?>
                <div class="item panel"><!-- widgetBody -->
                    
                    <div class="">
                        <?php
                            // necessary for update action.
                            if (! $payment->isNewRecord) {
                                echo Html::activeHiddenInput($payment, "[{$i}]id",['class'=>'payment-id']);
                            }
						
                        ?>
                        <?= $form->field($payment, "[{$i}]ref_no")->hiddenInput()->label(false); ?>
                        
						<div class="row">
							<div class="col-md-2">
						<?php if($payment->date){$payment->date = $formatter->asDate($payment->date,'php:d-m-Y');}?>
					    <?=  $form->field($payment, "[{$i}]date")->widget(DatePicker::classname(), [
                             'options' => ['placeholder' => 'Enter date ...','class'=>'date'],
                             'pluginOptions' => [
                                          'autoclose'=>true,
		                                  'format'=>'dd-mm-yyyy'
                                     ]
                        ]); ?>
				            </div>
                            <div class="col-sm-3">
							<?= $form->field($payment, "[{$i}]company_id")->widget(Select2::classname(), [
                               'data' => \app\models\Common::getCompanies(),
                               'options' => ['placeholder' => 'Select ...','class'=>'company','onchange'=>$i == 0 ?'inputChange("company",this)':""],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
                            </div>
                            <div class="col-sm-2">
                              <?php $payment->session = $payment->session?$payment->session:(new \app\models\Session)->currentSession;?>    
							  <?= $form->field($payment, "[{$i}]session")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\Session::find()->orderBy(['session'=>SORT_DESC])->asArray()->all(), 'session', 'session'),
                               'options' => ['placeholder' => 'Select ...','class'=>'session','onchange'=>$i == 0 ?'inputChange("session",this)':""],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
                            </div>
                            <div class="col-sm-2">
							
							  <?= $form->field($payment, "[{$i}]from_head")->
							    dropDownList(\app\models\Payment::buildFromHead(), ['prompt'=>Yii::t('app', 'Select ...'),'class'=>'form-control from-head']) ?>
							
                            </div>
							<div class="col-sm-3">
							<?= $form->field($payment, "[{$i}]from_account")->widget(Select2::classname(), [
                               'data' => $payment->isNewRecord?"":$payment->fromAccount() ,
                               'options' => ['placeholder' => 'Select ...','class'=>'from-account','onchange'=>$i == 0 && ! $payment->isNewRecord?'inputChange("from-account",this)':""],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
                            </div>
                        </div><!-- .row -->
                        
                        <div class="row">
                            
                            <div class="col-sm-3">
							<?= $form->field($payment, "[{$i}]to_company")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\Company::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                               'options' => ['placeholder' => 'Select ...','class'=>'to-company','onchange'=>"getContractCompany($('.to-company').index(this),this.value)"],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
                            </div>
                            
                            <div class="col-sm-2">
                                <?= $form->field($payment, "[{$i}]contract_company_id")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\ContractCompany::find()->where(['company_id'=>$payment->to_company])->orderBy('id')->asArray()->all(), 'id', 'name'),
                               'options' => ['placeholder' => 'Select a Company ...','class'=>'contract-company','onchange'=>"getState($('.contract-company').index(this),this.value,'ContractCompany')"],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
                            </div>
                            
                            <div class="col-sm-2">
                                <?= $form->field($payment, "[{$i}]state_id")->widget(Select2::classname(), [
                               'data' => $payment->states,
                               'options' => ['placeholder' => 'Select a State ...', 'class'=>'state',
                               'onchange' => "getDistrict($('.state').index(this),this.value,'ContractCompany')"],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
                            </div>
                            
                            <div class="col-sm-2">
							<?= $form->field($payment, "[{$i}]district_id")->widget(Select2::classname(), [
                               'data' => $payment->districts,
                               'options' => ['placeholder' => 'Select ...','class'=>'district','onchange' => "getSites($('.district').index(this),this.value,'ContractCompany')"],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
                            </div>
							<div class="col-sm-2">
							<?= $form->field($payment, "[{$i}]site_id")->widget(Select2::classname(), [
                               'data' => $payment->sites,
                               'options' => ['placeholder' => 'Select ...','class'=>'sites'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
                            </div>
                            
                            <div class="col-sm-2">
							
                              <?= $form->field($payment, "[{$i}]to_head")
                              ->dropDownList(\app\models\Payment::buildPaymentHead(), ['prompt'=>Yii::t('app', 'Select ...'),'class'=>'form-control to-head']) ?>
							
                            </div>
                            <?php $wv_class = $payment->worker_vendor && Payment::HEAD_WORKER_PAYMENT == $payment->to_head?"":"hide";?>
                            <div class="col-sm-3 <?= $wv_class?> worker-vendor-div">
                        <?= $form->field($payment, "[{$i}]worker_vendor")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\WorkerVendor::find()->select(['id',"CONCAT(code,' ',name) as name"])->orderBy('id')->asArray()->all(), 'id', 'name'),
                               'options' => ['placeholder' => 'Select ...','class'=>'worker-vendor'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
                            </div>
							<div class="col-sm-2">
							<?= $form->field($payment, "[{$i}]to_account")->widget(Select2::classname(), [
                               'data' => $payment->isNewRecord?"":$payment->toAccount(),
                               'options' => ['placeholder' => 'Select ...','class'=>'to-account'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
                            </div>
                        
                            <div class="col-sm-4">
							<?=  $form->field($payment, "[{$i}]particular")->textarea(['class'=>'form-control particular']); ?>
                            </div>
							<div class="col-sm-2">
							
							  <?= $form->field($payment, "[{$i}]amount")->textInput(['class'=>'form-control amount']); ?>
							
                            </div>
                            <div class="col-sm-1">
							
							  <?= $form->field($payment, "[{$i}]tds_rate")->textInput(['class'=>'form-control rate']); ?>
							
                            </div>
                            <div class="col-sm-2">
							<?=  $form->field($payment, "[{$i}]tds_amount")->textInput(['class'=>'form-control tds-amount']); ?>
                            </div>
							<div class="col-sm-2">
							
							  <?= $form->field($payment, "[{$i}]net_amount")->textInput(['class'=>'form-control net-amount','readonly'=> true]);?>
							
                            </div>
							<div class="col-sm-1">
							    <br>
							    <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
							
                            </div>
                        </div><!-- .row -->
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
			
						<div class="row">
						     <div class="col-md-12">
							      <button type="button" class="add-item btn btn-success pull-right"><i class="glyphicon glyphicon-plus"></i>Add</button>
							 </div>
						</div>
            <?php DynamicFormWidget::end(); ?>
        </div>
    </div>
	
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
     $select2Options = json_encode([
    'data'=>'',         
    'multiple' => false,
    //'theme' => 'krajee',
    'placeholder' => 'Select',
    'language' => 'en-US',
    'width' => '100%',
     ]);
     
     $worker = Payment::HEAD_WORKER_PAYMENT; 
     $sitesUrl = Url::to(['sites/ajax-sites']);
     $fromHeadUrl = Url::to(['payment/ajax-from-account']);
     $toHeadUrl = Url::to(['payment/ajax-to-account']);
     $toVendorUrl = Url::to(['ledger/ajax-account-by-vendor']);
     
?>
<?php 
$formatJs = <<< JS
    
        hideElementAfterFirstRow();
        setToHead();

          $(".dynamicform_wrapper").on("afterInsert", function(e, item) {
            hideElementAfterFirstRow();
            //console.log(e);
            $(e.target).find(".container-items").find(".item:last").find(".payment-id").val('');
            $(e.target).find(".container-items").find(".item:last").find(".to-company").val('').trigger("change");
            $(e.target).find(".container-items").find(".item:last").find(".contract-company").val('').trigger("change");
            $(e.target).find(".container-items").find(".item:last").find(".state").val('').trigger("change");
            $(e.target).find(".container-items").find(".item:last").find(".district").val('').trigger("change");
            $(e.target).find(".container-items").find(".item:last").find(".sites").val('').trigger("change");
            $(e.target).find(".container-items").find(".item:last").find(".to-head").val('').trigger("change");
            $(e.target).find(".container-items").find(".item:last").find(".to-account").val('').trigger("change");
            $(e.target).find(".container-items").find(".item:last").find(".particular").val('');
            $(e.target).find(".container-items").find(".item:last").find(".rate").val('');
            $(e.target).find(".container-items").find(".item:last").find(".tds-amount").val('');
            $(e.target).find(".container-items").find(".item:last").find(".net-amount").val('');
            $(e.target).find(".container-items").find(".item:last").find(".amount").val('');
            
            var currentItem = $(".item").last();
            var from_account = $("#payment-0-from_account").val();
            $("#payment-0-from_head").trigger("change");
            currentItem.find(".from-head:not(:first-child)").val($("#payment-0-from_head").val()).trigger("change");
            currentItem.find(".company").val($("#payment-0-company_id").val()).trigger("change");
            currentItem.find(".session").val($("#payment-0-session").val()).trigger("change");  
            currentItem.find(".krajee-datepicker").val($("#payment-0-date").val()).trigger("change");
            setTimeout(function(){
                $(".from-account").val(from_account).trigger("change");
            },3000);
          });

          $(".dynamicform_wrapper").on("beforeDelete", function(e, item) {
                if (! confirm("Are you sure you want to delete this item?")) {
                       return false;
                }
                return true;
          });
      
      $("#payment-0-company_id").change(function(){
          $(".from-head").val("");
      });
      
      $(".from-account:first").change(function(){
            var from_account = $(this).val();
            //console.log(from_account);
            $(".from-account").not(":first").val(from_account).trigger("change");
      });
      
      /*
      $(document).on("change",".district",function(){
        var site = $(this).closest(".row").find(".sites");
        $.ajax({
          url:'$sitesUrl',
          data:{id:$(this).val()},
          success:function(data){
              select2Options = $select2Options;
            site.find("option").remove();
            select2Options.data = data.data;
            site.select2(select2Options);
          }
        });
      });
      */
      
      $(document).on("change","#payment-0-from_head",function(){
        var from_account = $(".from-account");
        var company_id = $("#payment-0-company_id").val();
        if(company_id == ""){
           $("#error-modal .message").html("Please select company");
           $("#error-modal").modal("show");
           return false;
        }
        $(".from-head:not(:first-child)").val($(this).val());
        $.ajax({
          url:'$fromHeadUrl',
          data:{from_head:$(this).val(),'company_id':company_id},
          success:function(data){
              select2Options = $select2Options;
            from_account.find("option").remove();
            select2Options.data = data.data;
            from_account.select2(select2Options);
          }
        });
      });
      
      $(document).on("change",".to-head",function(){
        var index = $(".to-head").index(this);
        var to_account = $(".to-account").eq(index);
        var to_company = $(".to-company").eq(index).val();
         
         if($(this).val() == $worker){
             $(".worker-vendor-div").eq(index).removeClass("hide");
         }else{
             $(".worker-vendor-div").eq(index).addClass("hide");
         }

         if($(this).val() != "" && $(this).val() != $worker){
             $.ajax({
               url:'$toHeadUrl',
               data:{to_head:$(this).val(),to_company:to_company},
               success:function(data){
                   select2Options = $select2Options;
                   to_account.find("option").remove();
                select2Options.data = data.data;
                to_account.select2(select2Options);
               }
             });
         }
        
      });
      
      $(document).on("change",".worker-vendor",function(){
           
        var vendor = $(this).val();
        var index = $(".worker-vendor").index(this);
        var account = $(".to-account").eq(index);

             $.ajax({
               url:'$toVendorUrl',
               data:{vendor:vendor},
               success:function(data){
                   select2Options = $select2Options;
                   account.find("option").remove();
                select2Options.data = data.data;
                account.select2(select2Options);
               }
             });
        
      });
      
      function hideElementAfterFirstRow(){
          
               $(".from-head").not(":first").parent().hide();
               $(".from-account").not(":first").parent().hide();
               $(".company").not(":first").parent().hide();
               $(".session").not(":first").parent().hide();
               $(".krajee-datepicker").not(":first").parent().parent().hide();
               
         
      }
      
      $(document).on("keyup",".amount,.rate,.tds-amount,net-amount",function(){
      
          var panel = $(this).closest(".panel");
          var amount = Number(panel.find(".amount").val());
          var rate = Number(panel.find(".rate").val());
          panel.find(".rate").val(rate);
          var tds_amount = amount*rate/100;
          
          panel.find(".tds-amount").val(tds_amount.toFixed(2));
          
          var net_amount = amount - tds_amount;
          
          panel.find(".net-amount").val(net_amount.toFixed(2));
          
      });
       
      function setToHead(){
        index = 0;
        $(".to-head").each(function(){
            if($(this).val() == 1)
             $(".to-account").eq(index).val(1).trigger("change");
             index++;
        });

      };
      
    $(document).on("change", ".date", function() {
        var date = $(this).val();
        $(".dynamicform_wrapper .date").each(function() {
            $(this).val(date);
        });
    });
      
      
JS;
$this->registerJs($formatJs);