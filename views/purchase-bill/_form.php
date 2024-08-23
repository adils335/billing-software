<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2; 
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\date\DatePicker;
use kartik\file\FileInput;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\PurchaseBill */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="purchase-bill-form">
   
    <?php $form = ActiveForm::begin(['id'=>'purchaseForm']); ?>
    <div class="row">
    <?php
	        $session = empty($model->session)?\app\models\Session::getCurrentSession():$model->session;
	     ?>
        <div class="col-md-2">
			<?= $form->field($model, "session")->widget(Select2::classname(), [
               'data' => \yii\helpers\ArrayHelper::map(\app\models\Session::find()->orderBy('id')->asArray()->all(), 'session', 'session'),
               'options' => ['placeholder' => 'Select ...','class'=>'session','value' => $session],
               'pluginOptions' => [
                            'allowClear' => true
                    ],
              ]); ?>
        </div>
        <div class="col-sm-2">
			<?= $form->field($model, "company_id")->widget(Select2::classname(), [
               'data' => \yii\helpers\ArrayHelper::map(\app\models\Company::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
               'options' => ['placeholder' => 'Select ...','class'=>'company'],
               'pluginOptions' => [
                            'allowClear' => true
                    ],
             ]); ?>
        </div>  
        
        <div class="col-sm-3">
			<?= $form->field($model, "state_id")->widget(Select2::classname(), [
               'data' => \yii\helpers\ArrayHelper::map(\app\models\State::find()->orderBy('id')->asArray()->all(), 'id', 'state'),
               'options' => ['placeholder' => 'Select ...','class'=>'state'],
               'pluginOptions' => [
                            'allowClear' => true
                    ],
             ]); ?>
        </div>  
        
        <div class="col-md-3">
            <?= $form->field($model, 'company_gstin')->textInput(['maxlength' => true]) ?>
        </div>  
    </div>
    <div class="row">
        <div class="col-sm-4">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'gstin')->textInput(['maxlength' => true]) ?>
        </div>  
        <div class="col-md-2">
            <?= $form->field($model, 'invoice_no')->textInput(['maxlength' => true]) ?>
        </div>    
        <div class="col-md-3">
            <?php if( !empty( $model->date ) ){ $model->date = \Yii::$app->formatter->asDate($model->date,'php:d-m-Y'); }?>
            <?=  $form->field($model, "date")->widget(DatePicker::classname(), [
                    'options' => ['placeholder' => 'Enter date ...'],
                    'pluginOptions' => [
                           'autoclose'=>true,
		                   'format'=>'dd-mm-yyyy',
                    ]
            ]); ?>
        </div>  
    </div>
    
    <?php echo $this->render("_items",['form'=>$form,'items'=>$items,'itemsTax'=>$itemsTax]);?>
    
    <div class="row">
         
        <div class="col-md-3">
            <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'tax')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'total')->textInput(['maxlength' => true]) ?>
        </div>    
        <div class="col-md-9">
            <?= $form->field($model, 'file')->widget(FileInput::classname(), [
                                   'options' => ['accept' => 'image/*'],
                                   'pluginOptions'=>[
                                        'allowedFileExtensions'=>['jpg', 'gif', 'png', 'bmp','pdf'],
                                        
                                        'showPreview' => false,
                                        'showCaption' => true,
                                        'showRemove' => true,
                                        'showUpload' => false,
                                        'initialPreview' => [
                                                $model->file ? Html::img(Yii::getAlias("@web/upload/purchase-bill/").$model->file) : null, // checks the models to display the preview
                                              ],
                                        'overwriteInitial' => false,
                                    ],
            ]); ?>
        </div>
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

<?php 

$gstUrl = Url::to(['district/gst']);
$formatJs = <<< JS

    $(".dynamicform_wrapper").on("afterInsert", function(e, item) {
        $(e.target).find(".item-id:last").val("");
        $(e.target).find(".particular:last").val("");
        $(e.target).find(".hsn_no:last").val("");
        $(e.target).find(".item_rate:last").val("");
        $(e.target).find(".quantity:last").val("");
        $(e.target).find(".amount:last").val("");
        $(e.target).find(".total:last").val("");
        $(e.target).find(".container-tax:last").find(".inner_item_id").val("");
        $(e.target).find(".container-tax:last").find(".rate").val("");
        $(e.target).find(".container-tax:last").find(".tax_amount").val("");
        $(e.target).find(".container-tax:last").find(".tax_id").val('').trigger("change");

    });
    
    $(".dynamicform_inner").on("afterInsert", function(e2, item1) {
            //console.log($(e2.target));
            $(e2.target).find(".container-tax").find(".tax-item:last").find(".inner_item_id").val("");
            $(e2.target).find(".container-tax").find(".tax-item:last").find(".rate").val("");
            $(e2.target).find(".container-tax").find(".tax-item:last").find(".tax_amount").val("");
            $(e2.target).find(".container-tax").find(".tax-item:last").find(".tax_id").val('').trigger("change");
            
    });
    
   
   $(document).on("keyup",".item_rate,.quantity,.rate,.amount",function(event){
       var total_amount = 0, total_tax = 0,grand_amount = 0;
       $(".amount").each(function(itemIndex){
          var rate = Number($(".item_rate").eq(itemIndex).val());
          var quantity = Number($(".quantity").eq(itemIndex).val());
          var item_amount = rate * quantity;
          $(".amount").eq(itemIndex).val((item_amount).toFixed(2))
          var amount = Number($(".amount").eq(itemIndex).val());
          var item = $(".item").eq(itemIndex);
          var tax_amount = 0,total_tax_amount = 0;
          item.find(".rate").each(function(taxIndex){
            var rate = Number(item.find(".rate").eq(taxIndex).val());
            tax_amount = Number((amount*rate/100).toFixed(2));
            total_tax_amount += tax_amount; 
            item.find(".tax_amount").eq(taxIndex).val(tax_amount);
          }); 
          var total = total_tax_amount + amount;
          $(".total").eq(itemIndex).val(total);
          total_amount += amount;
          total_tax += total_tax_amount;
          grand_amount += total;
       });
       $("#purchasebill-amount").val(total_amount);
       $("#purchasebill-tax").val(total_tax);
       $("#purchasebill-total").val(Math.round(grand_amount));
       
   });
   
  
  $("#purchasebill-state_id").change(function(){
      var model = "Company";
	  var state = $(this).val();
	  var gst = $("#purchasebill-company_gstin");
	  var companyId = $("#purchasebill-company_id").val();
	
	  $.ajax({
		  url:"$gstUrl",
		  type:'post',
		  data:{state:state,model:model,company:companyId},
		  dataType:'JSON',
		  success:function(res){
			  
			  gst.val(res);
			  
		  }
	  });
  });
   
JS;
$this->registerJs($formatJs);
